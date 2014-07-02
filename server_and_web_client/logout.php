<?php

require 'src/facebook.php';
 
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => 'YOUR_ID_HERE',
  'secret' => 'YOUR_SECRET_HERE',
));
 
//on logout page
setcookie('fbs_'.$facebook->getAppId(), '', time()-100, '/', 'http://myfacedetect-weiqing.rhcloud.com');
session_destroy();
header('Location: http://ezride-weiqing.rhcloud.com');

?>