<?php

require("functions.php");
require("db_functions.php");


$url='index.php';

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$newtask = filter_input(INPUT_GET,'NewTask', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_GET,'state', FILTER_SANITIZE_STRING);
$table = filter_input(INPUT_GET,'table', FILTER_SANITIZE_STRING);




if ( $newtask ) {
	$dbconn = db_connect();
	$query = "insert into tasks (created_date, due_date, task_description) values ( curdate(), curdate(), '$newtask')";
	$result = mysqli_query($dbconn, $query) or die('Query failed: ' . mysqli_error($dbconn));

	reloadpage( $url );
}

check_submits( $submit, $id, $state );


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

