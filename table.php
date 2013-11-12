<?

require("db_functions.php");

$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);

db_connect();

print_today_task_table( $id, "index.php" );


db_close();

?>

