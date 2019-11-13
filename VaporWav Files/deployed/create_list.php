<?php
  //This is the upload action that uploads an image to S3 and updates database
  include 'dbconn.php';
  include 'queries.php';

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

  if(isset($_POST['name'])) {
    $listName = $_POST['name'];
  }

  $listCheck = "SELECT list FROM lists WHERE user_id = '".$_SESSION['userData']['id']."'";
  $listRes = $conn->query($listCheck);
  $check = FALSE;
  if($listRes->num_rows > 0) {
    while($row = $listRes->fetch_assoc()) {
      if($row['list'] === $listName) {
        $check = TRUE;
      }
    }
  }

  if($check == TRUE) {
    $message = "Sorry, you have already created a list with this name.";
  } else {
    $listQ = "INSERT INTO `lists`(`user_id`, `list`) VALUES ('".$_SESSION['userData']['id']."', '".$listName."')";
    $result = $conn->query($listQ);
    if($result) {
      $_SESSION['lists'][] = $listName;
      $message = "Success";
    } else {
      $message = "Something went wrong.";
    }
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>