<?php
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.'); 
    $userid = $_POST['userid'];
    date_default_timezone_set('America/Indiana/Indianapolis'); 
    $posttime = date('Y-m-d H:i:s');
    $content = $_POST['content'];
    $groupid = $_POST['groupid'];
    
   
        $query_insert = "INSERT INTO messages (content, posttime, userid, groupid) VALUES ('" . $content . "', '" . $posttime . "','" . $userid . "','" . $groupid . "')" or die('insert fail');
        mysql_query($query_insert) or die('Insert to messages failed');
        echo "success";
        //header('Location: YOUR_DOMAIN_HERE');
   

    mysql_close(); 

?>
