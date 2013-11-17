<?php



function db_connect() {
    $dbconn = mysql_connect('localhost', 'tl_user', 'hegemor123');
    if (!$dbconn) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db('tasklist') or die('Could not select database');
}


function print_tasks( $task, $alt, $expand, $url="none" ) {
    if ( $url == "none" ) { $url = $_SERVER['PHP_SELF']; }

    foreach ( $task as $j ) {
        if ( ($j['important'] == 'y' && $alt == 'important') || ($j['important'] == 'n' && $alt != 'important')) {
            echo "\t<tr class=\"$alt\">\n";

            if ( $j['id'] == $expand ) {
                # Print "Less options" button.
                echo "\t\t<td width=1><a href=\"javascript:void(0)\" title='Less options' onClick=\"loadXMLDoc( '$url', 0, '' )\"".
                     "><img src=\"images/Retract.png\"></a><br></td>\n";
                # Get task done today... 
                if ( $alt == 'yesterday' ) {
                    echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc( '$url', $j[id], '&submit=Today')\" ".
                         "title='Get task done today'><img src=\"images/Today.gif\"></a>\n";
                }
                # ...or tomorrow.
                else {
                    echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc('$url', $j[id], '&submit=NextDay')\"" .
                         "title='Get task done tomorrow'><img src=\"images/NextDay.gif\"></a>\n";
                }
                # Print "Importance" button.
                echo "\t\t<a href=\"javascript:void(0)\" onClick=\"loadXMLDoc('$url', $j[id], '&submit=Important&state=$j[important]')\"" .
                     "title='Toggle importance'><img src=\"images/Important.png\"></a></td>\n";
                # Print task description.
                echo "\t\t<td class=\"alt\">$j[task_description]<br>\n";
                # Print "postpone until tomorrow" button...
                if ( $alt == 'yesterday' ) {
                    echo "\t\t<a href=\"javascript:void(0)\" onClick=\"loadXMLDoc('$url', $j[id], '&submit=NextDay')\"" .
                         "title='Get task done tomorrow'><img src=\"images/NextDay.gif\"></a>\n";
                }
                #Print "postpone until next week" button.
                echo "\t\t<a href=\"".$url."?submit=NextWeek&id=$j[id]\" title='Get task done next week'><img src=\"images/NextWeek.gif\"></a>\n";
                #Put task on hold.
                echo "\t\t<a href=\"?submit=On_hold&id=$j[id]\" title='Put task on Hold'><img src=\"images/Hold.png\"</a>\n";
            }
            else {
                # Print "More options" button.
                echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc( '$url', $j[id], '')\" ".
                     "title='More options'><img src=\"images/Expand.png\"></a></td>\n";
                # Get task done today... 
                if ( $alt == 'yesterday' ) {
                    echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc( '$url', $j[id], '&submit=Today')\" ".
                         "title='Get task done today'><img src=\"images/Today.gif\"></a></td>\n";
                }
                # ...or tomorrow.
                else {
                    echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc('$url', $j[id], '&submit=NextDay')\" " .
                         "title='Get task done tomorrow'><img src=\"images/NextDay.gif\"></a></td>\n";
                }
                # Print task description.
                echo "\t\t<td class=\"alt\">$j[task_description]</td>\n";
            }
            #Print Done button.
            echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"loadXMLDoc( '$url', $j[id], '&submit=TaskDone')\" ".
                 "title='Task done!'><img src=\"images/done.png\"></a></td>\n";
            echo "\t</tr>\n";
        }
    }
}


function get_table( $query ) {
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    return $task;
}


function print_today_task_table( $expand="0", $url="none" )  {

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date=CURDATE() and isnull(On_Hold)';
    $todays_task = get_table( $query );

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date<CURDATE() and isnull(On_Hold)';
    $yesterdays_task = get_table( $query );

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<tr><th colspan=\"5\">Tasks due today or earlier:</th></tr>\n";

    print_tasks( $yesterdays_task, "important", $expand, $url );
    print_tasks( $todays_task, "important", $expand, $url );

    print_tasks( $yesterdays_task, "yesterday", $expand, $url );
    print_tasks( $todays_task, "none", $expand, $url );

    echo "</table>\n";

#    mysql_free_result($todays_task);
#    mysql_free_result($yesterdays_task);
}


function print_tomorrows_task_table( $expand="0", $url="none" )  {

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date>CURDATE() and isnull(On_Hold)';
    $tomorrows_tasks = get_table( $query );

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<tr><th colspan=\"5\">Tasks due tomorrow or later:</th></tr>\n";

    print_tasks( $tomorrows_tasks, "important", $expand, $url );
    print_tasks( $tomorrows_tasks, "none", $expand, $url );

    echo "</table>\n";

#    mysql_free_result($tomorrows_tasks);
}


