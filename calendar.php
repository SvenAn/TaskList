<?php

require("functions.php");
require("php_calendar.php");

$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);


?>
<!doctype html>
<html lang="en-US">
<head>
	<meta charset="UTF-8" />
	<title>TaskList</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link href="table.css" rel="stylesheet" type="text/css" />
	<link href="button.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE 6]>
		<script src="../js/belatedPNG.js"></script>
		<script>
			DD_belatedPNG.fix('*');
		</script>
	<![endif]-->

<section id="contentwrap">
	<div id="contents">
	<div id="topcolumns">
<?

$time = time(); 
$today = date('j',$time); 
$days = array($today=>array(NULL,NULL,'<span style="color: red; font-weight: bold; font-size: larger; text-decoration: blink;">'.$today.'</span>')); 
echo generate_calendar(date('Y', $time), date('n', $time), $days); 

echo "</div>";
#echo "<a class=\"button\" href='index.php?submit=On_hold&id=".$id."'><span>Put this task on hold for now</span></a>";
echo "<a class=\"button\" href=\"javascript:var URL = 'index.php?submit=On_hold&id=".$id."';window.opener.location.href = URL; window.close()\"><span>Put this task on hold for now</span></a>";

#<a href="javascript:var URL = 'pf_virtual.php';window.opener.location.href = URL; window.close()">OK, jeg er vill og gal og tar sjansen</a>

echo "</div>";
echo "</section>";

html_bottom();
?>

