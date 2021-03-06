<?php

require("functions.php");
require("db_functions.php");

#Checking for input data:
$submit = filter_input(INPUT_GET,'submit', FILTER_SANITIZE_STRING);
$subject = filter_input(INPUT_POST,'subject', FILTER_SANITIZE_STRING);
$text  = filter_input(INPUT_POST,'text', FILTER_SANITIZE_MAGIC_QUOTES);

$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_STRING);
if ( $id == '' ) {
	$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
}


function print_notes( $dbconn ) {
    global $ConfirmDelete;

    $query = 'SELECT id,subject,text,Expand FROM notes';
    $result = mysqli_query( $dbconn, $query ) or die( 'Query failed: ' . mysqli_error( $dbconn ) );

    $i = 0;
    while ($row = mysqli_fetch_row( $result )) {
        $note[$i] = $row;
        $i++;
    }

    #Creating table header.
    echo "<table class=\"notes\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Notes:</th>\n";

    foreach ( $note as $j ) {
        if ( $ConfirmDelete == "$j[0]" ) {
            echo "<tr class=\"important\"><td colspan=\"5\">Are you sure you want to delete this note?</td></tr>";
            echo "<tr class=\"important\">";
            echo "\t\t<td colspan=\"3\">$j[1]</td>\n";
            echo "\t\t<td width=1><a href=\"notes.php\"><img src=\"images/Button-Reload-icon.png\"</a></td>\n";
            echo "\t\t<td width=1><a href=\"notes.php?submit=DeleteConfirmed&id=$j[0]\">".
                "<img src=\"images/Delete-icon-big.png\"</a></td>\n";
            $text = nl2br($j[2]);
            echo "<tr class=\"text\"><td></td><td></td><td colspan=\"3\">$text</td></tr>";
        }
        else {
            echo "<tr>";
            echo "\t\t<td width=1><a href=\"addnote.php?submit=edit&id=$j[0]\">".
                "<img src=\"images/Edit.png\"</a></td>\n";
            if ( $j[3] == '1' ) {
                echo "\t\t<td width=1><a href=\"notes.php?submit=Expand&id=$j[0]\">".
                    "<img src=\"images/Zoom-Out-icon.png\"</a></td>\n";
            }
            else {
                echo "\t\t<td width=1><a href=\"notes.php?submit=Collapse&id=$j[0]\">".
                    "<img src=\"images/Zoom-In-icon.png\"</a></td>\n";
            }
            echo "\t\t<td>$j[1]</td>\n";
            echo "<td></td>";
            echo "\t\t<td width=1><a href=\"notes.php?submit=Delete&id=$j[0]\">".
                "<img src=\"images/Delete-icon.png\"</a></td>\n";
            echo "\t</tr>\n";
            if ( $j[3] == '1' ) {
                $text = nl2br($j[2]);
                echo "<tr class=\"text\"><td></td><td></td><td colspan=\"2\">$text</td></tr>";
            }
        }
    }
    echo "</table>\n";
    echo "<br><br>";
    echo "<a class=\"button\" href=\"addnote.php\"><span>Add new note</span></a>";

    mysqli_free_result($result);
}



html_top('notes');

$dbconn = db_connect();

if ( $subject ) {
	if ( $id == 'new' ) {
		$query = "insert into notes (subject, text) values ('$subject', '$text')";
	}
	else {
		$query = "update notes set subject='$subject', text='$text' where id=$id";
	}

	$result = mysqli_query( $dbconn, $query ) or die( 'Query failed: ' . mysql_error( $dbconn ) );
}
elseif ( $submit == 'Expand' ) {
	$query = "update notes set Expand=0 where id=$id";
	$result = mysqli_query( $dbconn, $query) or die( 'Query failed: ' . mysql_error( $dbconn ) );

}
elseif ( $submit == 'Collapse' ) {
	$query = "update notes set Expand=1 where id=$id";
	$result = mysqli_query( $dbconn, $query) or die( 'Query failed: ' . mysql_error( $dbconn ) );
}
elseif ( $submit == 'Delete' ) {
	$ConfirmDelete = $id;
	Echo "Yup, $ConfirmDelete = $id<br>";
}
elseif ( $submit == 'DeleteConfirmed' ) {
	$query = "delete from notes where id=$id";
	$result = mysqli_query( $dbconn, $query) or die( 'Query failed: ' . mysql_error( $dbconn ) );
}

print_notes( $dbconn );

db_close();
