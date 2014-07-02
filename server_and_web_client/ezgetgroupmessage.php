<?php


                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );
                  
                  $groupid = $_GET["groupid"];
                  //echo $groupid;
                  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                    $result=mysql_query("SELECT * FROM `messages` WHERE groupid='" . $groupid . "' ORDER BY posttime"); 
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
                      //<div class="row"><div class="mes">System: Welcome!</div></div>
                      echo "<div class=\"row\">";
                      $userresult=mysql_query("SELECT * FROM `userinfo` WHERE userid='" . $row['userid'] . "'"); 
                      $groupuser = mysql_fetch_array($userresult,MYSQLI_ASSOC);
                      echo  "<img class=\"user-img img-rounded\" src=\"";

                      $index = strpos($groupuser['avatarUrl'],"?sz=50");
                      $img_url = substr_replace($groupuser['avatarUrl'],"?sz=30",$index);
                      if(isset($groupuser['avatarUrl'])){echo $img_url;
                        echo "\" style=\"margin-left:20px;\" alt=\"\">";
                      }

                      echo "<div class=\"mes\">";
                      //echo $groupuser['name'];
                      //echo "<br>";
                      echo $row['content'];
                      echo "</div></div>";
                      
                    
                    } 
                      mysql_close(); 

                      ?>  
