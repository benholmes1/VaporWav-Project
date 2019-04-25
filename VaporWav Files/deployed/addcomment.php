<?php

session_start();

include "dbconfig.php";
$dbHost     = DB_HOST;
$dbUsername = DB_USERNAME;
$dbPassword = DB_PASSWORD;
$dbName     = DB_NAME;

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
  die("Failed to connect with MySQL: " . $conn->connect_error);
}

//$commentID = isset($_POST['comment_id']) ? $_POST['comment_id'] : "";
//get ids
if(isset($_POST['key'])) {   //keyname
    $keyname = $_POST['key'];
}
$comment = isset($_POST['comment']) ? $_POST['comment'] : "";
$date = date('Y-m-d H:i:s');

$commentquery = "INSERT INTO comments(image_id,`user_id`,comment,createddate) VALUES ('".$keyname."','".$_SESSION['userData']['id']."','".$comment."','".$date."')";
$commentqueryRes = $conn->query($commentquery);

if ($commentqueryRes) {
    $commentqueryRes = mysqli_error($conn);
}
echo $commentqueryRes;
?>