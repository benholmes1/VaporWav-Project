<?php
  //This is the upload action that uploads an image to S3 and updates database
  include 'dbconn.php';
  include 'queries.php';

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
    $imgGalQuery = $selectAll_ImageGalleries;
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
      $delGalQuery = $deleteImageGallery;
      $delGalRes = $conn->query($delGalQuery);
      if($delGalRes) {
        $message = "Success";
      }
      else {
        $message = "Something went wrong.";
      }
    }

    $delCommQuery = $deleteImageComments;
    $commRes = $conn->query($delCommQuery);
    if($commRes) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }

    $delLikeQuery = $deleteImageLikes;
    $likeRes = $conn->query($delLikeQuery);
    if($likeRes) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }

    $delTagQuery = $deleteImageTags;
    $tagRes = $conn->query($delTagQuery);
    if($tagRes) {
      $message = "Success";
    }
    else{
      $message = "Something went wrong.";

    $delQuery = $deleteImage;
    $result = $conn->query($delQuery);
    if($result) {
      $message = "Success";
    }
    else {
      $message = "Something went wrong.";
    }
  } else {
    $delSingleGal = $deleteImage_Gallery;
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
