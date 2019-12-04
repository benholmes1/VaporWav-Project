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

  public function upload($content, $keyname) {
    try {
      // Uploaded:
      $result = $this->s3->putObject(
        array(
            'Bucket'=>$this->bucket,
            'Key' =>  $keyname,
            'Body' => $content,
        )
      );
      $eTag = $result['ETag'];
    } catch (S3Exception $e) {
      die('Error:' . $e->getMessage());
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }

    $tag = str_replace('"', '', $eTag);
    return $tag;
  }

  public function insertImage($title, $desc, $taglist, $query, $id, $tag) {
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_STRICT;

    try {
      $insertQueryResult = $this->conn->query($query);
      if($insertQueryResult) {
        echo "Success";
      } else {
        echo "Fail";
      }
    } catch (mysqli_sql_exception $e) {
      echo $e->__toString();
    }
  }

  public function insertSave($query) {
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_STRICT;

    try {
      $saveQueryResult = $this->conn->query($query);
      if(!($saveQueryResult)) {
        echo "Fail";
      }
    } catch (mysqli_sql_exception $e) {
      echo $e->__toString();
    }
  }

  public function getSave($keyname) {
    try {
      // Uploaded:
      $result = $this->s3->getObject(
        array(
            'Bucket'=>$this->bucket,
            'Key' =>  $keyname,
        )
      );
    } catch (S3Exception $e) {
      die('Error:' . $e->getMessage());
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }

    return $result['Body'];
  }

  public function insertCategories($keyname, $categories) {
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_STRICT;

    try {
      $catconcat = "('".$keyname."','".$categories[0]."')";
      $catquery = "INSERT INTO categories (`keyname`, `category_name`) VALUES ".$catconcat;
      for($i = 1; $i < count($categories); $i++)
      {
        $catquery = $catquery.", ('".$keyname."', '".$categories[$i]."')";
      }
      $catqueryRes = $this->conn->query($catquery);
      if(!($catqueryRes)) {
        echo "Fail";
      }
    } catch (mysqli_sql_exception $e) {
      echo $e->__toString();
    }
  }

}