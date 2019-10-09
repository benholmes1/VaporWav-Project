<?php
include 'dbconn.php';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
  die("Failed to connect with MySQL: " . $conn->connect_error);
}


// get from jquery
if(isset($_POST['key'])) {   //keyname
    $keyname = $_POST['key'];
}
if(isset($_POST['tag'])){ //tag
    $tag = $_POST['tag'];
}  
if(isset($_POST['fullKey'])) {
    $fullKey = $_POST['fullKey'];
}

$tagquery = "INSERT INTO tags(`keyname`,`tag`) VALUES ('".$keyname."','".$tag."')";
$tagqueryRes = $conn->query($tagquery);

if($tagqueryRes) {
    $message = "Success";
} else {
    $message = "Fail";
}

?>