<?php
  if( !$_GET["groupid"])
  {
  }
?>

<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

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
    <link href="nprogress.css" rel="stylesheet">
  <script src="nprogress.js"></script>
   
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="bootstrap-datepicker.js"></script>
    
  <script src='lib/jquery-ui.custom.min.js'></script>
    <link href="datepicker.css" rel="stylesheet">

    <link href="nav.css" rel="stylesheet">

    <link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
  <link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />

  <script src='fullcalendar/fullcalendar.min.js'></script>

  <style>
    .submit{
      border-top-right-radius:2px;
      border-bottom-right-radius: 2px;
      border-left-width: 0px; 
    }
    #calendar {
      width: 100%;
      margin: 0 auto;
      }

      .mes{
            margin:10px;
            padding:5px;
            border-radius: 4px;
            border:1px solid rgb(204, 204, 204);
            display: inline-block;
            max-width: 40%;
        }

      .shout_box {
background:#fff; width:260px; overflow:hidden;
position:fixed; bottom:0; right:0%; z-index:9;
}
.shout_box .header .close_btn {
background: url(images/close_btn.png) no-repeat 0px 0px; 
float:right; width:15px;
height: 15px;
}
.shout_box .header .close_btn:hover {
background: url(images/close_btn.png) no-repeat 0px -16px;
}
.shout_box .header .open_btn {
background: url(images/close_btn.png) no-repeat 0px -32px;
float:right; width:15px;
height:15px;
}
.shout_box .header .open_btn:hover {
background: url(images/close_btn.png) no-repeat 0px -48px;
}
.shout_box .header{
padding: 5px 3px 5px 5px;
font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
font-weight: bold; color:#555;
border: 1px solid #ccc;
border-bottom:none; cursor:pointer;
}
.shout_box .header:hover{

}
.shout_box .message_box {
background: #FFFFFF; height: 200px;
overflow:auto; border: 1px solid #CCC;
}
.shout_msg{
margin-bottom: 10px; display: block;
border-bottom: 1px solid #F3F3F3; padding: 0px 5px 5px 5px;
font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif; color:#7C7C7C;
}
.message_box:last-child { border-bottom:none;
}
time{ 
font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
font-weight: normal; float:right; color: #D5D5D5;
}
.shout_msg .username{
margin-bottom: 10px;margin-top: 10px;
}
.user_info input {
width: 100%; height: 25px; border: 1px solid #CCC; border-top: none; padding: 3px 0px 0px 3px;
font: 11px 'lucida grande', tahoma, verdana, arial, sans-serif;
}
.shout_msg .username{
font-weight: bold; display: block;
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
            <li class="side-li"><a class="active" href="gcal.php">Calendar</a></li>
            <li class="side-li"><a href="nearby.php">Nearby</a></li>
            <li class="side-li"><a href="profile.php">Profile</a></li>
            
          </ul>
          <p>Choose Date For Recommendation: <br>
          <div class="input-group">
            <input type="text" class="span2 form-control" value="12/02/13" data-date-format="mm/dd/yy" id="datepicker"> 
          
            <span class="input-group-btn">
              <button type="button" class="btn btn-default submit">Submit</button></p>
            </span>
          </div>
          
          <p id="mygroupcal" style="cursor:pointer;">My group calendars:</p>
          
          <ul class="grouplist">
          <?php

                  define( "DB_SERVER", getenv('OPENSHIFT_MYSQL_DB_HOST') ); 
                  define( "DB_USER", getenv('OPENSHIFT_MYSQL_DB_USERNAME') ); 
                  define( "DB_PASSWORD", getenv('OPENSHIFT_MYSQL_DB_PASSWORD') ); 
                  define( "DB_DATABASE", getenv('OPENSHIFT_APP_NAME') );
                  
                  $userid_google = $_SESSION['userid_google'];

                  if(@mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD)){ 
                    if(mysql_select_db(DB_DATABASE)){ } }else{ die(mysql_error()); 
                    } //echo "aaaa";
                     $result=mysql_query("SELECT * FROM `usergroups` WHERE userid='" . $userid_google . "'"); 
                     //echo "bbb"; 
                     $rownum = mysql_num_rows($result); 
                    

                     while($row = mysql_fetch_array($result,MYSQLI_ASSOC)) {
                      //echo "aaaa"; 
                      //echo $row['userid'];
                      $res = mysql_query("SELECT * FROM `groups` WHERE groupid='" . $row['groupid'] . "'"); 
                      $buf = mysql_fetch_array($res,MYSQLI_ASSOC);
                      echo "<li><a href=\"";
                      echo "gcal.php?groupid=";
                      echo $buf['groupid'];
                      echo "\" class=\"\">";
                      echo $buf['name'];
                      echo "</a>";
                      echo "</li>";
                      
                    } 
                      

                      ?>  
                    </ul>
        </div>
      </div>

       <div class="col-md-9" style="margin-top:30px;">
        <div class="bs-sidebar hidden-print affix-top" role="complementary">

          <?php
          //error_reporting(E_ALL);
          //ini_set('display_errors', '1');
            //$userinfo = $oauth2Service->userinfo->get();
            $calList = $cal->calendarList->listCalendarList();

            
            //echo $rawCal->getSummary();;
            //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
            
            //print all cal event
            //print "<h1>Calendar List</h1><pre>" . print_r($rawCal['data']['items'], true) . "</pre>";
            
            //print "<h1>Userinfo</h1><pre>" . print_r($userinfo, true) . "</pre>";

          ?>
           
          <div id='calendar'></div>
          <br><br>
        </div>

      </div>

     </div>
    
    </div>

