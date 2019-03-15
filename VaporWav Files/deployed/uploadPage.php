<?php
  session_start();
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
  } /*else {
    if(isset($_GET['fail']) && $_GET['fail'] == 1)
    {
      $uniqueErr = "Username Taken";
      echo "<script type='text/javascript'>alert('$uniqueErr');</script>";
     
    }
  }
*/
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
        <li><a href = "index.php">Home</a></li>
      </ul>
      <ul class="leftHead">
        <li><a href = "account.php">My Account</a>
        <a href = "logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>


  <div class="container">
    <div class="wrapacct">
      <h2>Upload Image</h2>
      <div id="form">
      <div class="acctform">
	<form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm" class="acctform">
          <input id="imgFile" name="imgFile" type="file" required>
	  <input id="upload" type="submit" value="Upload" style="float:right">
        </form>
	<textarea placeholder="Description . . ." style="width:100%;height:5em;box-sizing:border-box;resize:none" id="desc" name="desc" form="uploadForm"></textarea>
      </div>
      </div>
    </div>
  </div>
</body>
</html