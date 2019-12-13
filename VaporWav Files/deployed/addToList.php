<?php

  //This is the upload action that uploads an image to S3 and updates database

  session_start(); 

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

  //This is needed to use AWS SDK for PHP
  require './vendor/autoload.php';
 
  //Include the database credentials
  include 'dbconn.php';	
  include 'queries.php';

  if(isset($_GET['list'])) {
    $list = $_GET['list'];
  }
  if(isset($_GET['key'])) {
    $key = $_GET['key'];
  }
  if(isset($_GET['mail'])) {
    $mail = $_GET['mail'];
  }

  $keyArr = explode('/', $key);

  $addQuery = "INSERT INTO `list_items`(`user_id`, `list`, `creator`, `keyname`) VALUES ('".$_SESSION['userData']['id']."', '".$list."', '".$mail."', '".$keyArr[1]."')";
  $addQueryRes = $conn->query($addQuery);
  if($addQueryRes) {
    $message = "Success";
  } else {
    $message = "Fail";
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
