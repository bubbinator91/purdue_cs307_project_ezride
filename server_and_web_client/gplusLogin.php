<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once 'Google/Client.php';
require_once 'Google/Service/Calendar.php';
require_once 'Google/Service/Plus.php';
require_once 'Google/Service/Oauth2.php';

session_start();

$client = new Google_Client();
$client->setApplicationName("Google+ PHP Starter Application");
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client->setClientId('YOUR_ID_HERE');
$client->setClientSecret('YOUR_SECRET_HERE');
$client->setRedirectUri('YOUR_DOMAIN_HERE');
$client->setDeveloperKey('YOUR_KEY_HERE');

$cal = new Google_Service_Calendar($client);
$plus = new Google_Service_Plus($client);

//$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));

$oauth2Service = new Google_Service_Oauth2($client); 
//print_r($_SESSION['access_token']);

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code']) && isset($_GET['authuser'])) {
  print "test";
  $client->authenticate($_GET['code']);
  print "test";
  if($client){
  $_SESSION['access_token'] = $client->getAccessToken();}
  print "test";
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'])) {
  //print "test";
  try{
  $client->setAccessToken($_SESSION['access_token']);
  } catch(Exception $e){
    print "test";
    unset($_SESSION['access_token']);
    session_destroy();
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

  }
}

if ($client->getAccessToken()) {
  try{
  $me = $plus->people->get('me');
  } catch(Exception $e){
    //print "test";
    unset($_SESSION['access_token']);
    session_destroy();
    header('Location: http://' . $_SERVER['HTTP_HOST'] ."/logout.php");

  }
  
  //store information into database for google account
    define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
    define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
    define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
    define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );
    $link = mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD) or die('connect to sql fail');
    mysql_select_db('ezride') or die('Select DB ezride fail.'); 
    
     
  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $url = filter_var($me['url'], FILTER_VALIDATE_URL);
  $img = filter_var($me['image']['url'], FILTER_VALIDATE_URL);
  $name = filter_var($me['displayName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
  $personMarkup = "<a rel='me' href='$url'>$name</a><div><img src='$img'></div>";

  $optParams = array('maxResults' => 100);
  $activities = $plus->activities->listActivities('me', 'public', $optParams);
  $activityMarkup = '';
  foreach($activities['items'] as $activity) {
    // These fields are currently filtered through the PHP sanitize filters.
    // See http://www.php.net/manual/en/filter.filters.sanitize.php
    $url = filter_var($activity['url'], FILTER_VALIDATE_URL);
    $title = filter_var($activity['title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $content = filter_var($activity['object']['content'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $activityMarkup .= "<div class='activity'><a href='$url'>$title</a><div>$content</div></div>";
  }

    $userinfo = $oauth2Service->userinfo->get(); 
    
    //$emailinfo = "wei72@purdue.edu";
    $google = filter_var($userinfo['id'], FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM userinfo WHERE google_id='" . $google . "'";
    $result = mysql_query($query) or die('query fail');

    
    if(mysql_num_rows($result) == 0){
        $query_insert = "INSERT INTO userinfo (google_id, name, avatarUrl) VALUES ('" . $google . "','" . $name . "','". $img . "')" or die('insert fail');
        mysql_query($query_insert) or die('Insert to userinfo failed');
        $_SESSION['userid_google'] = mysql_insert_id();
    }else{//echo "user already exist";
      $row = mysql_fetch_array($result);
      $query_update = "UPDATE userinfo SET avatarUrl='" . $img . "', name='" . $name ."'WHERE google_id='" . $google . "'";
      mysql_query($query_update) or die('update fail' . mysql_error());
      $_SESSION['userid_google'] = $row['userid'];
      //echo "user google";
      //echo $_SESSION['userid_google'];
    }
       
  // The access token may have been updated lazily.
  $_SESSION['access_token'] = $client->getAccessToken();
  

} else {
  $authUrl = $client->createAuthUrl();
}

if(isset($_GET['logout'])) {
  unset($_SESSION['userid_google']);
  unset($_SESSION['access_token']);
  unset($_SESSION['gplusuer']);
  session_destroy();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); // it will simply destroy the current seesion which you started before
  #header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
   
  /*NOTE: for logout and clear all the session direct goole jus un comment the above line an comment the first header function */
}
if(isset($me)){
    $_SESSION['gplusuer'] = $me; // start the session
}

?>