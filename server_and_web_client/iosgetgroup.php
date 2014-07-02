<?php

                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );


                    if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                     $result=mysql_query("SELECT * FROM `iosgroups` WHERE 1"); 
                     //echo "bbb"; 
                     $rownum = mysql_num_rows($result); 
                     //echo $rownum; 
                     // echo "<div class=\"row\">";
                    
                    //format          
                    //<a href="#" class="list-group-item">
                    //  <h4 class="list-group-item-heading">List group item heading</h4>
                    //  <p class="list-group-item-text">...</p>
                    //</a>
                     $results = array();
                     

                     while($row = mysql_fetch_array($result,MYSQLI_ASSOC)) {
                      //echo "aaaa"; 
                      //echo $row['userid'];
                     
                      $usersresult=mysql_query("SELECT * FROM `iosusergroups` WHERE groupid='" . $row['groupid'] . "'"); 
                      
                      

                     $users = array();
                      while($useringroup = mysql_fetch_array($usersresult,MYSQLI_ASSOC)) {

                        $userresult=mysql_query("SELECT * FROM `userinfo` WHERE userid='" . $useringroup['userid'] . "'"); 
                        $groupuser = mysql_fetch_array($userresult,MYSQLI_ASSOC);
                        $users[] = array(
                            'user' => $groupuser['name'],
                            'userid' => $groupuser['userid'],
                            'msg' => $msg['content'],
                            'lat' => $groupuser['lat'],
                            'lng' => $groupuser['lng']
                         );                     
                      }
                    
                      $results[] = array(
                            'groupid' => $row['groupid'],
                            'groupname' => $row['name'],
                            'users' => $users
                         );
                     

                    }
                     $json = json_encode($results);
                      echo $json;

                      mysql_close(); 

                      ?>  