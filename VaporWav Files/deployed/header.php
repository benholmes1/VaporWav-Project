<?php
include 'dbconn.php';
?>
<!doctype html>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">

    <title>VaporWav - Share your art</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="jquery.alphanum-master/jquery.alphanum.js"></script>
    <link rel="stylesheet" href="stylesFinal.css">

    <script src="acctscript.js"></script>
</head>
<body>
  <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!--<header>
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
      <a href = "galleries.php">Galleries</a>
      <a href = "friendPage.php">Friends</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>-->
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="home.php"><h1 style="color:white">VaporWav</h1></a>
    <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarToggle">
      <form style="padding-right:2em" class="form-inline" action="searchUser.php" method="get">
        <div class="input-group">
          <input style="font-family:Tinos" class="form-control" type="text" name="searchQ" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button style="background-color:mediumpurple" class="btn" type="submit"><i style="color:white" class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="home.php"><button class="btn">Home</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="uploadPage.php"><button class="btn">Upload</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="feed.php"><button class="btn">Explore</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="galleries.php"><button class="btn">Galleries</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="friendPage.php"><button class="btn">Friends</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="account.php"><button class="btn">My Account</button></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="logout.php"><button class="btn">Logout</button></a>
        </li>
      </ul>
    </div>
  </nav>

<!--<main class="container">-->