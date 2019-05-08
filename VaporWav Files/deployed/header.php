<?php
include 'dbconn.php'
?>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VaporWav - Share your art</title>
    <link rel="stylesheet" href="stylesFinal.css">
</head>
<body>
  <header>
    <div class="padThis">
      <h1>VaporWav</h1>
      <p class="underHeader">Show us what you have been working on.</p>
      <form action="searchUser.php" method="get">
	<input type="text" name="searchQ" placeholder="Search...">
	<button type="submit">Submit</button>
      </form>
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a>
      <a href = "feed.php">Explore</a>
      <a href = "galleries.php">Galleries</a></li>
      <a href = "friendPage.php">Friends</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>

<main class="container">