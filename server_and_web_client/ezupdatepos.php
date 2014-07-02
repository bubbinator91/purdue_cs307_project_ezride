<?php
    //session_start();
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.');
    
    $userid = $_POST['userid'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    
    $query = "SELECT * FROM userinfo WHERE userid='" . $userid . "'";
    $result = mysql_query($query) or die('query fail' . mysql_error());
    if(mysql_num_rows($result) == 0){
        echo 'User does not exist';
    }else{
        $query_update = "UPDATE userinfo SET lat='" . $lat .
                        "', lng='" . $lng .
                        "' WHERE userid='" . $userid . "'";

        mysql_query($query_update) or die('update fail' . mysql_error());
        echo 'success';
        }
    
?>