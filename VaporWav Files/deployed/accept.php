<?php
include 'dbconn.php';
include 'queries.php';
//START OF ACCEPT FRIEND

if($_GET["accept"]) {
//echo"<script type='text/javascript'>alert('a');</script>";

  $query = $selectAcceptFriend;
  if($result = $conn->query($query))
  {
   //echo"<script type='text/javascript'>alert('b');</script>";
    if($result->num_rows == 0){
       $conn->query($insertUser_Friend);
       $conn->query($insertSelf_Friend);
	echo"<script type='text/javascript'>alert('success');</script>";
    }
  $conn->query($deleteUsers_FriendRequests);
}
//echo"<script type='text/javascript'>alert('success');</script>";

}
header("Location:home.php");

?>
