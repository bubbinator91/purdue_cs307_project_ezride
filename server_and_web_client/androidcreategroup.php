
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
    $result = mysql_query($query) or die("failed to find user");
    $row = mysql_fetch_array($result);
    if (mysql_num_rows($result) == 0)
	{
        $message = "failed to find user";
    }
	else
	{
		$groupname = $_POST['groupname'];
		$description = $_POST['description'];
		$query = "SELECT * FROM groups WHERE name='" . $groupname . "'";
		$result = mysql_query($query) or die("failed to find group");
		if (mysql_num_rows($result) == 0)
		{
			$query = "INSERT INTO groups (name, description, datecreated) VALUES ('" . $groupname . "', '" . $description . "', NOW())";
			$result = mysql_query($query) or die("failed to insert");
			if ($result)
			{
				$query = "SELECT userid FROM userinfo WHERE google_id='" . $google_id . "'";
				$result = mysql_query($query) or die("failed to find user2");
				if (mysql_num_rows($result) == 1)
				{
					$row = mysql_fetch_array($result);
					$userid = $row['userid'];
					$query = "SELECT groupid FROM groups WHERE name='" . $groupname . "'";
					$result = mysql_query($query) or die("failed to find group");
					if (mysql_num_rows($result) == 1)
					{
						$row = mysql_fetch_array($result);
						$groupid = $row['groupid'];
						$query = "INSERT INTO usergroups (userid, groupid) VALUES ('" . $userid . "', '" . $groupid . "')";
						$result = mysql_query($query) or die("failed to insert into usergroups");
						if (!$result)
						{
							$message = "failed to insert into usergroups";
						}	
					}
					else
					{
						$message = "failed to find group";
					}
				}
				else
				{
					$message = "failed to find user2";
				}
			}
			else
			{
				$message = "failed to insert";
			}
		}
		else
		{
			$message = "group already exists";
		}
    }

echo $message;
?>
