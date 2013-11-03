<?

require("functions.php");
require("db_functions.php");

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);


html_top('addnote');

if ( $id ) {
	db_connect();

	$query = "SELECT subject,text FROM notes where id = '$id'";
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$row = mysql_fetch_row($result, MYSQL_ASSOC);

	db_close();
}
else {
	$id = 'new';
}

#Creating table header.
echo "<form name=\"input\" action=\"notes.php\" method=\"post\">";
echo "<form action=\"notes.php\" method=\"post\">";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<table class=\"notes\" width=\"100%\" height=\"100%\" align=\"center\">\n";
echo "<th colspan=\"2\">Add note:</th>\n";
echo "<tr><td>Subject:</td>\n";
echo "<td><input type=\"text\" name=\"subject\" size=\"140\" value=\"$row[subject]\" AUTOCOMPLETE = \"off\"></td></tr>\n";
echo "<tr><td valign=\"top\">Text:</td>\n";
echo "<td><textarea rows=\"20\" cols=\"110\" name=\"text\" wrap=\"physical\">$row[text]</textarea></td></tr>";
echo "<tr colspan=\"2\"><td colspan=\"2\" align=\"right\"><input type=\"submit\" value=\"Save note\" >      ";
echo "<input type=\"reset\" value=\"Cancel\"></td></tr>";
echo "</table>";
echo "</form>";


html_bottom();
?>

