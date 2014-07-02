<?php
	$userid = $_GET['userid'];
	$groupid = $_GET['groupid'];
	define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail' . mysql_error());
    mysql_select_db('ezride') or die('Select DB ezride fail.');

    $checkjoin = "SELECT * FROM usergroups WHERE userid='" . $userid . "' AND groupid='" . $groupid ."'";
    $result = mysql_query($checkjoin) or die ('checkjoin error');

    if(mysql_num_rows($result) == 0 ){
        $queryjoin = "INSERT INTO usergroups(userid, groupid) VALUES ('" . $userid . "', '" . $groupid . "')" or die('insert usergroups fail');
        mysql_query($queryjoin) or die('insert to usergroup fail --- mysql_query()');
        echo "user joins group.\n";
        header('Location: YOUR_DOMAIN_HERE/mygroups.php');
    }else{
        echo "user exists in group.\n";
        header('Location: YOUR_DOMAIN_HERE/mygroups.php');
    }

    mysql_close(); 
?>