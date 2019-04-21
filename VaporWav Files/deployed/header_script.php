<?php
  session_start();
?>

<html>
<head>
  <!needed this to stop a warning in the validator>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>VaporWav - Share your art</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="jquery.alphanum-master/jquery.alphanum.js"></script>
  <link rel="stylesheet" href="stylesFinal.css">
  <script src="acctscript.js"></script>
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
          <a href="galleries.php" class="dropL">Galleries</a>
          <div class="dropdown-content">
            <a href="home.php">Your Gallery</a>
            <?php
              foreach($_SESSION['galleries'] as $gal) {
                echo '<a href="home.php?gal='.$gal.'">'.$gal.'</a>';
              }
            ?>
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