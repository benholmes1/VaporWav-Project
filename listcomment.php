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

$commlquery = "SELECT * FROM comments ORDER BY created asc";
$commlqueryRes = $conn->query(commlquery);

$record_set = array();
while ($row = $commlqueryRes->fetch_assoc()) {
    array_push($record_set, $row);
}
$commlqueryRes->free_result();

$conn->close();
echo json_encode($record_set);
