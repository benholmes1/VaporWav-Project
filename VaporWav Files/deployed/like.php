<?php

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
  $userid = $_POST['userid'];
  $keyname = $_POST['keyname'];
  
  // Check entry within table
  $queryLikeI = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."' and userid='".$userid."'";
  $queryLikeIRes = $conn->query($queryLikeI);
  $likesinfoI = $queryLikeIRes->fetch_assoc();
  $likescountI = $fetchdata['likesinfoI'];

  if($count == 0){
    $insertquery = "INSERT INTO likes(userid,keyname) values(".$userid.",".$keyname.")";
    $conn->query($insertquery);
  }

  // Count post total likes and unlikes
  $queryLike = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $arrayreturn = array("likes"=>$likescount);

  echo json_encode($arrayreturn);