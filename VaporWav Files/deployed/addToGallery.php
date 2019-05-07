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

  //S3Client for use in upload
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;
 
  // AWS Info
  $bucketName = BUCKET_NAME;
  $IAM_KEY = ACCESS_KEY;
  $IAM_SECRET = SECRET_KEY;
 
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

  if(isset($_GET['gal'])) {
    $gal = $_GET['gal'];
  }
  if(isset($_GET['key'])) {
    $key = $_GET['key'];
  }

  $src = $bucketName . '/';
  $src .= $key;
  $keyArr = explode('/', $key);
  $destKey = $keyArr[0] . '/' . $gal . '/' . $keyArr[1];

   // Add it to S3
   try {
     $result = $s3->copyObject(
       array(
        'Bucket' => $bucketName,
        'CopySource' => $src,
        'Key' =>  $destKey,
       )
     );
   } catch (S3Exception $e) {
     die('Error:' . $e->getMessage());
   } catch (Exception $e) {
     die('Error:' . $e->getMessage());
   }   

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
