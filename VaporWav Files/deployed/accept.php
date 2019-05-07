<?php
include 'dbconn.php';
//START OF ACCEPT FRIEND

if($_GET["accept"]) {
//echo"<script type='text/javascript'>alert('a');</script>";

  $query ="SELECT * FROM friends WHERE user = '"  .$_SESSION["userData"]["id"].  "' AND friend = '" .$_GET["accept"]. "'";
  if($result = $conn->query($query))
  {
   //echo"<script type='text/javascript'>alert('b');</script>";
    if($result->num_rows == 0){
       $conn->query("INSERT INTO friends SET user = '" .$_GET["accept"]. "', friend = '" .$_SESSION["userData"]["id"] . "'");
       $conn->query("INSERT INTO friends SET user = '" .$_SESSION["userData"]["id"]. "', friend = '" .$_GET["accept"]. "'");
	echo"<script type='text/javascript'>alert('success');</script>";
    }
  $conn->query("DELETE FROM friend_requests WHERE sender = '" . $_GET["accept"] . "' AND recipient = '" . $_SESSION["userData"]["id"] . "'");
}
//echo"<script type='text/javascript'>alert('success');</script>";

}
header("Location:home.php");

?>
