<?php
  //This is the upload action that uploads an image to S3 and updates database
  include 'dbconn.php';	
  include 'queries.php';

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

  //This is needed to use AWS SDK for PHP
  require './vendor/autoload.php';
 

  if(isset($_GET['key'])) {
    $key = $_GET['key'];
  }
  if(isset($_GET['list'])) {
      $list = $_GET['list'];
  }

  $id = $_SESSION['userData']['id'];
  $keyArr = explode('/', $key);

  $delQuery = "DELETE FROM `list_items` where `user_id` = '".$id."' and `list` = '".$list."' and `keyname` = '".$keyArr[1]."'";
  $result = $conn->query($delQuery);
  if($result) {
    $message = "Success";
  }
  else {
    $message = "Something went wrong.";
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
