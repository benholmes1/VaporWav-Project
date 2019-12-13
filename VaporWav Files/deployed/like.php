<?php

  include 'dbconn.php';
  include 'queries.php';
  //get ids
  if(isset($_POST['key'])) {
    $keyname = $_POST['key'];
  }

  if(isset($_POST['type'])) {
    $type = $_POST['type'];
  }
  
  // Check entry within table
  $queryLikeI = $selectLikescount_Likes_Keyname;
  $queryLikeIRes = $conn->query($queryLikeI);
  if($queryLikeIRes) {
    $likesinfoI = $queryLikeIRes->fetch_assoc();
    $likescountI = $likesinfoI['likescount'];
    $test0 = "Success";
  } else {
    $test0 = "Fail";
  }

  if($likescountI == '0'){
    $insertquery = $insertLikes_SessionData;
    $insertResult = $conn->query($insertquery);
    if($insertResult) {
      $test1 = "Success";
    } else {
      $test1 = "Fail";
    }
  } else {
    $delLikeQuery = $deleteLikes;
    $delLikeRes = $conn->query($delLikeQuery);
    if($delLikeRes) {
      $test2 = "Success";
    } else {
      $test2 = "Fail";
    }
  }
  

  // Count post total likes and unlikes
  $queryLike = $updateLikes;
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $insertLikes = $updateLikes_Images;
  $insertLikeRes = $conn->query($insertLikes);
  if($insertResult) {
    $test3 = "Success";
  } else {
    $test3 = "Fail";
  }

  $arrayreturn = array("likes"=>$likescount, "test0"=>$test0, "tes1"=>$test1, "test2"=>$test2, "test3"=>$test3, "key"=>$keyname, "id"=>$_SESSION['userData']['id']);

  echo json_encode($arrayreturn);
