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

  //if(!empty($_POST['nname1'] {
    $editName = "UPDATE usernames SET nickname = '".$_POST['nname1']."' WHERE id = '".$_SESSION['userData']['id']."'";
    $edit = $conn->query($editName);
    if($edit) {
      $getName = "SELECT nickname from usernames WHERE id = '".$_SESSION['userData']['id']."'";
      $result = $conn->query($getName);
      $nickname = $result->fetch_assoc(); 
      $_SESSION['nickname'] = $nickname["nickname"];
      //header('Location: account.php');
      echo "Success";	
   } else {
      //header('Location: account_change.php?fail=1');
      echo "Username Taken";
   } 

  //}
//  }
?>

