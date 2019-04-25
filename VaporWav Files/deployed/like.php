<?php

  session_start();

  include "dbconfig.php";
  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;

  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }
  //get ids
  
  if(isset($_POST['key'])) {    //keyname
    $keyname = $_POST['key'];
  }
  
  // Check entry within table
  //keyname and userid
  $queryLikeI = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."' and userid='".$_SESSION['userData']['id']."'";
  $queryLikeIRes = $conn->query($queryLikeI);
  if($queryLikeIRes) {
    $likesinfoI = $queryLikeIRes->fetch_assoc();
    $likescountI = $likesinfoI['likescount'];
  }

  if($likescountI == '0'){
    $insertquery = "INSERT INTO `likes`(`userid`, `keyname`) VALUES ('".$_SESSION['userData']['id']."', '".$keyname."')";
    $insertResult = $conn->query($insertquery);
    if($insertResult) {
      $test = "Success";
    } else {
      $test = "Fail";
    }
  }

  // Count post total likes
  $queryLike = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $arrayreturn = array("likes"=>$likescount, "test"=>$test, "key"=>$keyname, "id"=>$_SESSION['userData']['id']);

  echo json_encode($arrayreturn);