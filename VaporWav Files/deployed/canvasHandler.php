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

$canvasUploadClient = new CanvasClass();
$canvasUploadClient->set($bucketName, $s3, $conn);

$errorFlag = 0;

if(isset($_POST['function'])) {
    $function = $_POST['function'];
} else {
    $errorFlag = 1;
}

if($function == "upload" && $errorFlag == 0) {
    $uploadFlag = 0;

    if(isset($_POST['image'])) {
        $image = $_POST['image'];
        $imageToUpload = base64_decode($image);
    } else {
        $uploadFlag = 1;
    }

    if(isset($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $uploadFlag = 1;
    }

    if(isset($_POST['desc'])) {
        $desc = $_POST['desc'];
    } else {
        $uploadFlag = 1;
    }

    if(isset($_POST['taglist'])) {
        $taglist = $_POST['taglist'];
    } else {
        $uploadFlag = 1;
    }

    if($uploadFlag == 0) {
        $originalKey = $title . '.png';
        $keyCheckClient = new S3Access();
        $keyNoPrefix = $keyCheckClient->generateKey($conn, $selectKeyname_Images, $originalKey);
        $keyname = $_SESSION['userData']['email'] . '/' . $keyNoPrefix;
    
        $tag = $canvasUploadClient->upload($imageToUpload, $keyname);
        $insertCanvas = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`, `likes`) VALUES ('".$_SESSION['userData']['id']."', NULL, '".$keyNoPrefix."', '".$title."', '".$desc."', CURDATE(), '0')";
        $canvasUploadClient->insertImage($title, $desc, $taglist, $insertCanvas, $_SESSION['userData']['id'], $tag);
    } else {
        echo "Upload Failed";
    }

} else if($function == "save" && $errorFlag == 0) {
    $saveFlag = 0;

    if(isset($_POST['snapshot'])) {
        $snapshot = $_POST['snapshot'];
    } else {
        $saveFlag = 1;
    }

    if(isset($_POST['canvasTitle'])) {
        $canvasTitle = $_POST['canvasTitle'];
    } else {
        $saveFlag = 1;
    }

    if($saveFlag == 0) {
        if(isset($_POST['canvasKey'])) {
            $snapKeyNoPrefix = $_POST['canvasKey'];
        } else {
            $originalSnapKey = $canvasTitle;
            $keyCheckClient = new S3Access();
            $snapKeyNoPrefix = $keyCheckClient->generateKey($conn, $selectKeyname_Images, $originalSnapKey);
        }
        $snapKey = $_SESSION['userData']['email'] . '/' . 'Saves/' . $snapKeyNoPrefix;

        $snapTag = $canvasUploadClient->upload($snapshot, $snapKey);
        if(!isset($_POST['canvasKey'])) {
            $insertCanvasSave = "INSERT INTO `saves`(`user_id`, `title`, `save_keyname`) VALUES ('".$_SESSION['userData']['id']."', '".$canvasTitle."', '".$snapKeyNoPrefix."')";
            $canvasUploadClient->insertSave($insertCanvasSave);
        }
        echo $snapKeyNoPrefix;
    } else {
        echo "Save Failed";
    }

} else if($function == "load" && $errorFlag == 0) {
    $loadFlag = 0;

    if(isset($_POST['canvasKey'])) {
        $canvasKey = $_POST['canvasKey'];
    } else {
        $loadFlag = 1;
    }
    
    if($loadFlag == 0) {
        $loadKeyname = $_SESSION['userData']['email'] . '/' . 'Saves/' . $canvasKey;
        $loadObject = $canvasUploadClient->getSave($loadKeyname);
        echo $loadObject;
    } else {
        echo "Load Failed";
    }
} else {
    echo "Fail";
}
