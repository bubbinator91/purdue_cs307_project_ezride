<?php

/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
    $message = 'You must be logged in to access this page';
    
}
else
{
    try
    {
        /*** connect to database ***/
       
      define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
      define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
      define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
      define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
      $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
      mysql_select_db('ezride') or die('Select DB ezride fail.');  
      $query = "SELECT * FROM userinfo WHERE userid='" . $_SESSION['user_id'] . "'";
      $result = mysql_query($query) or die('ezlogin query fail' . mysql_error());
      $row = mysql_fetch_array($result);
      if(mysql_num_rows($result) == 0){
          echo "fail to find user";
      }else{
              $ezuser_username = $row['username'];
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
    <link href="home.css" rel="stylesheet">
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
         <!-- <button class="btn btn-lg btn-primary btn-block" id="create-group" style="margin-top:30px; margin-bottom:-10px;border-color:rgb(114, 192, 44); background-color:rgb(114, 192, 44);">Create a Group</button>
         -->

         <ul class="nav bs-sidenav">
            <li class="side-li"><a class="active" href="#">All Groups</a></li>
            <li class="side-li"><a href="mygroups.php">My Groups</a></li>
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
            <div class="form-group">
              <label for="description">Description</label>
              <textarea type="field" class="form-control" name="profile" id="description"placeholder="Enter Description"></textarea>
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
            </form>
          </div>

      </div>

     </div>
    
    </div>

  <?php else: ?>
  <div class="jumbotron my-jum">
      <div class="container info">
        <h1>Welcome To EZRide!</h1>
        <p>This is a social based carpooling app. You have a few sign in options. You can signin with Google+, Facebook or an EZRide account. You can always create an EZRide only aacount if you are not into the whole social networking thing.</p>

        <div class="social">
                <div href="#" class="facebook">
                <i class="entypo-facebook"></i><span>facebook</span></div>
                          
                <div href="#" id="customBtn" class="my-gplus">
                    <i class="entypo-gplus"></i><span>google+</span></div>
            </div>  
            <hr>
            <center><span id="signup-text">Or you can</span></center>
            <center><a class="btn btn-lg btn-outline-inverse" href="signup.php"><span id="my-signup-btn">Sign up today</span></a></center>


        </div>
    </div>

  <div class="container main ">
      <!-- Example row of columns -->
      <div class="row">
         <div class="">
            <div class="project">
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <hr>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          
            </div>
         </div>

        <div class="col-lg-5">
          <div class="about-me">
            
          </div>
          
         
        </div>

       
      </div>
      <br>
      <br>
       <footer>
        <p>&copy; EZRide 2013</p>
      </footer>
    
    </div> <!-- /container -->
    <?php endif?>
     
  

  


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>
      $(document).ready(function() {

       
        $(".facebook").click(function(){
            
           window.location.replace("<?php echo $loginUrl; ?>");

        });
        $(".my-gplus").click(function(){
            
           window.location.replace("<?php echo $authUrl; ?>");
        });

      });

    </script>
     <script>
     $(document).ready(function() {
        fetchGroups(<?php echo $_SESSION['userid_google']?>);
        $(".create-group-form").toggle();
        $("#create-group").click(function(){
            //document.getElementById("my-group-list").innerHTML = "";
            $("#my-group-list").slideToggle( "fast" );
            $(".create-group-form").slideToggle( "fast" );
            getusernames();
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
               type: "GET",
               url: "getusers.php",
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

      function fetchGroups(id){

          jQuery.ajax({
               type: "POST",
               url: "ezgetgroups.php",
               data: { googleid: id},
               cache: false,
               success: function(response){
               //console.log(response);
               document.getElementById("my-group-list").innerHTML = response;
                $(".list-group-item").click(function(){
                var close = confirm("Do you want to join this group?");
                if ( close) {
                  
                  }
                else
                  {
                    event.preventDefault();
                  }
                });
                  
            }
            });
        }
  </script>
    <script src="js/bootstrap.min.js"></script>
    <?php if ($user || $ezuser_username || isset($me)): ?>
      <script>
      $('link[rel=stylesheet][href~="home.css"]').remove();
      </script>
    <?php else: ?>

    
    <?php endif?>
  </body>
  
</html>
