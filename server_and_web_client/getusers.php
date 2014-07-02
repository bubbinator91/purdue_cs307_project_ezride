<?php

                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );
                  
                  $userid_google = $_POST['googleid'];

                  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                     $result=mysql_query("SELECT * FROM `userinfo` WHERE 1 "); 
                     //echo "bbb"; 
                     $rownum = mysql_num_rows($result); 
                     //echo $rownum; 
                      echo "<div class=\"row\">";
                               
        

                     while($row = mysql_fetch_array($result,MYSQLI_ASSOC)) {
                      //echo "aaaa"; 
                      //echo $row['userid'];
                      if(isset($row[google_id]) && $row['userid'] != $userid_google){
                      echo "<div class=\"col-sm-6 col-md-3 user-col\"";

                      echo  "\"><div class=\"thumbnail user-thumb\">";
                      echo "<span class=\"glyphicon\" id=\"";
                      echo $row['userid'];
                      echo  "\"></span>";

                      echo  "<div class=\"img-wrap\"><img class=\"user-img\" src=\"";

                      $index = strpos($row['avatarUrl'],"?sz=50");
                      $img_url = substr_replace($row['avatarUrl'],"?sz=180",$index);
                      if(isset($row['avatarUrl'])){echo $img_url;
                        echo "\" style=\"width:100%;\" alt=\"\">";
                      }
                      if(!isset($row['avatarUrl'])){print "holder.js/180x180";

                      echo "\" alt=\"\">";
                      }
                      echo "</div><center><h3 class=\"names\">";

                      echo $row['name'];
                      echo "</h3></center></div></div>";
                      }
                    } 
                      echo "</div>";
                      mysql_close(); 

                      ?>  