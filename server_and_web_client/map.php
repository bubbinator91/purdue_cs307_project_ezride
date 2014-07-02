<?php
  if( !$_GET["groupid"])
  {
     echo "Choose a group first";
     exit();
  }
?>

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
      $result = mysql_query($query) or die('ezlogin query fail');
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
<html>
  <head>
    <title>Asynchronous Loading</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.png">

    <title>EZRide!</title>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
     <!--[if lte IE 8]>
         <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
     <![endif]-->
      <script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
    
    <script src="js/jquery-2.0.3.min.js"></script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body{
        height: 100%;
        margin: 0px;
        padding: 0px;

      }
      .message{
            margin-top: 5px;
            border:1px solid rgb(204, 204, 204);
            border-bottom-width: 0px;
            border-radius: 4px 4px 0px 0px;
            width:100%;
            height:250px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        #input-mes{
            border-top-left-radius: 0px;
             border-top-right-radius: 0px;
        }
        #send-btn{
            border-top-right-radius: 0px;
            border-left-color: rgb(255, 255, 255);
        }
        .mes{
            margin:10px;
            padding:5px;
            border-radius: 4px;
            border:1px solid rgb(204, 204, 204);
            display: inline-block;
            max-width: 40%;
        }
       #map{
        margin-top: 30px;
        height:500px;
       }
    </style>
    
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
             
             <ul class="nav bs-sidenav">
                <li class="side-li"><a href="index.php">All Groups</a></li>
                <li class="side-li"><a href="mygroups.php">My Groups</a></li>
                <li class="side-li"><a href="gcal.php">Calendar</a></li>
                <li class="side-li"><a href="nearby.php">Nearby</a></li>
                <li class="side-li"><a href="profile.php">Profile</a></li>
                
              </ul>

              
              <div class="message" id="mes-wrap">
                  <div class="row"><div class="mes" style="margin-left:20px">System: Welcome!</div></div>
              </div>
              <input type="field" class="form-control col-md-9" name="content" id="input-mes" placeholder="Enter to chat">
                  

            </div>
          </div>
          <div class="col-md-9">
            <div class="bs-sidebar hidden-print affix-top" role="complementary">
              <div id="map"></div>
              
            </div>
          </div>

     </div>
    
    </div>

  <?php else: 
    
    echo "Sigin in first";

  ?>
   <!-- /container -->
      
  <?php endif?>
     
  

  


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     <script>
       var marker_me;
       var map;
       var groupusers = new Array();
       var usermsg = new Array();

       function getLocation()
          {
          if (navigator.geolocation)
            {
            navigator.geolocation.getCurrentPosition(showPosition);
            }
          else{console.log("Geolocation is not supported by this browser.");}
          }
        function showPosition(position)
          {
            L.Map = L.Map.extend({
                openPopup: function(popup) {
                    //        this.closePopup();  // just comment this
                    this._popup = popup;

                    return this.addLayer(popup).fire('popupopen', {
                        popup: this._popup
                    });
                }
            });

            map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);
            L.tileLayer('http://{s}.tile.cloudmade.com/c643fd47e4274547b92b9051310b0260/997/256/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
                maxZoom: 18
            }).addTo(map);
            //marker_me = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            //marker_me.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
            jQuery.ajax({
                       type: "GET",
                       url: "ezgetuserpos.php",
                       data: { groupid: <?php echo $_GET["groupid"]?>},
                       cache: false,
                       success: function(response){
                        //console.log(response);
                        var users = JSON.parse(response);
                        
                        //console.log(Object.keys(users).length);
                        for (var i = 0; i < Object.keys(users).length; i++) { 
                            
                              if(users[i].lat != null && users[i].lng != null){
                                  var temp = L.marker([users[i].lat, users[i].lng]).addTo(map);
                                  temp.bindPopup("<b>"+users[i].user+"</b>:"+users[i].msg);
                                  
                                  groupusers.push(temp);
                                  usermsg.push(users[i].msg);

                              }

                            

                        }
                       
                        
                      }
                        
                    
            });
        }

            var lastResponse;
            var newResponse;
            function addMessage(content, id ,groupid){

                  jQuery.ajax({
                       type: "POST",
                       url: "ezaddmessage.php",
                       data: { userid: id, groupid: groupid, content:content},
                       cache: false,
                       success: function(response){
                       console.log(response);
                       $('#input-mes').val("");
                    }
                    });
            }

            function fetchMessage(groupid){

                  jQuery.ajax({
                       type: "GET",
                       url: "ezgetgroupmessage.php",
                       data: { groupid: groupid},
                       cache: false,
                       success: function(response){
                       //console.log(response);
                        newResponse = response;
                        if(newResponse != lastResponse){
                        document.getElementById("mes-wrap").innerHTML = response;
                        var myDiv = $("#mes-wrap");
                        
                        window.scrollTo(0, myDiv.prop("scrollHeight"));
                 
                        myDiv.animate({ scrollTop: myDiv.prop("scrollHeight") - myDiv.height() }, 30);
                        }
                        lastResponse = newResponse;
                    }
                    });
            }
            

      $(document).ready(function() {
        
        setInterval(function(){
                fetchMessage(<?php echo $_GET["groupid"]?>);
            }, 500);

        getLocation();
       
         $('#input-mes').keypress(function(event) {
                if(event.which == 13) {
                    event.preventDefault();
                    
                    console.log("aaaa");
                    if($('#input-mes').val()!=""){addMessage($('#input-mes').val(),<?php echo $_SESSION['userid_google']?>,<?php echo $_GET["groupid"]?>);

                    }
                }
            });
            $('#send-btn').click(function(){
               addMessage($('#input-mes').val(),<?php echo $_SESSION['userid_google']?>,<?php echo $_GET["groupid"]?>);

         
            });

        function updateLoc(userid, lat, lng){

                  jQuery.ajax({
                       type: "POST",
                       url: "ezupdatepos.php",
                       data: { userid: userid, lat: lat, lng: lng },
                       cache: false,
                       success: function(response){
                       //console.log(response);
                        
                      }
                        
                    
                    });
        }

         function getUsers(groupid){

                  jQuery.ajax({
                       type: "GET",
                       url: "ezgetuserpos.php",
                       data: { groupid: groupid},
                       cache: false,
                       success: function(response){
                        //console.log(response);
                        var users = JSON.parse(response);
                        
                        console.log(Object.keys(users).length);
                        for (var i = 0; i < Object.keys(users).length; i++) { 
                            
                                  //console.log(i);
                                  //console.log(groupusers[i]);
                                  var m = groupusers[i];
                                  if(users[i].msg != usermsg[i]){
                                  m.closePopup();
                                  m.setPopupContent("<b>"+users[i].user+"</b>:"+users[i].msg);
                                  m.openPopup();
                                  usermsg[i] = users[i].msg;
                                  }
                                  m.setLatLng([users[i].lat, users[i].lng]);
                                  m.update();

                              

                            

                        }
                        
                      }
                        
                    
                    });
        }


        function updatePosition(position){
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          //console.log("<?php echo $_SESSION['userid_google']?>");
          updateLoc(<?php echo $_SESSION['userid_google']?>, lat, lng);
          //var latlng = L.latLng(lat, lng);
          //marker_me.setLatLng(latlng);
          //marker_me.update();
          getUsers(<?php echo $_GET["groupid"]?>);
        }
        
        setInterval(function(){
                if (navigator.geolocation)
                  {
                  
                  navigator.geolocation.getCurrentPosition(updatePosition);
                  }
                else{console.log("Geolocation is not supported by this browser.");}
                
                
            }, 500);


      });


    </script>
    <script src="js/bootstrap.min.js"></script>
  </body>
  <?php if ($user || $ezuser_username || isset($_SESSION['gplusuer'])): ?>
    <?php else: ?>
      <link href="home.css" rel="stylesheet">
    <?php endif?>
</html>