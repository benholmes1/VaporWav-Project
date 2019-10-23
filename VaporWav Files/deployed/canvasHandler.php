<?php

session_start();

include 'dbconn.php';
include 'queries.php';
include 'canvasClass.php';
include 's3Access.php';

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

$errorFlag = 0;

if(isset($_POST['image'])) {
    $image = $_POST['image'];
    $imageToUpload = base64_decode($image);
} else {
    $errorFlag = 1;
}

if(isset($_POST['title'])) {
    $title = $_POST['title'];
} else {
    $errorFlag = 1;
}

if(isset($_POST['desc'])) {
    $desc = $_POST['desc'];
} else {
    $errorFlag = 1;
}

if(isset($_POST['taglist'])) {
    $taglist = $_POST['taglist'];
} else {
    $errorFlag = 1;
}

if($errorFlag == 0) {
    $originalKey = $title . '.png';
    $keyCheckClient = new S3Access();
    $keyNoPrefix = $keyCheckClient->generateKey($conn, $selectKeyname_Images, $originalKey);
    $keyname = $_SESSION['userData']['email'] . '/' . $keyNoPrefix;

    $canvasUploadClient = new CanvasClass();
    $canvasUploadClient->set($bucketName, $s3, $conn);
    $insertCanvas = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`, `likes`) VALUES ('".$_SESSION['userData']['id']."', NULL, '".$keyNoPrefix."', '".$title."', '".$desc."', CURDATE(), '0')";
    $canvasUploadClient->upload($imageToUpload, $keyname, $title, $desc, $taglist, $insertCanvas, $_SESSION['userData']['id']);
} else {
    echo "Upload Failed.";
}
