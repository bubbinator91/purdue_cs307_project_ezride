<?php
    $title = $_POST['title'];
    $details = $_POST['details'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail' . mysql_error());
    mysql_select_db('ezride') or die('Select DB ezride fail.' . mysql_error());

    $query = "INSERT INTO `events` (`eventid`, `title`, `details`, `start`, `end`) VALUES (NULL, '" . $title . "', '" . $details . "', '" . $start . "', '" . $end . "') ";
    mysql_query($query) or die("query_fail" . mysql_error());
?>
