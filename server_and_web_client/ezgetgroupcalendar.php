<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

	$groupid = $_GET['groupid'];
    $userid = $_GET['userid'];

    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    

    //echo $groupid;
	define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );

    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail' . mysql_error());
    mysql_select_db('ezride') or die('Select DB ezride fail.' . mysql_error());

    //debug
    //echo "groupid is " . $groupid;
    //echo "date is " . $date;
    $queryusergroups = "SELECT userid FROM usergroups WHERE groupid='" . $groupid . "'";
    $result = mysql_query($queryusergroups) or die('queryusergroups fail:' . mysql_error());
 
    //$row = mysql_fetch_array($result);
    if(mysql_num_rows($result) != 0){ 	
    	//echo mysql_num_rows($result);
         echo "var events = [];". "\n";

         while($user = mysql_fetch_array($result,MYSQLI_ASSOC)) {
            //echo $user['userid'];
            if($user['userid'] !=  $userid){
            $query = "SELECT * FROM usercalendar WHERE userid='" . $user['userid'] . "'";
            $events = mysql_query($query) or die('queryusergroups fail:' . mysql_error());
            
            
            while($event = mysql_fetch_array($events,MYSQLI_ASSOC)) {
                        $queryevents = "SELECT * FROM events WHERE id='" . $event['eventid'] . "'";
                        
                       $eventresult = mysql_query($queryevents) or die('queryusergroups fail:' . mysql_error());
                        while($e = mysql_fetch_array($eventresult,MYSQLI_ASSOC)) {
                            $starttime = new Datetime($e['start']);
                            $endtime = new Datetime($e['end']);

                              echo "events.push({";
                              echo "  title: \"";

                              //echo addslashes(htmlentities($e['title']));
                              echo "Unavailable...";
                              echo "\"";
                              echo ",";

                              echo " id:\"";
                              echo $groupid;
                              echo "\"";
                              echo ",";

                              echo " color:\"";
                              echo $color;
                              echo "\"";
                              echo ",";
                              

                              printf("start: new Date(%d, %d, %d, %d, %d),",$starttime->format('Y'), $starttime->format('m')-1, $starttime->format('d'), $starttime->format('H'), $starttime->format('i'));
                              printf("end: new Date(%d, %d, %d, %d, %d),",$endtime->format('Y'), $endtime->format('m')-1, $endtime->format('d'), $endtime->format('H'), $endtime->format('i'));
                              echo "allDay: false";
                            
                              echo "});". "\n";

                        } 

                    //}  
                    
                //}
                }
                
            }
            //echo "timeslots:";
            
           
         }
        
        
        echo "$('#calendar').fullCalendar( 'addEventSource', events);";

    }
    else{
    	echo 'size 0';
    }
    mysql_free_result($result);
?>