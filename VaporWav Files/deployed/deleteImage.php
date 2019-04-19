<?php

  //This is the upload action that uploads an image to S3 and updates database

  session_start(); 

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

  //This is needed to use AWS SDK for PHP
  require './vendor/autoload.php';
 
  //Include the database credentials
  include 'dbconfig.php';	
  include 'config.php';

  //S3Client for use in upload
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;
 
  // AWS Info
  $bucketName = BUCKET_NAME;
  $IAM_KEY = ACCESS_KEY;
  $IAM_SECRET = SECRET_KEY;
  
  //DB info
  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }
 
  // Connect to AWS
  try {
    $s3 = S3Client::factory(
      array(
        'credentials' => array(
          'key' => $IAM_KEY,
          'secret' => $IAM_SECRET
        ),
        'version' => 'latest',
        'region'  => 'us-west-1'
      )
    );
  } catch (Exception $e) {
    die("Error: " . $e->getMessage());
  }

  if(isset($_GET['key'])) {
    $key = $_GET['key'];
  }

  try {
    $result = $s3->DeleteObject(
      array(
        'Bucket'=>$bucketName,
        'Key' =>  $key,
      )
    );
  } catch (S3Exception $e) {
    die('Error:' . $e->getMessage());
  } catch (Exception $e) {
    die('Error:' . $e->getMessage());
  }

  $keyname = explode('/', $key);
  $keyname = end($keyname);

  $delQuery = "DELETE FROM `images` where `keyname` = '".$keyname."'";
  $result = $conn->query($delQuery);
  if($result) {
    $message = "Success";
  }
  else {
    $message = "Something went wrong.";
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
