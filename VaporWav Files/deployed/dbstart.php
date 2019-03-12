<?php 

// Database configuration
$dbHost     = "vaporwav-db-instance.cnfuipg6elma.us-west-1.rds.amazonaws.com";
$dbUsername = "vaporwav_user";
$dbPassword = "hR5#ath7!f5";
$dbName     = "vaporwavDB";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
