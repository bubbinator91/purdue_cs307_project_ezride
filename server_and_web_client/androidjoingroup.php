
<?php
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.');
    $google_id = $_POST['google_id'];
	$message = "success";
    $query = "SELECT * FROM userinfo WHERE google_id='" . $google_id . "'";
    $result = mysql_query($query) or die($message = "failed to find user");
    $row = mysql_fetch_array($result);
    if (mysql_num_rows($result) == 0)
	{
        $message = "failed to find user";
    }
	else
	{
		$userid = $row['userid'];
		$groupname = $_POST['groupname'];
		$query = "SELECT * FROM groups WHERE name='" . $groupname . "'";
		$result = mysql_query($query) or die($message = "failed to find group");
		if (strcmp($message, "failed to find group") != 0)
		{
			if (mysql_num_rows($result) == 0)
			{
				$message = "failed to find group";
			}
			else
			{
				$row = mysql_fetch_array($result);
				$groupid = $row['groupid'];
				//$message = "groupname=" . $groupname . "\nuserid=" . $userid . "\ngroupid=" . $groupid . "\n";
				$query = "INSERT INTO usergroups (userid, groupid) VALUES ('" . $userid . "', '" . $groupid . "')";
				$result = mysql_query($query) or die($message = "failed to insert into usergroups");
			}
		}
    }

echo $message;
?>
