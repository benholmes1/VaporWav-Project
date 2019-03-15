<?php

session_start();

if(!($_SESSION['login'])) {
  header('Location: index.php');
}
?>

<!DOCTYPE html>
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
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>

  <div class="container">
    <p style="font-family:Streamster;font-size:350%" class="count">Coming Soon</p>
    <hr class="center">
    <p id="demo" class="count"></p>
    <script src="countdown.js"></script>
  </div>

</body>
</html>
