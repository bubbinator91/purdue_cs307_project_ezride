<?php

/*** begin the session ***/
session_start();
define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );

if(!isset($_GET['userid']))
{
    $message = 'You must be logged in to access this page';
    
}
else
{
    try
    {
        /*** connect to database ***/
       
      $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
      mysql_select_db('ezride') or die('Select DB ezride fail.');  
      $query = "SELECT * FROM userinfo WHERE userid='" . $_GET['userid'] . "'";
      $result = mysql_query($query) or die('ezlogin query fail');
      $row = mysql_fetch_array($result);
      if(mysql_num_rows($result) == 0){
          echo "fail to find user";
      }else{
              $ezuser_username = $row['username'];
              $ezuser_userid = $row['userid'];
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

  padding-top: 23px;
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
       
  <?php// include 'header.php'; ?>
  
  <?php if ($ezuser_username): ?>
  
    <div class="container main " style="padding-top:23px; height:100%;">
      <!-- Example row of columns -->
    

       <div class="">
        <div class="bs-sidebar hidden-print affix-top" role="complementary">
          <form role="form" style="margin-top:10px; padding:20px;" action="./iosupdateprofile.php" method="POST">
            <div class="form-group">
              <input type="hidden" name="userid" value="<?php echo $row['userid'];?>">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" id="name" <?php if($row['name']):?>placeholder="<?php echo $row['name'];?>" value="<?php echo $row['name'];?>"<?php else:?>placeholder="Enter Name"<?php endif?>>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email" id="email" <?php if($row['email']):?>placeholder="<?php echo $row['email'];?>" value="<?php echo $row['email'];?>"<?php else:?>placeholder="Enter Email"<?php endif?>>
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" class="form-control" name="address" id="address" <?php if($row['address']):?>placeholder="<?php echo $row['address'];?>" value="<?php echo $row['address'];?>"<?php else:?>placeholder="Enter Address"<?php endif?>>
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" name="phonenumber" id="phone" <?php if($row['phonenumber']):?>placeholder="<?php echo $row['phonenumber'];?>" value="<?php echo $row['phonenumber'];?>"<?php else:?>placeholder="Enter Phone"<?php endif?>>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea type="field" class="form-control" name="profile" id="description" <?php if($row['profile']):?>placeholder="<?php echo $row['profile'];?>" value="<?php echo $row['profile'];?>"<?php else:?>placeholder="Enter Description"<?php endif?><?php echo $row['profile'];?>></textarea>
            </div>

            <button type="submit" class="btn btn-default" style="width:100%">Save</button>
            </form>
            <br><br>
        </div>

      </div>

     </div>
    
    </div>

   <!-- /container -->
      
  <?php endif?>
     
  

  


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     
    <script src="js/bootstrap.min.js"></script>
  </body>
  <script type="text/javascript">
   <?php if(!isset($_SESSION['update'])):?>

    <?php else:?>
      alert("Update Success");
    <?php unset($_SESSION['update']);?>
    <?php endif?>
  </script>
  <?php if ($ezuser_username): ?>
    <?php else: ?>
      <link href="home.css" rel="stylesheet">
    <?php endif?>
</html>

