<?

require("functions.php");
require("db_functions.php");

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$NewProject = filter_input(INPUT_GET,'NewProject', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_GET,'ProjDesc', FILTER_SANITIZE_MAGIC_QUOTES);

$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_STRING);
if ( $id == '' ) {
	$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
}


function print_projects() {
    global $ConfirmDelete;

    $query = 'SELECT projid,name,description,Expand FROM projects';
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $proj[] = $row;
    }

    #Creating table header.
    echo "<table class=\"project\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Projects:</th>\n";

    foreach ( $proj as $j ) {
        if ( $ConfirmDelete == "$j[projid]" ) {
            echo "<tr class=\"important\"><td colspan=\"5\">Are you sure you want to delete this project?</td></tr>";
            echo "<tr class=\"important\">";
            echo "\t\t<td colspan=\"3\">$j[name] - $j[description]</td>\n";
            echo "\t\t<td width=1><a href=\"planner.php\"><img src=\"images/Button-Reload-icon.png\"</a></td>\n";
            echo "\t\t<td width=1><a href=\"planner.php?submit=DeleteConfirmed&id=$j[projid]\">".
                "<img src=\"images/Delete-icon-big.png\"</a></td>\n";
            $text = nl2br($j[description]);
            echo "<tr class=\"title\"><td></td><td></td><td colspan=\"3\">$text</td></tr>";
        }
        else {
            echo "<tr class=\"title\">";
            if ( $j[Expand] == '1' ) {
                echo "\t\t<td width=1><a href=\"planner.php?submit=Expand&id=$j[projid]\">".
                    "<img src=\"images/Zoom-Out-icon.png\"</a></td>\n";
            }
            else {
                echo "\t\t<td width=1><a href=\"planner.php?submit=Collapse&id=$j[projid]\">".
                    "<img src=\"images/Zoom-In-icon.png\"</a></td>\n";
            }
            echo "\t\t<td colspan=\"3\">$j[name]  -  $j[description]</td>\n";
            #echo "<td></td>";
            echo "\t\t<td width=1><a href=\"planner.php?submit=Delete&id=$j[projid]\">".
                "<img src=\"images/Delete-icon.png\"</a></td>\n";
            echo "\t</tr>\n";
            if ( $j[Expand] == '1' ) {
                #$text = nl2br($j[text]);
                #echo "<tr class=\"text\"><td></td><td></td><td colspan=\"2\">$text</td></tr>";
                $query = "SELECT id,task_description,important FROM tasks where isnull(solved_date) and projid=$j[projid]";
                $tasks = get_table( $query );
                print_tasks($tasks, "important");
                print_tasks($tasks);
            }
        }
    }
    echo "</table>\n";
#    echo "<br><br>";
#    echo "<a class=\"button\" href=\"addnote.php\"><span>Add new note</span></a>";

    mysql_free_result($result);
}


function print_unassigned_tasks() {

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date>CURDATE()-1 and isnull(On_Hold) and isnull(projid)';
    $todays_task = get_table( $query );

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date<CURDATE() and isnull(On_Hold) and isnull(projid)';
    $yesterdays_task = get_table( $query );

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks not assigned to any project:</th>\n";

    print_tasks($yesterdays_task, "important");
    print_tasks($todays_task, "important");

    print_tasks($yesterdays_task, "yesterday");
    print_tasks($todays_task);

    echo "</table>\n";

    mysql_free_result($result);
}






html_top('planner');

db_connect();

if ( $NewProject ) {
	$query = "insert into projects (name, description) values ('$NewProject', '$description')";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
    reloadpage("planner.php");
}

elseif ( $submit == 'Expand' ) {
	$query = "update projects set Expand=0 where projid=$id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

}
elseif ( $submit == 'Collapse' ) {
	$query = "update projects set Expand=1 where projid=$id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
}
elseif ( $submit == 'Delete' ) {
	$ConfirmDelete = $id;
	Echo "Yup, $ConfirmDelete = $id<br>";
}
elseif ( $submit == 'DeleteConfirmed' ) {
	$query = "delete from projects where projid=$id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
}

print_projects();

if ( $submit == 'newproj' ) {
    print_new_project_field();
}
else {
    echo "<tr><td colspan=\"4\"><a href=\"planner.php?submit=newproj\">New project</a></td></tr>";
}

echo "<br><br>";
echo "<br><br>";

print_unassigned_tasks();

db_close();

html_bottom();
?>

