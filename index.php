<?

require("functions.php");
require("db_functions.php");


$url='index.php';

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$newtask = filter_input(INPUT_GET,'NewTask', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_GET,'state', FILTER_SANITIZE_STRING);
$table = filter_input(INPUT_GET,'table', FILTER_SANITIZE_STRING);


db_connect();


if ( $submit == 'Important' ) {
	if ( $state == 'n' ) {
		$query = "update tasks set important='y' where id=$id";
	}
	elseif ( $state == 'y' ) {
		$query = "update tasks set important='n' where id=$id";
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	reloadpage( $url );
}


if ( $submit == 'TaskDone' ) {
	$query = "update tasks set solved_date=CURDATE() where id=$id";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	reloadpage( $url );
}


if ( $newtask ) {
	$query = "insert into tasks (created_date, due_date, task_description) values ( curdate(), curdate(), '$newtask')";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());

	reloadpage( $url );
}


if ( $submit == 'Today' ) {
        $query = "update tasks set due_date=CURDATE() where id=$id";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $id = 0;
#        reloadpage( $url );
}


if ( $submit == 'NextDay' ) {
        $query = "update tasks set due_date=CURDATE()+1 where id=$id";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

        reloadpage( $url );
}

if ( $submit == 'On_hold' ) {
        $query = "update tasks set on_hold='1' where id=$id";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

        reloadpage( $url );
}

if ( ! $table ) {
    html_top('home');
}

echo "<div id=TableDiv>\n";

print_today_task_table( $id, "index.php" );

echo "</div>\n";

#mysql_free_result($result);

echo "<br> <br>\n";

db_close();


if ( ! $table ) {
    print_new_task_field();

    html_bottom();
}
?>

