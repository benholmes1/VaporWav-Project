<?php
session_start();
include 'dbconfig.php';
include 'config.php';

$dbHost     = DB_HOST;
$dbUsername = DB_USERNAME;
$dbPassword = DB_PASSWORD;
$dbName     = DB_NAME;
   
// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
}
//echo"<script type='text/javascript'>alert('works');</script>";

//START OF ADD FRIEND
if($_GET["add"]) {
    $_query ="SELECT * FROM friend_requests WHERE sender = '" . $_SESSION["userData"]["id"] . "' AND recipient = '" . $_GET["add"] . "'";
    if($result = $conn->query($_query))
	{
	 //echo"<script type='text/javascript'>alert('$result->num_rows');</script>";
	if($result->num_rows == 0) {
	  $query1 = "INSERT INTO friend_requests SET sender = '" . $_SESSION["userData"]["id"] . "', recipient = '" . $_GET["add"] . "'";
	  if($result = $conn->query($query1)) {
	
	   // echo"<script type='text/javascript'>alert('works');</script>";
	  }
	}
    }
}
//echo"<script type='text/javascript'>alert('works');</script>";
//END OF ADD FRIEND
//START OF ACCEPT FRIEND

/*
//END OF ACCEPT FRIEND
//START OF SHOW FRIEND REQUESTS
$query = mysql_query("SELECT * FROM friend_requests WHERE recipient = '" . $_SESSION["logged"] . "'");
if(mysql_num_rows($query) > 0) {
while($row = mysql_fetch_array($query)) {
$_query = mysql_query("SELECT * FROM users WHERE id = '" . $row["sender"] . "'");
while($_row = mysql_fetch_array($_query)) {
echo $_row["username"] . " wants to be your friend. <a href=\"" . $_SERVER["PHP_SELF"] . "?accept=" . $_row["id"] . "\">Accept?</a>";
}
}
}
//END OF SHOW FRIEND REQUESTS
//START OF MEMBERLIST
echo "<h2>User List:</h2>";
$query = mysql_query("SELECT * FROM users WHERE id != '" . $_SESSION["id"] . "'");
while($row = mysql_fetch_array($query)) {
  $alreadyFriend = false;
  $friends = unserialize($row["friends"]);
  if(isset($friends[0])) {
    foreach($friends as $friend) {
      if($friend == $_SESSION["id"]) $alreadyFriend = true;
    }
  }
  echo $row["email"];
  $_query = mysql_query("SELECT * FROM friend_requests WHERE sender = '" . $_SESSION["id"] . "' AND recipient = '" . $row["id"] . "'");
  if(mysql_num_rows($_query) > 0) {
    echo " - Friendship requested.";
    }
  elseif($alreadyFriend == false) {
    echo " - <a href=\"" . $_SERVER["PHP_SELF"] . "?add=" . $row["id"] . "\">Add as friend</a>";
    } 
  else {
    echo " - Already friends.";
    }
    echo "";
}
//END OF MEMBERLIST
//START OF FRIENDLIST
echo "<h2>Friend List:</h2>";
$query = mysql_query("SELECT friends FROM users WHERE id = '" . $_SESSION["id"] . "'");
while($row = mysql_fetch_array($query)) {
$friends = unserialize($row["friends"]);
if(isset($friends[0])) {
  foreach($friends as $friend) {
    $_query = mysql_query("SELECT username FROM users WHERE id = '" . $friend . "'");
    $_row = mysql_fetch_array($_query);
    echo $_row["email"] . "";
    }
  }
}
//END OF FRIENDLIST
}
*/
header("Location:home.php");
?>
