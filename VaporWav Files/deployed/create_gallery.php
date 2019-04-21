<?php

  //This is the upload action that uploads an image to S3 and updates database

  session_start(); 

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }
 
  //Include the database credentials
  include 'dbconfig.php';	
  
  //DB info
  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }

  if(isset($_POST['name'])) {
    $galName = $_POST['name'];
  }

  $galCheck = "SELECT galleries FROM galleries WHERE user_id = '".$_SESSION['userData']['id']."'";
  $galRes = $conn->query($galCheck);
  $check = FALSE;
  if($galRes->num_rows > 0) {
    while($row = $galRes->fetch_assoc()) {
      if($row['galleries'] === $galName) {
        $check = TRUE;
      }
    }
  }

  if($check == TRUE) {
    $message = "Sorry, you have already created a gallery with this name.";
  } else {
    $galQ = "INSERT INTO `galleries`(`user_id`, `galleries`) VALUES ('".$_SESSION['userData']['id']."', '".$galName."')";
    $result = $conn->query($galQ);
    if($result) {
      $_SESSION['galleries'][] = $galName;
      $message = "Success";
    } else {
      $message = "Something went wrong.";
    }
  }

  //Redirect back to the upload page
  header("Location: galleries.php?msg=$message");

?>
