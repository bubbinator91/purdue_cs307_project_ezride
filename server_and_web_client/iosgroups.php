<?php

/*** begin the session ***/
session_start();
      define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
      define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
      define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
      define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
      $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
      mysql_select_db('ezride') or die('Select DB ezride fail.');


if(!isset($_GET['userid']))
{
    $message = 'You must be logged in to access this page';
    
}
else
{
    try
    {
        /*** connect to database ***/
       
        
      $query = "SELECT * FROM userinfo WHERE userid='" . $_GET['userid'] . "'";
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
    <style type="text/css">
.my-nav{
  background-color: rgb(255, 255, 255);
}

.my-nav .navbar-nav > .active > a, .my-nav .navbar-nav > .active > a:hover {
     color: rgb(114, 192, 44);
    background-color: rgb(255, 255, 255);
    border-bottom: 2px solid rgb(114, 192, 44);
}

html, body {
    height:100%;
}

.my-nav-right{
    margin-top: -4px;
    height: 50px;
}
.list-group-item:hover{
  background-color: #f6f6f6;
  cursor: pointer;
}

.cell{
  width:100%;
  height:100px;
  padding:10px;
  padding-top: 30px;
  margin-left:0px;
  border-bottom:1px solid rgb(231, 231, 231);
}

  .bs-sidenav {
    margin-top: 30px;
    margin-bottom: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    text-shadow: 0px 1px 0px rgb(255, 255, 255);
    background-color: rgb(247, 245, 250);
    border-radius: 5px 5px 5px 5px;
    }
    .bs-sidebar .nav > li > a {
    display: block;
    color: rgb(113, 107, 122);
    padding: 5px 20px;
    }
    .my-list{
      margin-top: 30px;
    }

  .active{font-weight:bold;}
    .nav > li > a:hover, .nav > li > a:focus {
      text-decoration: none;
      background-color: #fff;
    }

 #my-alert-wrapper{

  padding-top: 53px;
  margin-bottom: -60px;
 }

 .close-x{
  cursor: pointer;

 }

 .user-col{
  margin-bottom:20px;
  
 }
.user-thumb{
  color:#333;
}
.user-thumb > img{
  margin-left:0px;
  width:100%;

}
.user-img:hover{
   opacity:0.7;
   transition: opacity 0.1s linear 0s;
}
 .user-thumb:hover{
  cursor: pointer;
  
 }
.user-thumb > span{
  position:absolute;
  top: 0px;
  left:-5px;
}
 .names{
  color:#555;
  margin-top: 10px;
 }
.img-wrap{
  background-color: #fff;
}
.img-wrap:hover{
  background-color: #000;
}

    </style>
    
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
       
 
    

  <?php if ($ezuser_username): ?>
  
    <div class="container main " style="padding-top:-60px; height:100%;">
      <!-- Example row of columns -->

       <div class="">
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

            <button type="Create a Group" class="btn btn-default btn-lg" id="create-group-btn" style="width:100%">Submit</button>
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
               type: "GET",
               url: "ioscreategroup.php",
               data: 'groupname='+ name,
               cache: false,
               success: function(response){
                console.log(response);
                addUserGroup(<?php echo $_GET['userid']?>,name);
                $(".glyphicon-ok-sign").each(function(i){
                    //console.log($(this).attr('id'));
                    addUserGroup($(this).attr('id'),name);
                    //$(this).parent().show("fast");

                });
                alert("Success!");
            }
            });
        }

        function addUserGroup(id , name){

          jQuery.ajax({
               type: "GET",
               url: "iosaddusergroup.php",
               data: { users: id, groupname: name},
               cache: false,
               success: function(response){
               console.log(response);
            }
            });
        }


     $(document).ready(function() {
   
      $("#mygroup").addClass("active");
      $( "#home-li" ).removeClass("active");          
      $('#create-group-btn').click(function(event){
            console.log("pressed!");
            event.preventDefault();
            var group_name = $("#name").val();
            if(group_name == "")return false;
            else{
                createGroup(group_name);
               
            }
        });

        getusernames();
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
               url: "iosgetusers.php",
               data: { userid: <?php echo $_GET['userid']?>},
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

