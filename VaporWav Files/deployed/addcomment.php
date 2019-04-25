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


// get from jquery
if(isset($_POST['key'])) {   //keyname
    $keyname = $_POST['key'];
}
if(isset($_POST['comment'])){ //comment
    $comment = $_POST['comment'];
}  


$date = date('Y-m-d H:i:s');

$commentquery = "INSERT INTO comments(image_id,`user_id`,comment,createddate) VALUES ('".$keyname."','".$_SESSION['userData']['id']."','".$comment."','".$date."')";
$commentqueryRes = $conn->query($commentquery);

if ($commentqueryRes) {
    $commentqueryRes = mysqli_error($conn);
}
echo $commentqueryRes;
?>