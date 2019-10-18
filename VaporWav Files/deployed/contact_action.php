<?php
  include 'header.php';
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }
    	$msg = $_POST['subject']."\n".$_POST['email'];
        wordwrap($msg,70);
        mail($_POST['triboygames@gmail.com'],"The user ".$_SESSION['name']." ",$msg);
        header("Location:home.php");
?>