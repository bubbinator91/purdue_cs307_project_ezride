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

if(!isset($_GET['userid']))
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
      $query = "SELECT * FROM userinfo WHERE userid='" . $_GET['userid'] . "'";
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
       #map{
        margin-top: 0px;
        height:100%;
        width:100%;
       }
       #locate{
        position:fixed;
        top:10px;
        right:10px;
        z-index: 9;
       }
    </style>
    
  </head>
 <body>
    

  <?php if ($ezuser_username): ?>
  
    <div class="main " style="height:100%;">
      <!-- Example row of columns -->
   
            <button type="button" class="btn btn-default btn" id="locate"><span class="glyphicon glyphicon-map-marker"></span></button>
              <div id="map"></div>
              
    
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
       var group;
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
                       url: "iosgetuserpos.php",
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
                        group = new L.featureGroup(groupusers);

                        map.fitBounds(group.getBounds());
                        
                      }
                        
                    
            });

          var MyControl = L.Control.extend({
    options: {
        position: 'topright'
    },

    onAdd: function (map) {
        // create the control container with a particular class name
        var container = L.DomUtil.create('div', 'my-custom-control');

        // ... initialize other DOM elements, add listeners, etc.

        return container;
    }
});

map.addControl(new MyControl());
        }

            var lastResponse;
            var newResponse;
            

      $(document).ready(function() {
        
       
        $("#locate").click(function(){

          map.fitBounds(group.getBounds());
        });

        getLocation();
       
        

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
                       url: "iosgetuserpos.php",
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
          updateLoc(<?php echo $_GET['userid']?>, lat, lng);
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