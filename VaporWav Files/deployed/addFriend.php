<?php
include 'dbconn.php';

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
header("Location:home.php");
?>