function print_tasks_on_hold_table( $expand="0", $url="none" )  {

    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and NOT isnull(On_Hold)';
    $tasks_on_hold = get_table( $query );

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<tr><th colspan=\"5\">Tasks on hold:</th></tr>\n";

    print_tasks( $tasks_on_hold, "important", $expand, $url );
    print_tasks( $tasks_on_hold, "none", $expand, $url );

    echo "</table>\n";

#    mysql_free_result($tasks_on_hold);
}


function print_yesterdays_task_table()  {
    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date<CURDATE()';
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks due yesterday:</th>\n";

    print_important_tasks( $task );

    print_not_so_important_tasks( $task );

    echo "</table>\n";

    mysql_free_result($result);
}


function print_tomorrow_task_table()  {
    $query = 'SELECT id,task_description,important FROM tasks where isnull(solved_date) and due_date>CURDATE()';
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $num_rows = mysql_num_rows($result);
    $i = 0;
    while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
        $task[$i] = $row;
        $i++;
    }

    #Creating table header.
    echo "<table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "<th colspan=\"5\">Tasks due tomorrow:</th>\n";

    print_important_tasks( $task );

    print_not_so_important_tasks( $task );

    echo "</table>\n";

    mysql_free_result($result);
}


function print_new_task_field() {
    echo "<form>";
    echo "   <table class=\"tasks\" width=\"100%\" height=\"100%\" align=\"center\">\n";
    echo "   <tr><th>New Task: <input type=\"text\" size=\"115\" name=\"NewTask\" id=\"NewTask\"\n".
       "    placeholder=\"Enter new task.\" autofocus class=\"textfield\" AUTOCOMPLETE = \"off\">\n";
    echo "   </tr></table>\n";
    echo "</form>\n";
}


function print_new_project_field() {
    echo "<form>";
    echo "   <table class=\"notes\" width=\"100%\" height=\"100%\" align=\"center\">";
    echo "   <tr><th colspan=\"2\">New project: </th></tr>";
    echo "   <tr><td>Name:</td><td><input type=\"text\" size=\"100%\" name=\"NewProject\" id=\"NewProject\"".
        " placeholder=\"Enter new project name.\" autofocus class=\"textfield\" AUTOCOMPLETE = \"off\"></td></tr>";
    echo "  <tr><td>Description:</td><td><input type=\"text\" size=\"100%\" name=\"ProjDesc\" id=\"ProjDesc\"".
        " placeholder=\"Project description.\" class=\"textfield\" AUTOCOMPLETE = \"off\"></td></tr>";
    echo "  <tr><td colspan=\"2\"><input type=\"submit\" value=\"submit\"</td></tr>";
    echo "   </table>";
    echo "</form>";
}


function check_submits( $mysubmit='none', $id=0, $importantstate='0' ) {
    if ( $mysubmit != 'none' && $id !=0 ) {

        # Toggle importance
        if ( $mysubmit == 'Important' ) {
            if ( $importantstate == 'n' ) {
                $query = "update tasks set important='y' where id=$id";
            }
            elseif ( $importantstate == 'y' ) {
                $query = "update tasks set important='n' where id=$id";
            }
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
            $id = 0;
        }

        # Marking task as Done.
        elseif ( $mysubmit == 'TaskDone' ) {
            $query = "update tasks set solved_date=CURDATE() where id=$id";
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        }

        #Getting task done today.
        elseif ( $mysubmit == 'Today' ) {
            $query = "update tasks set due_date=CURDATE() where id=$id";
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
            $id = 0;
        }

        # Getting task done tomorrow.
        elseif ( $mysubmit == 'NextDay' ) {
            $query = "update tasks set due_date=CURDATE()+1 where id=$id";
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        }

        # Toggle "On hold" status.
        elseif ( $mysubmit == 'On_hold' ) {
            $query = "select on_hold from tasks where id=$id";
            $result = get_table( $query );

            foreach ( $result as $j ) {
                if ( $j['on_hold'] == 1 ) {
                    $query = "update tasks set on_hold = NULL, due_date=CURDATE() where id=$id";
                }
                else {
                    $query = "update tasks set on_hold='1' where id=$id";
                }
            }
            $result = mysql_query($query) or die('Query failed: ' . mysql_error());
            $id = 0;
        }


    }
}



function db_close() {
#	mysql_close($dbconn);
}



# Nice looking button. May never use it:
#                     "<a class=\"button\" href=\"javascript:var URL = 'index.php?submit=On_hold&id=".$id."';" .
#                     "window.opener.location.href = URL; window.close()\"><span>H</span></a></td>";


# Opens a new window with a calendar.  For future use.
            #echo "\t\t<td width=1><a href=\"javascript:void(0)\" onClick=\"".
            #    "window.open('adjust_task.php?id=$j[id]','','toolbar=no,".
            #    "directories=no, location=no, status=no, menubar=no, resizable=no,".
            #    " scrollbars=no, width=1000, height=350,".
            #    "tleft=300')\"><img src=\"images/settings.png\"></a></td>\n";