<?php if($_GET["groupid"]):?>
    <!-- shoutbox -->
<div class="shout_box">
<div class="header">Group chat <div class="close_btn">&nbsp;</div></div>
  <div class="toggle_chat">
  <div class="message_box" id="message_box">
    </div>
    <div class="user_info">
    <input name="shout_message" id="shout_message" type="text" placeholder="Type Message Hit Enter" maxlength="100" /> 
    </div>
    </div>
</div>

<?endif?>
<!-- shoutbox end -->

  <?php else: 
    echo "Sigin in first";
  ?>
   <!-- /container -->
      
  <?php endif?>
     
  

  


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     
    <script src="js/bootstrap.min.js"></script>
  </body>
  <?php if ($user || $ezuser_username || isset($_SESSION['gplusuer'])): ?>
    <?php else: ?>
      <link href="home.css" rel="stylesheet">
    <?php endif?>
    <script>
  
  function getgroupcal(userid, groupid){

                  jQuery.ajax({
                       type: "GET",
                       url: "ezgetgroupcalendar.php",
                       data: { userid: userid, groupid: groupid},
                       cache: false,
                       success: function(response){
                        
                        var JS= document.createElement('script');
                        JS.text= response;
                        document.body.appendChild(JS);
                    }
                    });
            }

  
  $(document).ready(function() {
    $("#mygroupcal").click(function(){
      $(".grouplist").toggle("fast");
    });
    //toggle hide/show shout box
    $(".header").click(function (e) {
        //get CSS display state of .toggle_chat element
        var toggleState = $('.toggle_chat').css('display');

        //toggle show/hide chat box
        $('.toggle_chat').slideToggle();
        
        //use toggleState var to change close/open icon image
        if(toggleState == 'block')
        {
            $(".header div").attr('class', 'open_btn');
        }else{
            $(".header div").attr('class', 'close_btn');
        }
    });

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    
    var calendar = $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      selectable: false,
      selectHelper: true,
      select: function(start, end, allDay) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.fullCalendar('renderEvent',
            {
              title: title,
              start: start,
              end: end,
              allDay: allDay
            },
            true // make the event "stick"
          );
        }
        calendar.fullCalendar('unselect');
      },
      editable: true,
      defaultView: 'agendaWeek',
      events: [
       
      ]


    });
     
     <?php 
        echo "var events = [];". "\n";
        $calsize = sizeof($calList['items']);
        $calcount = 0;
        foreach($calList['items'] as $eachcal){
            $calcount ++ ;
              try{
            $rawCal = $cal->events->listEvents($eachcal['id']);
            }catch (Google_ServiceException $e) {
              if ($e->getCode() == 404) {
                print "The event doesn't exist";

              }
            }

            $size = sizeof($rawCal['data']['items']);
        //echo $size;
            $count = 0;
            $events = array_reverse($rawCal['data']['items']);

            foreach($events as $event){
              if($event['summary']!=""){
              $count ++;
              
              
              echo "events.push({";
              echo "  title: \"";

              echo addslashes(htmlentities($event['summary']));
              echo "\"";
              echo ",";
              if(!$event['start']['date']){
              $pieces = explode("T", $event['start']['dateTime']);
              $t = explode("-", $pieces[0]);
              $t_2 = explode(":", $pieces[1]);
              //echo $pieces[0];

              $pieces2 = explode("T", $event['end']['dateTime']);
              $t2 = explode("-", $pieces2[0]);
              $t2_2 = explode(":", $pieces2[1]);
              
              printf("start: new Date(%d, %d, %d, %d, %d),",$t[0], $t[1]-1, $t[2], $t_2[0], $t_2[1]);
              printf("end: new Date(%d, %d, %d, %d, %d),",$t2[0], $t2[1]-1, $t2[2], $t2_2[0], $t2_2[1]);
              echo "allDay: false";
              
              $startdate = new DateTime($event['start']['dateTime']);
              $startdateStr = $startdate->format('jS F, ga');
              $enddate = new DateTime($event['end']['dateTime']);
              $enddateStr = $enddate->format('jS F, ga');
              
              $query = "SELECT * FROM events WHERE id='" . $event['id'] . "'";
              $result = mysql_query($query) or die('query fail');

              
                if(mysql_num_rows($result) == 0){
                    $query = "INSERT INTO `events` ( `id` ,`title`, `details`, `start`, `end`) VALUES ('" . $event['id']. "', '" . addslashes(htmlentities($event['summary'])) . "', '" . addslashes(htmlentities($event['summary'])) . "', '" . $startdate->format("Y-m-d H:i:s") . "', '" . $enddate->format("Y-m-d H:i:s") . "') ";
                    //echo $query;
                    mysql_query($query) or die("query_fail" . mysql_error());

                    $query_insert = "INSERT INTO usercalendar (userid, eventid) VALUES ('" . $_SESSION['userid_google'] . "', '" . $event['id'] . "')" or die('insert fail');
                    mysql_query($query_insert) or die('Insert user to group failed');
        
                    //$id = mysql_insert_id();

                }else{
                  
                }

              
             
              }
              else{
                $pieces = explode("-", $event['start']['date']);
                printf("start: new Date(%d, %d, %d),",$pieces[0], $pieces[1]-1, $pieces[2]);
                $pieces = explode("-", $event['end']['date']);
                printf("end: new Date(%d, %d, %d)",$pieces[0], $pieces[1]-1, $pieces[2]-1);

                //$query = "INSERT INTO `events` (`eventid`, `etag` ,`title`, `details`, `start`, `end`) VALUES (NULL, '" . $event['etag']. "', '" . $event['summary'] . "', '" . $event['summary'] . "', '" . $start . "', '" . $end . "') ";
                //mysql_query($query) or die("query_fail" . mysql_error());
             
              
              }
              echo "});". "\n";

              //echo "{";
              
              //echo "}";
              //if($count > 30)break;
              //if($count < 30 || $calcount <= $calsize )echo ",";

            }
          }


        }


        echo "$('#calendar').fullCalendar( 'addEventSource', events);";


        ?>
      
      <?php if($_GET["groupid"]):?>
        getgroupcal(<?php echo $_SESSION['userid_google']?>,<?php echo $_GET["groupid"]?>);
      <?endif?>

  });

