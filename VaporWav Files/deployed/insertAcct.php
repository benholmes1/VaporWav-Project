<?php
  session_start();
//  if(isset($_POST['nname'])){

  //Check if user is logged in
  if(!($_SESSION['login'])) {
    header('Location: index.php');
    exit();
  }

    include 'dbconfig.php';

    $dbHost     = DB_HOST;
    $dbUsername = DB_USERNAME;
    $dbPassword = DB_PASSWORD;
    $dbName     = DB_NAME;
   
    // Connect to the database
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if($conn->connect_error){
      die("Failed to connect with MySQL: " . $conn->connect_error);
    }

    $check = 0;

  //if(!empty($_POST['nname1'] {
    $editName = "UPDATE usernames SET nickname = '".$_POST['nname1']."' WHERE id = '".$_SESSION['userData']['id']."'";
    $edit = $conn->query($editName);
    if($edit) {
      $getName = "SELECT nickname from usernames WHERE id = '".$_SESSION['userData']['id']."'";
      $result = $conn->query($getName);
      $nickname = $result->fetch_assoc(); 
      $_SESSION['nickname'] = $nickname["nickname"];
      //header('Location: account.php');
      $check = 1;
   } else {
      //header('Location: account_change.php?fail=1');
      $check = 0;
   }

   if($_POST['privacy'] == "pub") {
     $privacySetting = "0";
   } else {
     $privacySetting = "1";
   }

   $privacyQuery = "UPDATE users SET private = '".$privacySetting."' WHERE id = '".$_SESSION['userData']['id']."'";
   $privacyQRes = $conn->query($privacyQuery);
   if($privacyQRes) {
     $getPrivacy = "SELECT private FROM users where id = '".$_SESSION['userData']['id']."'";
     $getResult = $conn->query($getPrivacy);
     $privacy = $getResult->fetch_assoc();
     $_SESSION['private'] = $privacy['private'];
     $check = 1;
   } else {
     $check = 0;
   }

   if($check == 1) {
     echo "Success";
   } else {
     echo "Something went wrong";
   }

   header('Location: account.php');

  //}
//  }
?>

