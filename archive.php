<?

require("functions.php");
require("php_calendar.php");

html_top('archive');

$time = time(); 
$today = date('j',$time); 
$days = array($today=>array(NULL,NULL,'<span style="color: red; font-weight: bold; font-size: larger; text-decoration: blink;">'.$today.'</span>')); 
echo generate_calendar(date('Y', $time), date('n', $time), $days); 

html_bottom();
?>

