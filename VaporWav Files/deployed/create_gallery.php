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
    $galName = $_POST['name'];
  }

  $galCheck = $selectGalleries_SessionData;
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
    $galQ = $insertUserGallery_Galleries;
    $result = $conn->query($galQ);
    if($result) {
      $_SESSION['galleries'][] = $galName;
      $message = "Success";
    } else {
      $message = "Something went wrong.";
    }
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
