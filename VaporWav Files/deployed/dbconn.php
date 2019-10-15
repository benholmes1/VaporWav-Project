<?php
session_start();
//ob_start();
include 'dbconfig.php';
include 'config.php';

$dbHost     = DB_HOST;
$dbUsername = DB_USERNAME;
$dbPassword = DB_PASSWORD;
$dbName     = DB_NAME;
   
// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
}

?>