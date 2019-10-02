<?php
include 'dbconn.php';
include 'queries.php';

//echo"<script type='text/javascript'>alert('works');</script>";

//START OF ADD FRIEND
if($_GET["add"]) {
    $_query = $selectAll_FriendRequests_SessionData;
    if($result = $conn->query($_query))
	{
	 //echo"<script type='text/javascript'>alert('$result->num_rows');</script>";
	if($result->num_rows == 0) {
	  $query1 = $insertUsers_FriendRequests;
	  if($result = $conn->query($query1)) {
	
	   // echo"<script type='text/javascript'>alert('works');</script>";
	  }
	}
    }
}
header("Location:home.php");
?>
