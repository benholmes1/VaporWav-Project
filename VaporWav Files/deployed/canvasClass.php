<?php

session_start();

//This is the upload action that uploads an image to S3 and updates database
//include 'dbconn.php';
//include 'queries.php';
//Check if user is logged in
if(!($_SESSION['login'])){
  header('Location: index.php');
  exit();
}

class CanvasClass {

  function upload($s3, $image, $title, $desc, $taglist) {
    try {
      // Uploaded:
      $result = $s3->putObject(
        array(
            'Bucket'=>$bucketName,
            'Key' =>  "thisisatest.png",
            'Body' => $imageToUpload,
        )
      );
    } catch (S3Exception $e) {
      die('Error:' . $e->getMessage());
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

}
//This is needed to use AWS SDK for PHP
//require './vendor/autoload.php';

//S3Client for use in upload
//use Aws\S3\S3Client;
//use Aws\S3\Exception\S3Exception;

// AWS Info
//$bucketName = BUCKET_NAME;
//$IAM_KEY = ACCESS_KEY;
//$IAM_SECRET = SECRET_KEY;

// Connect to AWS
/*try {
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

if(isset($_POST['image'])) {
    $image = $_POST['image'];
    $imageToUpload = base64_decode($image);
}

try {
    // Uploaded:
    $result = $s3->putObject(
      array(
          'Bucket'=>$bucketName,
          'Key' =>  "thisisatest.png",
          'Body' => $imageToUpload,
      )
    );

    $eTag = $result['ETag'];
      $vID = $result['VersionId'];
  } catch (S3Exception $e) {
    die('Error:' . $e->getMessage());
  } catch (Exception $e) {
    die('Error:' . $e->getMessage());
  }*/