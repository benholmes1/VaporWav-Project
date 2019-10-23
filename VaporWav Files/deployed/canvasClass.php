<?php

session_start();

if(!($_SESSION['login'])){
  header('Location: index.php');
  exit();
}

class CanvasClass {

  private $bucket;
  private $s3;
  private $conn;

  public function set($bucket, $s3, $conn) {
    $this->bucket = $bucket;
    $this->s3 = $s3;
    $this->conn = $conn;
  }

  public function upload($image, $keyname, $title, $desc, $taglist, $query, $id) {
    try {
      // Uploaded:
      $result = $this->s3->putObject(
        array(
            'Bucket'=>$this->bucket,
            'Key' =>  $keyname,
            'Body' => $image,
        )
      );
      $eTag = $result['ETag'];
    } catch (S3Exception $e) {
      die('Error:' . $e->getMessage());
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }

    $tag = str_replace('"', '', $eTag);

    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_STRICT;

    try {
      $insertQueryResult = $this->conn->query($query);
    } catch (mysqli_sql_exception $e) {
      echo $e->__toString();
    }
  }

}