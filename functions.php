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
    <script>
        function loadXMLDoc( Url, Myid, submit ) {
            var xmlhttp;
            xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    document.getElementById( "TableDiv" ).innerHTML=xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", Url + "?table=yes&id=" + Myid + submit , true);
            xmlhttp.send();
        }
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
<?php
			if($current == 'home') {
  				?> <li class="current"><a href="index.php">Today</a></li> <?php
			}
			else {
  				?> <li><a href="index.php">Today</a></li> <?php
			}
			if($current == 'planner') {
                                ?> <li class="current"><a href="planner.php">Planner</a></li> <?php
                        }
                        else {
                                ?> <li><a href="planner.php">Planner</a></li> <?php
                        }
			if($current == 'notes' || $current == 'addnote') {
                                ?> <li class="current"><a href="notes.php">Notes</a></li> <?php
                        }
                        else {
                                ?> <li><a href="notes.php">Notes</a></li> <?php
                        }
			if($current == 'blog') {
                                ?> <li class="current"><a href="blog.php">Blog</a></li> <?php
                        }
                        else {
                                ?> <li><a href="blog.php">Blog</a></li> <?php
                        }
			if($current == 'contact') {
                                ?> <li class="current"><a href="contact.php">Contact</a></li> <?php
                        }
                        else {
                                ?> <li><a href="contact.php">Contact</a></li> <?php
                        }
	print "</ul>";
	print "</nav>";
	print "<header id=\"normalheader\"></header>";
	print "</section>";
	print "<section id=\"contentwrap\">";
	print "<div id=\"contents\">";
	print "<div id=\"topcolumns\">";
}


function reloadpage($url) {
	print "<script language=\"Javascript\">";
	print "window.location = \"$url\"";
	print "</script>";
}


function html_bottom() {
	print "</section>";
	print "</div>";
	print "<div class=\"clear\"></div>";
	print "</div>";
	print "</section>";
	print "</body>";
	print "</html>";
}

?>
