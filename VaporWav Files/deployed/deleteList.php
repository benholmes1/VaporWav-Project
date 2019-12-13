<?php
  //This is the upload action that uploads an image to S3 and updates database
  include 'dbconn.php';	
  include 'queries.php';

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

  require_once 'User.class.php';

  //This is needed to use AWS SDK for PHP
  require './vendor/autoload.php';
 
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

  if(isset($_GET['prefix'])) {
    $prefix = $_GET['prefix'];
  }
  if(isset($_GET['list'])) {
      $list = $_GET['list'];
  }

  $iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $prefix, 'Delimiter' => $del));
  $objects = [];
  foreach($iterator as $o) {
      $objects[] = array("Key" => $o['Key']);
  }

  if(!empty($objects)) {
    try {
        $result = $s3->DeleteObjects(
        array(
            'Bucket'=>$bucketName,
            'Delete' =>  [
                'Objects' => $objects,
            ],
        )
        );
    } catch (S3Exception $e) {
        die('Error:' . $e->getMessage());
    } catch (Exception $e) {
        die('Error:' . $e->getMessage());
    }
  }

  $id = $_SESSION['userData']['id'];

  $delQuery = "DELETE FROM `lists` where `user_id` = '".$id."' and `list` = '".$list."'";
  $result = $conn->query($delQuery);
  if($result) {
    $message = "Success";
  }
  else {
    $message = "Something went wrong.";
  }

  $user = new User();
  $lists = $user->getLists($_SESSION['userData']['id']);
  $_SESSION['lists'] = $lists;

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
