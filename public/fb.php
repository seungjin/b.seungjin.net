<?php
// Copyright 2007 Facebook Corp.  All Rights Reserved. 
// 
// Application: seungjin_test
// File: 'index.php' 
//   This is a sample skeleton for your application. 
// 

require_once 'facebook.php';

$appapikey = 'ffb9e770c586afd75584994a95d2ec7b';
$appsecret = 'd4a29ac01e54502d84ee3d949cc7e90c';
$facebook = new Facebook($appapikey, $appsecret);
$user_id = $facebook->require_login();

// Greet the currently logged-in user!

echo "<p>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</p>";

// Print out at most 25 of the logged-in user's friends,
// using the friends.get API method

echo "<h1>DO NOT TRUST FACEBOOK. THERE IS NO PRIVACY AT ALL</h1>";

echo "<p>Friends:";
$friends = $facebook->api_client->friends_get();
$friends = array_slice($friends, 0, 25);
foreach ($friends as $friend) {
  echo "<br>$friend";
  }
  echo "</p>";
