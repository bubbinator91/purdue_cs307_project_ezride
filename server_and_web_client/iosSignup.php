<?php 
   define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
 
   define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
   
   define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
   
   define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
   

 
  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){
    if(mysql_select_db(DB_DATABASE)){
     
    } 
  }else{
    die(mysql_error());
  }

  

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
  
    <title>Signup with EZRide!</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="js/jquery-2.0.3.min.js"></script>
    <style type="text/css">
    body {
    padding-top: -60px;
  }

.my-nav{
  background-color: rgb(255, 255, 255);
}

.my-nav .navbar-nav > .active > a, .my-nav .navbar-nav > .active > a:hover {
     color: rgb(114, 192, 44);
    background-color: rgb(255, 255, 255);
    border-bottom: 2px solid rgb(114, 192, 44);
}

.form-signup {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signup .form-signup-heading,
.form-signup .checkbox {
  margin-bottom: 10px;
}
.form-signup .checkbox {
  font-weight: normal;
}
.form-signup .form-control {
  position: relative;
  font-size: 16px;
  height: auto;
  padding: 10px;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
.form-signup .form-control:focus {
  z-index: 2;
}

    .warnField{
       border-color: rgb(200, 100, 100);
        outline: 0px none;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(200, 100, 100, 0.6);
    }
    .focusField{
       border-color: rgb(102, 175, 233);
        outline: 0px none;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(102, 175, 233, 0.6);
    }
    .idleField{
       border: 1px solid rgb(204, 204, 204);
    border-radius: 4px 4px 4px 4px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
    }

    </style>
    
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

<body>
    <?php //include 'header.php'; ?>
    

    <div class="container" style="padding-top:-35px; height:100%;">

      <form class="form-signup" action="./iosRegister.php" method="POST" id="signup-form">
        <center><h2 class="form-signin-heading">Sign Up</h2></center>
        <br>
        <div id="check-user" style="display:inline; color:#bbb"></div>
        <input type="text" class="form-control" id="user-name" name="username" placeholder="User name" maxlength="16" autofocus>
        <br>
        <input type="password" class="form-control" id="pwd" name="password" placeholder="Password" maxlength="32">
        <br>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" maxlength="32">
        <br>
        <textarea type="field" class="form-control" id="des" name="profile" placeholder="Describe yourself here..."></textarea>
        <br>
        <button class="btn btn-lg btn-primary btn-block" id="submit" type="submit" >Sign up</button>
      </form>

    </div> <!-- /container -->

    <script>
      $(document).ready(function() {
        

        $('#signup-form').submit(function(event){

          //event.preventDefault();
          var flag = 0;
            if($("#user-name").val()==''){
             $("#user-name").removeClass("idleField").removeClass("focusField").addClass("warnField");
              flag = 1;
            }
            if($("#pwd").val()==''){
              $("#pwd").removeClass("idleField").removeClass("focusField").addClass("warnField");
              //event.preventDefault();
              flag = 1;
            }
             if($("#email").val()==''){
              $("#email").removeClass("idleField").removeClass("focusField").addClass("warnField");
              flag = 1;
             // event.preventDefault();
            }
            if(flag == 1){return false;}
            if($("#user-name").val()!=''&& $("#pwd").val()!='' && $("#pwd-confirm").val()!='' && $("#email").val()!=''){
              
              $("#signup-from").submit();

            }

        });
        $('.form-control').focus(function() {
             $(this).removeClass("idleField").addClass("focusField");
        });

        $('.form-control').blur(function() {
           $(this).removeClass("focusField").addClass("idleField");
        });

        setInterval(function(){

                username_check();
                //console.log('test');
            }, 1000);

        function username_check(){
     
          var username = $('#user-name').val();
          //console.log(username);
          if($('#user-name').val()!=''){
            jQuery.ajax({
               type: "POST",
               url: "check.php",
               data: 'username='+ username,
               cache: false,
               success: function(response){
                //console.log(response);
                if(response != 0){
                  $("#user-name").removeClass("idleField").removeClass("focusField").addClass("warnField");
                  document.getElementById("check-user").innerHTML = "User name exists";
                }else{
                   $("#user-name").removeClass("warnField");
                    document.getElementById("check-user").innerHTML = "";
                }
             
            }
            });
          }
          }

      });
      
    </script>
    <script type="text/javascript">
   <?php if(!isset($_GET['signup'])):?>

    <?php else:?>
      alert("Signup Success");
    <?php endif?>
    </script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  <?php include 'footer.php'; ?>
