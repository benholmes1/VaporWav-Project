<?php

session_start();

include 'dbconn.php';
include 'queries.php';
include 'galleryClass.php';

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

$client = new GalleryClass();
$client->set($bucketName, $s3, $conn);

$errorFlag = 0;

if(isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $errorFlag = 1;
}

if($action == "createGallery" && $errorFlag == 0) {
    $createGalFlag = 0;

    if(isset($_POST['galName'])) {
        $galName = $_POST['galName'];
    } else {
        $createGalFlag = 1;
    }

    if($createGalFlag == 0) {
        $result = $client->createGallery($galName);
        echo $result;
    } else {
        echo "Failed";
    }
} else if($action == "deleteGallery" && $errorFlag == 0) {
    $deleteGalFlag = 0;

    if(isset($_POST['prefix'])) {
        $prefix = $_POST['prefix'];
    } else {
        $deleteGalFlag = 1;
    }
    if(isset($_POST['gal'])) {
        $gal = $_POST['gal'];
    } else {
        $deleteGalFlag = 1;
    }

    if($deleteGalFlag == 0) {
        $result = $client->deleteGallery($prefix, $gal);
        echo $result;
    } else {
        echo "Failed";
    }
}

?>