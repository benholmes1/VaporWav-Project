<?php

  include 'dbconn.php';
  //get ids
  if(isset($_POST['key'])) {
    $keyname = $_POST['key'];
  }
  
  // Check entry within table
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
  

  // Count post total likes and unlikes
  $queryLike = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $insertLikes = "UPDATE images SET likes = '".$likescount."' WHERE keyname = '".$keyname."'";
  $insertLikeRes = $conn->query($insertLikes);
  if($insertResult) {
    $test = "Success";
  } else {
    $test = "Fail";
  }

  $arrayreturn = array("likes"=>$likescount, "test"=>$test, "key"=>$keyname, "id"=>$_SESSION['userData']['id']);

  echo json_encode($arrayreturn);
