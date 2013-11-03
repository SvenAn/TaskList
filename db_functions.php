<?php




function db_connect() {
    $dbconn = mysql_connect('localhost', 'tl_user', 'hegemor123');
    if (!$dbconn) {
        die('Could not connect: ' . mysql_error());
    }
    #echo 'Connected successfully';

    mysql_select_db('tasklist') or die('Could not select database');
}


function print_tasks($task, $alt) {
    foreach ( $task as $j ) {
        if ( ($j[important] == 'y' && $alt == 'important') || ($j[important] == 'n' && $alt != 'important')) {
            echo "\t<tr class=\"$alt\">\n";
            #echo "\t\t<td width=1><a href=\"?submit=Important&id=$j[id]&state=$j[important]\">" . 
            #"<img src=\"images/important.png\"</a></td>\n";
            echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"".
                "window.open('adjust_task.php?id=$j[id]','','toolbar=no,".
                "directories=no, location=no, status=no, menubar=no, resizable=no,".
                " scrollbars=no, width=1000, height=350,".
                "tleft=300')\"><img src=\"images/settings.png\"></a></td>\n";
            if ( $alt == 'yesterday' ) {
                echo "\t\t<td width=1><a href=\"".$_SERVER['PHP_SELF']."?submit=Today&id=$j[id]\">".
                "<img src=\"images/today.png\"</a></td>\n";

            }
            else {
                echo "\t\t<td width=1><a href=\"".$_SERVER['PHP_SELF']."?submit=NextDay&id=$j[id]\">".
                "<img src=\"images/next-day.png\"</a></td>\n";
            }
            echo "\t\t<td class=\"alt\">$j[task_description]</td>\n";
            echo "\t\t<td width=1><a href=\"".$_SERVER['PHP_SELF']."?submit=TaskDone&id=$j[id]\">".
            "<img src=\"images/done.png\"</a></td>\n";
            echo "\t</tr>\n";
        }
    }
}


function get_table( $query ) {
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    return $task;
}


function print_today_task_table()  {

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date=CURDATE() and isnull(On_Hold)';
    $todays_task = get_table( $query );

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date<CURDATE() and isnull(On_Hold)';
    $yesterdays_task = get_table( $query );

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks due today or earlier:</th>\n";

    print_tasks($yesterdays_task, "important");
    print_tasks($todays_task, "important");

    print_tasks($yesterdays_task, "yesterday");
    print_tasks($todays_task);

    echo "</table>\n";

    mysql_free_result($result);
}


function print_yesterdays_task_table()  {
    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date<CURDATE()';
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks due yesterday:</th>\n";

    print_important_tasks( $task );

    print_not_so_important_tasks( $task );

    echo "</table>\n";

    mysql_free_result($result);
}


function print_tomorrow_task_table()  {
    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date>CURDATE()';
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks due tomorrow:</th>\n";

    print_important_tasks( $task );

    print_not_so_important_tasks( $task );

    echo "</table>\n";

    mysql_free_result($result);
}


function print_new_task_field() {
    echo "<form>";
    echo "   <table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">";
    echo "   <th>New Task: <input type=\"text\" size=\"115\" name=\"NewTask\" id=\"NewTask\"".
    " placeholder=\"Enter new task.\"";
    echo "       autofocus class=\"textfield\" AUTOCOMPLETE = \"off\">";
    echo "   </table>";
    echo "</form>";
}


function print_new_project_field() {
    echo "<form>";
    echo "   <table class=\"notes\" width=\"100%\" height=\"100%\" align=\"center\">";
    echo "   <tr><th colspan=\"2\">New project: </th></tr>";
    echo "   <tr><td>Name:</td><td><input type=\"text\" size=\"100%\" name=\"NewProject\" id=\"NewProject\"".
        " placeholder=\"Enter new project name.\" autofocus class=\"textfield\" AUTOCOMPLETE = \"off\"></td></tr>";
    echo "  <tr><td>Description:</td><td><input type=\"text\" size=\"100%\" name=\"ProjDesc\" id=\"ProjDesc\"".
        " placeholder=\"Project description.\" class=\"textfield\" AUTOCOMPLETE = \"off\"></td></tr>";
    echo "  <tr><td colspan=\"2\"><input type=\"submit\" value=\"submit\"</td></tr>";
    echo "   </table>";
    echo "</form>";
}


function db_close() {
	mysql_close($dbconn);
}
