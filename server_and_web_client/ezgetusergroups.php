<?php

                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );
                  
                  $userid_google = $_POST['googleid'];

                  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                     $result=mysql_query("SELECT * FROM `usergroups` WHERE userid='" . $userid_google . "'"); 
                     //echo "bbb"; 
                     $rownum = mysql_num_rows($result); 
                     //echo $rownum; 
                     // echo "<div class=\"row\">";
                    
                    //format          
                    //<a href="#" class="list-group-item">
                    //  <h4 class="list-group-item-heading">List group item heading</h4>
                    //  <p class="list-group-item-text">...</p>
                    //</a>

                     while($row = mysql_fetch_array($result,MYSQLI_ASSOC)) {
                      //echo "aaaa"; 
                      //echo $row['userid'];
                      $res = mysql_query("SELECT * FROM `groups` WHERE groupid='" . $row['groupid'] . "'"); 
                      $buf = mysql_fetch_array($res,MYSQLI_ASSOC);
                      echo "<li href=\"";
                      echo "map.php?groupid=";
                      echo $buf['groupid'];
                      echo "\" class=\"list-group-item\">";

                      echo "<a href=\"";
                      echo "deletegroup.php?groupid=";
                      echo $buf['groupid'];
                      echo "\" class=\"pull-right close-x\">×</a>";
                      echo "<h4 class=\"list-group-item-heading\">";
                      echo $buf['name'];
                      echo " ( ";
                      $usersresult=mysql_query("SELECT * FROM `usergroups` WHERE groupid='" . $row['groupid'] . "'"); 
                      
                      $counter_output = 0;
                      while($useringroup = mysql_fetch_array($usersresult,MYSQLI_ASSOC)) {
                        $counter_output = $counter_output + 1;
                        $userresult=mysql_query("SELECT * FROM `userinfo` WHERE userid='" . $useringroup['userid'] . "'"); 
                        $groupuser = mysql_fetch_array($userresult,MYSQLI_ASSOC);
                        echo $groupuser['name'];
                        if($counter_output != mysql_num_rows($usersresult)){
                          echo " |  ";
                        }
                        else{
                          echo "  ";
                        }
                        
                      }
                      $counter_output = 0;
                      echo " )";
                      echo "</h4>";
                      

                      echo "<p class=\"list-group-item-text\" style=\"color:#888\">";


                      echo $buf['datecreated'];
                      echo "</p></li>";
                      
                    } 
                      mysql_close(); 

                      ?>  