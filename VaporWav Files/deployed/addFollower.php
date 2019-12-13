<?php
include 'dbconn.php';
include 'queries.php';

//echo"<script type='text/javascript'>alert('works');</script>";

//START OF Follow
if($_GET["follow"]) {

	$msg = "You will now be shown new content on your feed!";
	wordwrap($msg,70);
	mail($_GET['fEmail'],"The user ".$_SESSION['nickname']." is following you!",$msg);

	$qryNotific = "INSERT INTO `notifications`(`userEmail`, `message`) VALUES ('".$_GET['fEmail']."', '".$_SESSION['nickname']." is following you!')";
	$qryNotificRes = $conn->query($qryNotific);

    $insertUser_Follower = "INSERT INTO `followers`(`user`, `follower`) VALUES ('".$_GET["follow"]."', '".$_SESSION["userData"]["id"]."')";
    $result = $conn->query($insertUser_Follower);
}
header("Location:home.php");
$msg = "You will now be shown new content on your feed!";
wordwrap($msg,70);
mail($_GET['fEmail'],"The user ".$_SESSION['nickname']." is following you!",$msg);
?>