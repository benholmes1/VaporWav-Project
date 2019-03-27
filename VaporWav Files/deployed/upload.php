<?php
  session_start(); 

  if(!($_SESSION['login'])){
    header('Location: index.php');
  }
 
  /*if(isset($_POST['avatar'])) {
    if($_POST['avatar'] == 1) {
      $avatar = True;
      //echo "<script type='text/javascript'>alert('Here');</script>";
      unset($_POST['avatar']);
    }
  }*/

  require './vendor/autoload.php';
 
  include 'dbconfig.php';	

  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;
 
  // AWS Info
  $bucketName = BUCKET_NAME;
  $IAM_KEY = ACCESS_KEY;
  $IAM_SECRET = SECRET_KEY;
  
  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }
 
  /*if(!(isset($_POST['title']))) {
    $alrt = "Please fill out title field";
    echo "<script type='text/javascript'>alert('$alrt');</script>";
    header('Location: uploadPage.php');
    exit();
  }*/
  if("" == trim($_POST['title'])){
    $alrt = "Please fill out title field";
    echo "<script type='text/javascript'>alert('$alrt');</script>";
    header('Location: uploadPage.php');
  }      
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

  if(isset($_FILES['imgFile'])) {
    $ext_error = false;
    $extensions = array('jpg', 'jpeg', 'png');
    $file_ext = explode('.', $_FILES['imgFile']['name']);
    $file_ext = end($file_ext);
    $file_ext = strtolower($file_ext);

    if(!in_array($file_ext, $extensions)) {
      $ext_error = true;
    }
//echo "<script type='text/javascript'>alert('Here');</script>";

    if($ext_error == false) {

      if($avatar == True) {
        $description = $_SESSION['userData']['email'] . '\'s avatar.';
        $descriptionTag = 'Description=' . $description;
      }
      elseif(isset($_POST["desc"])) {
        //echo "<script type='text/javascript'>alert('Here');</script>";
	$description = $_POST['desc'];
        $descriptionTag = 'Description=' . $_POST['desc'];
      }
      else {
      
      }
	
      if($avatar == True) {
        $keyName = $_SESSION['userData']['email'] . '/avatar/' . basename($_FILES["imgFile"]['name']); 
      }  else {
        $keyName = $_SESSION['userData']['email'] . '/' . basename($_FILES["imgFile"]['name']);
      }
      $pathInS3 = 'https://s3.us-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;
  
      /*$fileCheck = md5_file($_FILES['imgFile']['tmp_name']); 
      $tagQuery = "SELECT id, etag from images";
      $tagRes = $conn->query($tagQuery);
      while($row = $tagRes->fetch_array(MYSQLI_ASSOC)) {
        if($fileCheck == $row["etag"]) {
          $exists = "Sorry, this file has already been uploaded.";
	  if($_SESSION['userData']['id'] == $row["id"]) {
	    $exists .= "You can edit this file by clicking on the image in your gallery.";
	  }
	  header("Location:home.php?err=".$exists);
	  exit();
	}
      }
      $tagRes->free();*/
  
      $keyQuery = "SELECT keyname from images";
      $keyRes = $conn->query($keyQuery);

      $checkKey = True;
      while($checkKey) {
        $nKey = md5(uniqid(rand(), true));
        while($row = $keyRes->fetch_array(MYSQLI_ASSOC)) {
          if($nKey == $row["keyname"]) {
	    $checkKey = True;
          } else {
            $checkKey = False;
          }
        }
     }

     //$keyName = $_SESSION['userData']['email'] . '/' . $nkey . );
     $keyNoPrefix = $nKey . '_' . basename($_FILES["imgFile"]['name']);
     $keyName = $_SESSION['userData']['email'] . '/' . $keyNoPrefix;
      // Add it to S3
      try {
        // Uploaded:
        $file = $_FILES["imgFile"]['tmp_name'];
        $result = $s3->putObject(
          array(
	    'Bucket'=>$bucketName,
	    'Key' =>  $keyName,
	    'SourceFile' => $file
          )
        );

        $eTag = $result['ETag'];
	$vID = $result['VersionId'];
      } catch (S3Exception $e) {
        die('Error:' . $e->getMessage());
      } catch (Exception $e) {
        die('Error:' . $e->getMessage());
      }

      //$fileCheck = md5_file($file);
      //echo "<script type='text/javascript'>alert('$fileCheck');</script>"; 
 
      $tag = str_replace('"', '', $eTag);
 
      $query = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`) VALUES ('".$_SESSION['userData']['id']."', '".$tag."', '".$keyNoPrefix."', '".$_POST['title']."', '".$description."', CURDATE())";
      $queryRes = $conn->query($query);
      $message = "Success!";
      //echo '<script type="text/javascript">alert("Success");</script>';
    }
    else {
      $message = "Please upload an image file.";
      //echo '<script type="text/javascript">alert("Please upload an image file.");</script>';           
      //header('Location: uploadPage.php?res');
    }
  }

  //$_SESSION['upResult'] = $res;
  header("Location: uploadPage.php?msg=$message");

?>
