<?php

session_start();

if(!($_SESSION['login'])){
  header('Location: index.php');
  exit();
}

class CanvasClass {

  function upload($s3, $bucket, $image, $title, $desc, $taglist) {
    try {
      // Uploaded:
      $result = $s3->putObject(
        array(
            'Bucket'=>$bucket,
            'Key' =>  "thisisatest.png",
            'Body' => $image,
        )
      );
    } catch (S3Exception $e) {
      die('Error:' . $e->getMessage());
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

}