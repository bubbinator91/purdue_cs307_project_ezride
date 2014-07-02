<?php
    $google_id = $_POST['google_id'];
	$message = "";
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail' . mysql_error());
    mysql_select_db('ezride') or die('Select DB ezride fail.');
	
	$query = "SELECT * FROM groups WHERE groupid IN (SELECT groupid FROM usergroups WHERE userid=(SELECT userid FROM userinfo WHERE google_id='" . $google_id . "'))";
	$result = mysql_query($query) or die('query fail:' . mysql_error());
    if(mysql_num_rows($result) == 0){
        echo "no groups";
    } else {
		$message = "numgroups=" . mysql_num_rows($result) . "\n";
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$description = "";
			if ($row["description"] === NULL) {
				$description = "NULL";
			} else {
				$description = $row["description"];
			}
			$message .= "groupid=" . $row["groupid"] . "\n" . "name=" . $row["name"] . "\n" . "description=" . $description . "\n" . "datecreated=" . $row["datecreated"] . "\n";
		}
		
		echo $message;
    }

?>
