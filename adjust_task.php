<?php

require("functions.php");
require("db_functions.php");
require("php_calendar.php");

$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);

db_connect();

?>
<!doctype html>
<html lang="en-US">
<head>
	<meta charset="UTF-8" />
	<title>TaskList</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link href="table.css" rel="stylesheet" type="text/css" />
	<link href="button.css" rel="stylesheet" type="text/css" />

<section id="contentwrap">
	<div id="contents">
	<div id="topcolumns">
<?

# Generating calender 
$time  = time(); 
$today = date('j',$time); 
$year  = date("Y");
$month = date("m");
$days_this_month = date('t', mktime(0, 0, 0, $month, 1, $year));
$days_next_month = date('t', mktime(0, 0, 0, $month+1, 1, $year));

$days[$today] = array(NULL,NULL,'<span style="color: red; font-weight: bold; font-size: larger; text-decoration: blink;">'.$today.'</span>');
for ( $i=$today+1; $i<=$days_this_month; $i++ ) {
        $days[$i] = array('index.php','linked-day');
}

#print_task_description( $id );

echo "<table class=\"task\" align=\"center\"><tr><th colspan=\"3\">Please select when the task is due to be finished.<br><br></th></tr>";
echo "<tr><td>";
echo generate_calendar(date('Y', $time), date('n', $time), $days); 
echo "</td> <td width='30'></td>";
for ( $i=1; $i<=$days_next_month; $i++ ) {
        $days[$i] = array('index.php','linked-day');
}
echo "<td>";
echo generate_calendar(date('Y', $time), date('n', $time)+1, $days); 
echo "</td></tr>";

echo "</div>";

echo "<tr><td>";
#echo "<a class=\"button\" href='index.php?submit=On_hold&id=".$id."'><span>Put this task on hold for now</span></a>";
#echo "<a class=\"button\" href=\"javascript:var URL = 'index.php?submit=On_hold&id=".$id."';window.opener.location.href = URL; window.close()\"><span>Put this task on hold for now</span></a>";

echo "</td></tr>";
echo "</table>";

#<a href="javascript:var URL = 'pf_virtual.php';window.opener.location.href = URL; window.close()">OK, jeg er vill og gal og tar sjansen</a>

echo "</div>";
echo "</section>";

html_bottom();
?>

