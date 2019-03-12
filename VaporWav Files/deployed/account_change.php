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
      <h2>Edit Account Settings</h2>
      <div id="form">
        <div class="acctform">
	  <label>Nickname: </label>
	  <input id="nname" type="text" placeholder="Enter nickname" required>
	  <br>
	  <input id="submit" type="button" value="Submit">
	</div>
      </div>
    </div>
  </div>
  <script>
   $("#nname").alphanum({
     allowSpace: false,
     allowNewline: false,
     allowOtherCharSets: false
   });
 </script>
</body>
</html
