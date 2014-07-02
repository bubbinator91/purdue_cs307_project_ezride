<?php
    $groupid = $_GET['groupid'];
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail' . mysql_error());
    mysql_select_db('ezride') or die('Select DB ezride fail.');

    $querygroups = "DELETE FROM groups WHERE groupid='" . $groupid . "'";
    mysql_query($querygroups) or die('querygroups fail:' . mysql_error());



    $queryusergroups = "DELETE FROM usergroups WHERE groupid='" . $groupid . "'";
    mysql_query($queryusergroups) or die('queryusergroups fail:' . mysql_error());


    $querymessages = "DELETE FROM messages WHERE groupid='" . $groupid . "'";
    mysql_query($querymessages) or die('querymessages fail:' . mysql_error());

    /* delete "groups" in calendar, delete related events in table "event", also events in "usercalendar"*/
    $queryselect = "SELECT * FROM groupcalendar WHERE groupid='" . $groupid . "'";
    $result = mysql_query($queryselect) or die('queryselect fail:' . mysql_error());
    if(mysql_num_rows($result) != 0){
         while($row = mysql_fetch_array($result,MYSQLI_ASSOC)){
            $queryevent = "DELETE FROM events WHERE eventid='" . $row['eventid'] . "'";
            mysql_query($queryevent) or die('queryevent fail:' . mysql_error());
         }
    }

    header('Location: YOUR_DOMAIN_HERE/mygroups.php');

    //else {
         //echo "no events in this group";
    //}

    $querygroupcalendar = "DELETE FROM groupcalendar WHERE groupid='" . $groupid . "'";
    mysql_query($querygroupcalendar) or die ('querygroupcalendar fail:' . mysql_error());


?>
