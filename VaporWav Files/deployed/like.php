<?php

  include 'dbconn.php';
  //get ids
  if(isset($_POST['key'])) {
    $keyname = $_POST['key'];
  }

  if(isset($_POST['type'])) {
    $type = $_POST['type'];
  }
  
  // Check entry within table
  $queryLikeI = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."' and userid='".$_SESSION['userData']['id']."'";
  $queryLikeIRes = $conn->query($queryLikeI);
  if($queryLikeIRes) {
    $likesinfoI = $queryLikeIRes->fetch_assoc();
    $likescountI = $likesinfoI['likescount'];
    $test0 = "Success";
  } else {
    $test0 = "Fail";
  }

  if($likescountI == '0'){
    $insertquery = "INSERT INTO `likes`(`userid`, `keyname`) VALUES ('".$_SESSION['userData']['id']."', '".$keyname."')";
    $insertResult = $conn->query($insertquery);
    if($insertResult) {
      $test1 = "Success";
    } else {
      $test1 = "Fail";
    }
  } else {
    $delLikeQuery = "DELETE FROM likes WHERE keyname = '".$keyname."' and userid='".$_SESSION['userData']['id']."'";
    $delLikeRes = $conn->query($delLikeQuery);
    if($delLikeRes) {
      $test2 = "Success";
    } else {
      $test2 = "Fail";
    }
  }
  

  // Count post total likes and unlikes
  $queryLike = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $insertLikes = "UPDATE images SET likes = '".$likescount."' WHERE keyname = '".$keyname."'";
  $insertLikeRes = $conn->query($insertLikes);
  if($insertResult) {
    $test3 = "Success";
  } else {
    $test3 = "Fail";
  }

  $arrayreturn = array("likes"=>$likescount, "test0"=>$test0, "tes1"=>$test1, "test2"=>$test2, "test3"=>$test3, "key"=>$keyname, "id"=>$_SESSION['userData']['id']);

  echo json_encode($arrayreturn);
