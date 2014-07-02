<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

	$groupid = $_POST['groupid'];
    $date = strtotime($_POST['date']);
    $timeslots = Array();
    $minutes_to_add = 10;
    $time = new DateTime(date('Y-m-d',$date)." 00:00:00");
    for($i = 0; $i < 144; $i++ ){
    //$stamp = $time->format('Y-m-d H:i:s');
    //echo $stamp . "\n";
    //$timeslots[$stamp] = 1;
    //$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $timeslots[$i] = 1;
    }

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

         while($user = mysql_fetch_array($result,MYSQLI_ASSOC)) {
            //echo $user['userid'];
            $query = "SELECT * FROM usercalendar WHERE userid='" . $user['userid'] . "'";
            $events = mysql_query($query) or die('queryusergroups fail:' . mysql_error());
            
            //echo "loop user". $user['userid']."\n";

            while($event = mysql_fetch_array($events,MYSQLI_ASSOC)) {
                //echo "loop event\n";
                reset($timeslots);
                //while(list($key, $value) = each($timeslots)){

                //    if($value == 1){
                        //$end = new Datetime($key);
                        //$end->add(new DateInterval('PT' . $minutes_to_add . 'M'));
                        
                        //$queryevents = "SELECT * FROM events WHERE id='" . $event['eventid'] . "' AND ( start >'". $end->format('Y-m-d H:i:s')." ' OR end < '".$key."')";
                        $queryevents = "SELECT * FROM events WHERE id='" . $event['eventid'] . "'" . "AND DATE(start)='".date('Y-m-d',$date)."'";
                        
                        //echo "aaa\n";
                        //echo $queryevents."\n";
                        
                       // $numofevents = "SELECT COUNT(*) as total FROM 'events'";
                       // $num = mysql_fetch_assoc($numofevents);
                       // echo $num."\n";

                        $eventresult = mysql_query($queryevents) or die('queryusergroups fail:' . mysql_error());
                        //if(mysql_num_rows($eventresult) == 0){
                        //    $timeslots[$key] = 0;
                        //}

                        while($e = mysql_fetch_array($eventresult,MYSQLI_ASSOC)) {
                            //echo $e['start']."\n";
                            $starttime = new Datetime($e['start']);
                            $endtime = new Datetime($e['end']);
                            //echo "start:".($starttime->format('H')*6+$starttime->format('i')/10)."\n";
                            //echo "end:".($endtime->format('H')*6+$endtime->format('i')/10)."\n";
                            
                            for($i = $starttime->format('H')*6+$starttime->format('i')/10; $i<=$endtime->format('H')*6+$endtime->format('i')/10;$i++){
                                $timeslots[$i] = 0;
                            }
                            

                        } 

                    //}  
                    
                //}
                
                
            }
            //echo "timeslots:";
            
           
         }
         $newTimeSlots = Array();
         $index = 0; //index of avalable slots (larger than 30 min) 
         $countAvb = 0;
         for($j = 0; $j < 144; $j++){
             if( $timeslots[$j] == 1){
               $countAvb = $countAvb +1;
               //echo "countavb= " . $countAvb;
            }else{ 
                if ($countAvb >= 3){
                    //echo "add value to newtimeslots";
                    $newTimeSlots[$index]=$j-$countAvb;
                    $index = $index + 1; 
                    $newTimeSlots[$index]=$j-1;
                    $index = $index + 1; 
                }
                $countAvb = 0;
            }
         }
            // count the last hours.  convert to time
         if($countAvb>=3){
            $newTimeSlots[$index]=144-$countAvb;
            $index = $index + 1; 
            $newTimeSlots[$index]=143;
            $index = $index + 1; 
         }
         $mytime = new DateTime(date('Y-m-d',$date));
         //$mytime = date_create($date->format('Y-m-d'));
         $min = 10;
         $startT;
         $results = array();

         foreach ($newTimeSlots as $indexN => $timeN) {
            //echo "timeN=" . $timeN;
            $mytime->add(new DateInterval('PT' . $min*$timeN . 'M'));
            $stamptime = $mytime->format('Y-m-d H:i:s');
            $mytime->sub(new DateInterval('PT' . $min*$timeN . 'M'));
            if($indexN % 2 == 0){
                $startT = $stamptime;
            }else{
                $results[] = array(
                    'start' => $startT,
                    'end' => $stamptime
                );
                //echo 'startT=' . $startT;
                //echo 'stampime=' . $stamptime;
                    
            }
         }
         $json = json_encode($results);
         echo $json;
         /*
            $mytime->add(new DateInterval('PT' . $min*$timeN . 'M'));
            $stamp = $mytime->format('Y-m-d H:i:s');
            $mytime->add(new DateInterval('PT' . -$min*$timeN . 'M'));
            echo $stamp;
            if($index%2 == 0 ) {
                echo "~";
            }else{
                echo "\n\r";
            }
          */  
        /*
         $mytime = new DateTime(date('Y-m-d',$date)." 00:00:00");
         $min = 10;
         foreach ($timeslots as $key => $value) {   
            $stamp = $mytime->format('Y-m-d H:i:s');
            echo $stamp . "\n";
            $mytime->add(new DateInterval('PT' . $min . 'M'));
            //echo "$value"."\n";
            if($value == 1)echo "available\r\n";
            if($value == 0)echo "not available\r\n";
         }
        */
    }
    else{
    	echo 'size 0';
    }
    mysql_free_result($result);
?>
