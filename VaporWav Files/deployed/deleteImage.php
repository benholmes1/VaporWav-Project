<?php
  //This is the upload action that uploads an image to S3 and updates database
  include 'dbconn.php';	 

  //Check if user is logged in
  if(!($_SESSION['login'])){
    header('Location: index.php');
    exit();
  }

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
  
  if(!isset($_GET['gal'])) {
    $imgGalQuery = "SELECT * FROM `image_galleries` WHERE `keyname` = '".$keyname."'";
    $imgGalRes = $conn->query($imgGalQuery);
    if($imgGalRes->num_rows != 0) {
      while($galRow = $imgGalRes->fetch_assoc()) {
        try {
          $result = $s3->DeleteObject(
            array(
              'Bucket'=>$bucketName,
              'Key' =>  $galRow['gallery'],
            )
          );
        } catch (S3Exception $e) {
          die('Error:' . $e->getMessage());
        } catch (Exception $e) {
          die('Error:' . $e->getMessage());
        }
      }
      $delGalQuery = "DELETE FROM `image_galleries` WHERE `keyname` = '".$keyname."'";
      $delGalRes = $conn->query($delGalQuery);
      if($delGalRes) {
        $message = "Success";
      }
      else {
        $message = "Something went wrong.";
      }
    }

    $delCommQuery = "DELETE FROM `comments` WHERE `image_id` = '".$keyname."'";
    $commRes = $conn->query($delCommQuery);
    if($commRes) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }

    $delLikeQuery = "DELETE FROM `likes` WHERE `keyname` = '".$keyname."'";
    $likeRes = $conn->query($delLikeQuery);
    if($likeRes) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }

    $delQuery = "DELETE FROM `images` where `keyname` = '".$keyname."'";
    $result = $conn->query($delQuery);
    if($result) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }
  } else {
    $delSingleGal = "DELETE FROM `image_galleries` WHERE `keyname` = '".$keyname."' AND gallery = '".$key."'";
    $delSingleRes = $conn->query($delSingleGal);
    if($delSingleRes) {
      $message = "Success";
    } else {
      $message = "Fail";
    }
  }

  //Redirect back to the upload page
  header("Location: home.php?msg=$message");

?>
