<?php
include 'dbconn.php';
include 'queries.php';

//echo"<script type='text/javascript'>alert('works');</script>";

//START OF ADD FRIEND
if($_GET["bID"]) {

    $blockQ = "INSERT INTO `blocked`(`owner`, `blocked_user`) VALUES ('".$_SESSION['userData']['id']."', '".$_GET[bID]."')";
    if($result = $conn->query($blockQ))
	{

	   // echo"<script type='text/javascript'>alert('works');</script>";
	}
    }

    header("Location:home.php");
?>
