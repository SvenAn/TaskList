<?

require("functions.php");
require("db_functions.php");

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$table = filter_input(INPUT_GET,'table', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_GET,'state', FILTER_SANITIZE_STRING);

$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_STRING);
if ( $id == '' ) {
	$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
}

db_connect();

check_submits( $submit, $id, $state );

if ( ! $table ) {
    html_top('planner');
}

print_today_task_table( $id, 'planner.php' );

echo "<br><br>";

print_tomorrows_task_table( $id, 'planner.php' );

echo "<br><br>";

print_tasks_on_hold_table( $id, 'planner.php' );

echo "</div>\n";

if ( ! $table ) {
    html_bottom();
}

db_close();

?>