</script>
<script type="text/javascript">
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
                       $('#shout_message').val("");
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
                        document.getElementById("message_box").innerHTML = response;
                        var myDiv = $("#message_box");
                        
                        window.scrollTo(0, myDiv.prop("scrollHeight"));
                 
                        myDiv.animate({ scrollTop: myDiv.prop("scrollHeight") - myDiv.height() }, 30);
                        }
                        lastResponse = newResponse;
                    }
                    });
            }


            function getDate(groupid,date){
                 
                  jQuery.ajax({
                       type: "POST",
                       url: "gettime.php",
                       data: { groupid: groupid, date: date},
                       cache: false,
                       success: function(response){
                        //console.log(response);
                        var slots = JSON.parse(response);
                        var events = [];
                        var selectday;
                        console.log(Object.keys(slots).length);
                        for (var i = 0; i < Object.keys(slots).length; i++) { 
                            var t = slots[i].start.split(/[- :]/);
                            var datestart = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                            var t2 = slots[i].end.split(/[- :]/);
                            var dateend = new Date(t2[0], t2[1]-1, t2[2], t2[3], t2[4], t2[5]);
                            
                            selectday = new Date(t2[0], t2[1]-1, t2[2]);
                            
                            events.push({
                            title: 'Share Time Block',
                            color: "#46E38C",
                            start: datestart,
                            end: dateend,
                            allDay: false
                                
                            });


                           

                        }

                        $('#calendar').fullCalendar( 'addEventSource', events);
                        $('#calendar').fullCalendar('gotoDate', selectday);
                      }
                        
                    
                    });
            }
            $('#datepicker').keypress(function(event) {
                if(event.which == 13) {
                    event.preventDefault();
                    if($('#datepicker').val()!=""){
                      <?php if($_GET["groupid"]):?>
                      getDate(<?php echo $_GET["groupid"]?>,$( "#datepicker" ).val());
                      <?php endif?>

                    }
                }
            });
            $(".submit").click(function(){
              event.preventDefault();
                    if($('#datepicker').val()!=""){
                      <?php if($_GET["groupid"]):?>

                      getDate(<?php echo $_GET["groupid"]?>,$( "#datepicker" ).val());
                      <?php endif?>

                    }
            });

            setInterval(function(){
                fetchMessage(<?php echo $_GET["groupid"]?>);
            }, 500);

            $('#shout_message').keypress(function(event) {
                if(event.which == 13) {
                    event.preventDefault();
                    if($('#shout_message').val()!=""){addMessage($('#shout_message').val(),<?php echo $_SESSION['userid_google']?>,<?php echo $_GET["groupid"]?>);

                    }
                }
            });
            
</script>
 <script type="text/javascript">
    $(function(){
      
      $('#datepicker').datepicker();
       NProgress.start();
    }(jQuery));
    </script>
</html>

