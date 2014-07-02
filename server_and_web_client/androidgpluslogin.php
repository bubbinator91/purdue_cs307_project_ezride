<?php
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.');
    $google_id = $_POST['google_id'];
	$name = $_POST['name'];
	$avatarURL = $_POST['avatarURL'];
	$email = $_POST['email'];
	$profile = $_POST['profile'];
    $query = "SELECT * FROM userinfo WHERE google_id='" . $google_id . "'";
    $result = mysql_query($query) or die('ezlogin query fail');
    $row = mysql_fetch_array($result);
    if(mysql_num_rows($result) == 0)
	{
        $query_insert = "INSERT INTO userinfo (google_id, name, email, profile, avatarUrl) VALUES ('" . $google_id . "','" . $name . "','" . $email . "','" . $profile . "','". $avatarURL . "')" or die('insert fail');
        mysql_query($query_insert) or die('Insert to userinfo failed');
		echo 'added';
		$query = "SELECT * FROM userinfo WHERE google_id='" . $google_id . "'";
		$result = mysql_query($query) or die('failed to find user for some stupid reason');
		$row = mysql_fetch_array($result);
		if(mysql_num_rows($result) == 0)
		{
			echo "failed to find user for some stupid reason";
		}
		else
		{
			echo "userid:" . $row['userid'] . "\n" .
				"google_id:" . $row['google_id'] . "\n" .
				"name:" . $row['name'] . "\n" .
				"email:" . $row['email'] . "\n" .
				"phonenumber:" . $row['phonenumber'] . "\n" .
				"address:" . $row['address'] . "\n" .
				"profile:" . $row['profile'] . "\n" .
				"avatarUrl:" . $row['avatarUrl'] . "\n";
		}
    }
	else
	{
		$query_update = "UPDATE userinfo SET avatarUrl='" . $avatarURL . "', name='" . $name . "', email='" . $email . "', profile='" . $profile ."' WHERE google_id='" . $google_id . "'";
		mysql_query($query_update) or die('update fail');
		$query = "SELECT * FROM userinfo WHERE google_id='" . $google_id . "'";
		$result = mysql_query($query) or die('failed to find user for some stupid reason');
		$row = mysql_fetch_array($result);
		if(mysql_num_rows($result) == 0)
		{
			echo "failed to find user for some stupid reason";
		}
		else
		{
			echo "userid:" . $row['userid'] . "\n" .
				"google_id:" . $row['google_id'] . "\n" .
				"name:" . $row['name'] . "\n" .
				"email:" . $row['email'] . "\n" .
				"phonenumber:" . $row['phonenumber'] . "\n" .
				"address:" . $row['address'] . "\n" .
				"profile:" . $row['profile'] . "\n" .
				"avatarUrl:" . $row['avatarUrl'] . "\n";
		}
    }
	

?>

