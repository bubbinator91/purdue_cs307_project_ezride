<?php
	$message = "success";
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die($message = "connect to sql fail");
    mysql_select_db('ezride') or die($message = "Select DB ezride fail");
    
    $google_id = $_POST['google_id'];
	$groupid = $_POST['groupid'];
	$user_id = $_POST['userid'];
    
    $query = "SELECT * FROM userinfo WHERE google_id='" . $google_id . "'";
    $result = mysql_query($query) or die("query fail");
    if(mysql_num_rows($result) == 0){
        $message = "Invalid username or password";
    } else {
        $query_update = "DELETE FROM usergroups WHERE userid='" . $user_id . "' AND groupid='" . $groupid . "'";
        $result = mysql_query($query_update) or die($message = "update fail" . mysql_error());
		if ($result) {
			$message = "success";
		} else {
			$message = "fail";
		}
    }
echo $message;
?>