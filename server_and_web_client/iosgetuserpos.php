<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );
                  
                  if(isset( $_GET["groupid"])){
                  $groupid = $_GET["groupid"];
                  //echo $groupid;
                  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                   $usersresult=mysql_query("SELECT * FROM `iosusergroups` WHERE groupid='" . $groupid . "'"); 
                      $results = array();
                      while($useringroup = mysql_fetch_array($usersresult,MYSQLI_ASSOC)) {
                        $userresult=mysql_query("SELECT * FROM `userinfo` WHERE userid='" . $useringroup['userid'] . "'"); 
                        $groupuser = mysql_fetch_array($userresult,MYSQLI_ASSOC);
                        $lastmsg = mysql_query("SELECT * FROM `messages` WHERE groupid='" . $groupid . "' AND userid='" . $groupuser['userid'] . "'". " ORDER BY `messages` . `posttime` DESC LIMIT 0 , 1 "); 
                        $msg = mysql_fetch_array($lastmsg,MYSQLI_ASSOC);
                        //echo $groupuser['name'];
                        //echo " ";
                        //echo $groupuser['lat'];
                        //echo " ";
                        //echo $groupuser['lng'];
                        //echo " ";
                         $results[] = array(
                            'user' => $groupuser['name'],
                            'userid' => $groupuser['userid'],
                            'msg' => $msg['content'],
                            'lat' => $groupuser['lat'],
                            'lng' => $groupuser['lng']
                         );
                      }
                      $json = json_encode($results);
                      echo $json;
                      mysql_close(); 

                    }


                  
?>  