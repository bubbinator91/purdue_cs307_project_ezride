<?php
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.'); 
    $groupname = $_GET['groupname'];
    date_default_timezone_set('America/Indiana/Indianapolis'); 
    $date = date('Y-m-d H:i:s');

    $query = "SELECT * FROM iosgroups WHERE name='" . $groupname . "'";
    $result = mysql_query($query) or die('query fail');

    
    if(mysql_num_rows($result) == 0){
        $query_insert = "INSERT INTO iosgroups (name, datecreated) VALUES ('" . $groupname . "', '" . $date . "')" or die('insert fail');
        mysql_query($query_insert) or die('Insert to group failed');
        echo "success";
        //header('Location: YOUR_DOMAIN_HERE');
    }else{
        echo "group name already exists";
    }

     mysql_close(); 
?>
