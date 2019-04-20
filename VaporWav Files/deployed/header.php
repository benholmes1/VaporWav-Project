<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>VaporWav Account</title>
<link rel="stylesheet" href="stylesFinal.css"/>
</head>
<body>
<header>
  <div class="padThis">
  <h1>VaporWav</h1>
  <p class="underHeader">Show us what you have been working on.</p>
  </div>
  <nav>
  <!list of the seperate parts of this page>
  <ul>
    <li><a href = "index.php">Home</a>
    <a href = "uploadPage.php">Upload</a></li>
    <li>
    <div class="dropdown">
      <a href="home.php" class="dropL">Galleries</a>
      <div class="dropdown-content">
        <a href="home.php">Your Gallery</a>
        <a href="#">Create New Gallery</a>
      </div>
    </div>
    </li>
  </ul>
  <ul class="leftHead">
    <li><a href = "account.php">My Account</a>
    <a href = "logout.php">Logout</a></li>
  </ul>
  </nav>
</header>