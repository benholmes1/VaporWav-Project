<?php
include 'dbconn.php';
include 'queries.php';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
  die("Failed to connect with MySQL: " . $conn->connect_error);
}


// get from jquery
if(isset($_POST['key'])) {   //keyname
    $keyname = $_POST['key'];
}
if(isset($_POST['comment'])){ //comment
    $comment = $_POST['comment'];
}  
if(isset($_POST['fullKey'])) {
    $fullKey = $_POST['fullKey'];
}

$commentquery = $insertComment_Comments;
$commentqueryRes = $conn->query($commentquery);

if($commentqueryRes) {
    $message = "Success";
} else {
    $message = "Fail";
}

header("Location: imageDisplay.php?key=".$fullKey."#commentSection");

?>