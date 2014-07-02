<?php

/*** begin the session ***/
session_start();
      define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
      define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
      define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
      define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
      $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
      mysql_select_db('ezride') or die('Select DB ezride fail.');


if(!isset($_SESSION['user_id']))
{
    $message = 'You must be logged in to access this page';
    
}
else
{
    try
    {
        /*** connect to database ***/
       
        
      $query = "SELECT * FROM userinfo WHERE userid='" . $_SESSION['user_id'] . "'";
      $result = mysql_query($query) or die('ezlogin query fail');
      $row = mysql_fetch_array($result);
      if(mysql_num_rows($result) == 0){
          echo "fail to find user";
      }else{
              $ezuser_username = $row['username'];
              $ezuser_id = $row['usernid'];
      }
      


    }
    catch (Exception $e)
    {
        /*** if we are here, something is wrong in the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }
}

?>


<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.png">

    <title>EZRide!</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
    
    <script src="js/jquery-2.0.3.min.js"></script>
    
    <!-- Custom styles for this template -->
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <div id="fb-root"></div>
       
  <?php include 'header.php'; ?>
    

  <?php if ($user || $ezuser_username || isset($_SESSION['gplusuer'])): ?>
  
    <div class="container main " style="padding-top:53px; height:100%;">
      <!-- Example row of columns -->
     <div class="row">
      <div class="col-md-3">

        <div class="bs-sidebar hidden-print affix-top" role="complementary">
         <button class="btn btn-lg btn-primary btn-block" id="create-group" style="margin-top:30px; margin-bottom:-10px;border-color:rgb(114, 192, 44); background-color:rgb(114, 192, 44);">Create a Group</button>

         <ul class="nav bs-sidenav">

            <li class="side-li"><a href="index.php">All Groups</a></li>
            <li class="side-li"><a class="active" href="mygroups.php">My Groups</a></li>
            <li class="side-li"><a href="gcal.php">Calendar</a></li>
            <li class="side-li"><a href="nearby.php">Nearby</a></li>
            <li class="side-li"><a href="profile.php">Profile</a></li>
            
          </ul>

        </div>
      </div>

       <div class="col-md-9">
        <div class="bs-sidebar hidden-print affix-top" role="complementary">
          <div class="list-group my-list" id="my-group-list">
            
          </div>

          <div class="create-group-form">
              <form role="form" style="margin-top:10px; padding:20px;" action="" method="POST">
            <div class="form-group">
              <input type="hidden" name="userid" value="<?php echo $row['userid'];?>">
              <label for="name">Group Name</label>
              <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
            </div>
            <div class="form-group">
              <label for="description">Choose Users</label>
              <input type="field" class="form-control" name="users" id="users"placeholder="Search Users for this group">
</input>
              <input type="hidden" name="choosedusers" value=""></input>
              <br>
              
              <div id="getusers">

              </div>
            </div>

            <button type="submit" class="btn btn-default btn-lg" id="create-group-btn">Submit</button>
            </form>
          </div>

        </div>

      </div>

     </div>
    
    </div>

  <?php else: 
    echo "Sigin in first";
  ?>
   <!-- /container -->
      
  <?php endif?>
     
  

   <script>
   function createGroup(name){

          jQuery.ajax({
               type: "POST",
               url: "ezcreatgroup.php",
               data: 'groupname='+ name,
               cache: false,
               success: function(response){
                console.log(response);
                addUserGroup(<?php echo $_SESSION['userid_google']?>,name);
                $(".glyphicon-ok-sign").each(function(i){
                    //console.log($(this).attr('id'));
                    addUserGroup($(this).attr('id'),name);
                    //$(this).parent().show("fast");

                });
                fetchGroups(<?php echo $_SESSION['userid_google']?>);
                $("#my-group-list").slideToggle( "fast" );
                $(".create-group-form").slideToggle( "fast" );
            }
            });
        }

        function addUserGroup(id , name){

          jQuery.ajax({
               type: "POST",
               url: "ezaddgroupuser.php",
               data: { users: id, groupname: name},
               cache: false,
               success: function(response){
               console.log(response);
            }
            });
        }

        function fetchGroups(id){

          jQuery.ajax({
               type: "POST",
               url: "ezgetusergroups.php",
               data: { googleid: id},
               cache: false,
               success: function(response){
               //console.log(response);
               document.getElementById("my-group-list").innerHTML = response;
                 
                $(".list-group-item").click(function(event){
                    window.location.assign($(this).attr("href"));
                });

            }
            });
        }

     $(document).ready(function() {
   
      $("#mygroup").addClass("active");
      $( "#home-li" ).removeClass("active");
      fetchGroups(<?php echo $_SESSION['userid_google']?>);
                
        $('#create-group-btn').click(function(event){
            console.log("pressed!");
            event.preventDefault();
            var group_name = $("#name").val();
            if(group_name == "")return false;
            else{
                createGroup(group_name);
               
            }
        });

        $(".create-group-form").toggle();
        $("#create-group").click(function(){
            //document.getElementById("my-group-list").innerHTML = "";
            $("#my-group-list").slideToggle( "fast" );
            $(".create-group-form").slideToggle( "fast" );
            getusernames();
            fetchGroups(<?php echo $_SESSION['userid_google']?>);
                
        });
        $(".user-thumb").click(function(){
            if(!$( this).children("span").first().hasClass("glyphicon-ok-sign"))$( this ).children("span").first().addClass("glyphicon-ok-sign");
            else $( this ).children("span").first().removeClass("glyphicon-ok-sign");


        });
        var newinput;
        var lastinput;
        setInterval(function(){
                var searchname = $('#users').val();
                newinput = searchname;
                if(searchname != ""){
                  var searchLower = searchname.toLowerCase();
                  $(".user-thumb").each(function(i){
                    console.log($(this).text());
                    var username = $(this).text();
                    var usernameLower = username.toLowerCase();
                  if(usernameLower.indexOf(searchLower) == -1)$(this).parent().hide("fast");
                  else $(this).parent().show("fast");
                  
                  

                  });
                }else{
                  $(".user-thumb").each(function(i){
                    //console.log($(this).text());

                    $(this).parent().show("fast");
                    //getusernames();
               

                  });

                }
              
                lastinput = newinput;
            }, 500);


        

        
      });
     function getusernames(){
     
            jQuery.ajax({
               type: "POST",
               url: "getusers.php",
               data: { googleid: <?php echo $_SESSION['userid_google']?>},
               cache: false,
               success: function(response){
                //console.log(response);
                if(response != ""){
                  document.getElementById("getusers").innerHTML = response;
                  $(".user-thumb").click(function(){
                    if(!$( this).children("span").first().hasClass("glyphicon-ok-sign"))$( this ).children("span").first().addClass("glyphicon-ok-sign");
                    else $( this ).children("span").first().removeClass("glyphicon-ok-sign");
                    });
                }else{
                 
                }
             
            }
            });
          
        }

        
  </script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     
    <script src="js/bootstrap.min.js"></script>
    <script src="holder.js"></script>
  </body>
  <?php if ($user || $ezuser_username || isset($_SESSION['gplusuer'])): ?>
    <?php else: ?>
      <link href="home.css" rel="stylesheet">
    <?php endif?>
</html>

