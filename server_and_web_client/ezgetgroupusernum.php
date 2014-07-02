<?php


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
                   $usersresult=mysql_query("SELECT * FROM `usergroups` WHERE groupid='" . $groupid . "'"); 
                      $results = array();
                      $count = 0;
                      while($useringroup = mysql_fetch_array($usersresult,MYSQLI_ASSOC)) {
                        $count ++;
                        
                        
                      }
                      echo $count;
                      
                      mysql_close(); 

                    }


                  
?>  