<?php
  if( !$_GET["groupid"])
  {
     echo "Choose a group first";
     exit();
  }
?>



<!DOCTYPE html>
<html>
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
    <style type="text/css">
         .message{
            margin-top: 60px;
            border:1px solid rgb(204, 204, 204);
            border-bottom-width: 0px;
            border-radius: 4px 4px 0px 0px;
            width:100%;
            height:500px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        #input-mes{
            border-top-left-radius: 0px;
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
       
    </style>
    
    <!-- Custom styles for this template -->
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

    <body>
        <?php include 'header.php'; ?>
        <div class="container">
            <div class="row">
            <div class="message" id="mes-wrap">
                <div class="row"><div class="mes" style="margin-left:20px">System: Welcome!</div></div>
            </div>
            </div>
            

            <div class="row">
                <div class="input-group">
                <input type="field" class="form-control col-md-9" name="content" id="input-mes" placeholder="Enter to chat">
                <br>
                <span class="input-group-btn">
                <button type="submit" class="btn btn-default" id="send-btn">Submit</button>
                </span>
                </div>
            </div>
            
        </div>
    </body>
<html>
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
            setInterval(function(){
                fetchMessage(<?php echo $_GET["groupid"]?>);
            }, 500);

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
</script>