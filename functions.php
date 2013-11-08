<?php




function html_top($current) {

?>
<!doctype html>
<html lang="en-US">
<head>
	<meta charset="UTF-8" />
	<title>TaskList</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link href="table.css" rel="stylesheet" type="text/css" />
	<link href="button.css" rel="stylesheet" type="text/css" />

	<script src="../js/jquery-1.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="../js/loopedslider.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		$(function(){
			$('#slider').loopedSlider({
				autoStart: 6000,
				restart: 5000
			});
		});
	</script>
</head>

<body class="normalpage">

<section id="page">
	<div id="bodywrap">
	<section id="top">
	<nav>
		<h1 id="sitename">
  		<a href="#">TaskList</a></h1>
  
  		<ul id="sitenav">
<?
			if($current == 'home') {
  				?> <li class="current"><a href="index.php">Today</a></li> <?
			}
			else {
  				?> <li><a href="index.php">Today</a></li> <?
			}
			if($current == 'planner') {
                                ?> <li class="current"><a href="planner.php">Planner</a></li> <?
                        }
                        else {
                                ?> <li><a href="planner.php">Planner</a></li> <?
                        }
			if($current == 'notes' || $current == 'addnote') {
                                ?> <li class="current"><a href="notes.php">Notes</a></li> <?
                        }
                        else {
                                ?> <li><a href="notes.php">Notes</a></li> <?
                        }
			if($current == 'blog') {
                                ?> <li class="current"><a href="blog.php">Blog</a></li> <?
                        }
                        else {
                                ?> <li><a href="blog.php">Blog</a></li> <?
                        }
			if($current == 'contact') {
                                ?> <li class="current"><a href="contact.php">Contact</a></li> <?
                        }
                        else {
                                ?> <li><a href="contact.php">Contact</a></li> <?
                        }
?>
			
  		</ul>
  
	</nav>

	<header id="normalheader"></header>
</section>

<?
#if ( $current == 'home' ) {
#	echo "<a class=\"button\" href=\"#\"><span>Tomorrow</span></a>";
#	echo "<a class=\"button\" href=\"#\"><span>This week</span></a>";
#	echo "<a class=\"button\" href=\"#\"><span>On hold</span></a>";
#	echo "<a class=\"button\" href=\"#\"><span>Resolved today</span></a>";
#}
#elseif ( $current == 'notes' ) {
#	echo "<a class=\"button\" href=\"addnote.php\"><span>Add new note</span></a>";
#}
#elseif ( $current == 'addnote' ) {
#	echo "<form name=\"input\" action=\"notes.php\" method=\"post\">";
#	echo "<input type=\"submit\" value=\"Submit\" class=\"button\" />";
	#echo "<a class=\"button\" href=\"notes.php\"><span>Submit new note</span></a>";
#	echo "<a class=\"button\" href=\"notes.php\"><span>Cancel</span></a>";
#}

?>
<section id="contentwrap">
	<div id="contents">
	<div id="topcolumns">
<?
}


function reloadpage($url) {
?>
	<script language="Javascript">
		window.location = "<?=$url?>";
	</script>
<?
}


function html_bottom() {
?>
	</div>
	<div class="clear"></div>
	</div>

	</section>
	</body>
	</html>
<?
}


?>
