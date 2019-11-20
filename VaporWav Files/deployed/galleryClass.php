<?php

session_start();

if(!($_SESSION['login'])){
  header('Location: index.php');
  exit();
}

class GalleryClass {
    private $bucket;
    private $s3;
    private $conn;

    public function set($bucket, $s3, $conn) {
        $this->bucket = $bucket;
        $this->s3 = $s3;
        $this->conn = $conn;
    }

    public function createGallery($galName) {
        $galCheck = $selectGalleries_SessionData;
        $galRes = $conn->query($galCheck);
        $check = FALSE;
        if($galRes->num_rows > 0) {
            while($row = $galRes->fetch_assoc()) {
            if($row['galleries'] === $galName) {
                $check = TRUE;
            }
            }
        }

        if($check == TRUE) {
            $message = "Sorry, you have already created a gallery with this name.";
        } else {
            $galQ = $insertUserGallery_Galleries;
            $result = $conn->query($galQ);
            if($result) {
                $_SESSION['galleries'][] = $galName;
                $message = "Success";
            } else {
                $message = "Something went wrong.";
            }
        }
        echo $message;
    }

    public function deleteGallery($prefix, $gal) {
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
      
        $delQuery = $deleteGalleries;
        $result = $conn->query($delQuery);
        if($result) {
          $message = "Success";
        }
        else {
          $message = "Something went wrong.";
        }
      
        $user = new User();
        $galleries = $user->getGalleries($_SESSION['userData']['id']);
        $_SESSION['galleries'] = $galleries;
        echo $message;
    }

    public function createList($listName) {
        $listCheck = "SELECT list FROM lists WHERE user_id = '".$_SESSION['userData']['id']."'";
        $listRes = $conn->query($listCheck);
        $check = FALSE;
        if($listRes->num_rows > 0) {
            while($row = $listRes->fetch_assoc()) {
            if($row['list'] === $listName) {
                $check = TRUE;
            }
            }
        }

        if($check == TRUE) {
            $message = "Sorry, you have already created a list with this name.";
        } else {
            $listQ = "INSERT INTO `lists`(`user_id`, `list`) VALUES ('".$_SESSION['userData']['id']."', '".$listName."')";
            $result = $conn->query($listQ);
            if($result) {
                $_SESSION['lists'][] = $listName;
                $message = "Success";
            } else {
                $message = "Something went wrong.";
            }
        }
        echo $message;
    }

    public function deleteList($prefix, $list) {
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
        echo $message;
    }

}

?>