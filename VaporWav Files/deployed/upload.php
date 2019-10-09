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

  //If the file was chosen
  if(isset($_FILES['imgFile'])) {

    //Check the extension of the file to see if it is an image
    $ext_error = false;
    $extensions = array('jpg', 'jpeg', 'png');
    $file_ext = explode('.', $_FILES['imgFile']['name']);
    $file_ext = end($file_ext);
    $file_ext = strtolower($file_ext);

    //If the image is not an image set the error to true
    if(!in_array($file_ext, $extensions)) {
      $ext_error = true;
    }

    //If the file is an image proceed with upload
    if($ext_error == false) {

      if(isset($_POST["desc"])) {
	$description = $_POST['desc'];
        $descriptionTag = 'Description=' . $_POST['desc'];
      }

      $pathInS3 = 'https://s3.us-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;
    
      //Select all the keynames from the database
      $keyQuery = "SELECT keyname from images";
      $keyRes = $conn->query($keyQuery);

      if($keyRes->num_rows === 0){
        $nKey = md5(uniqid(rand(), true));
      }
      else {
        //This checks to make sure the keyname is unique
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
     }

     //Create the keyname without the prefix and with the prefix
     $keyNoPrefix = $nKey . '_' . basename($_FILES["imgFile"]['name']);
     $keyNoPrefix = str_replace("+", "", $keyNoPrefix);
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
 
      $tag = str_replace('"', '', $eTag);
 
      //Insert image information into the database
      $query = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`, `likes`) VALUES ('".$_SESSION['userData']['id']."', '".$tag."', '".$keyNoPrefix."', '".$_POST['title']."', '".$description."', CURDATE(), '0')";
      $queryRes = $conn->query($query);
      $message = "Success!";
      //Fetch the results of the SQL query and email each of the respective emails
      $queryF = "SELECT * from friends WHERE user ='" .$_SESSION["userData"]["id"]. "'";

      if ($resultF = $conn->query($queryF)) {
        while ($rowF = $resultF->fetch_assoc()) {
            $friendsQuery = "SELECT nickname, email, picture FROM users u INNER join usernames n on u.id = n.id WHERE u.id = '" . $rowF["friend"] . "'";
            if($friendsRes = $conn->query($friendsQuery)) {
                $friendsRow = $friendsRes->fetch_assoc();
                $msg = "Come check it out http://ec2-52-53-194-4.us-west-1.compute.amazonaws.com/searchPage.php?searchQ=".$_SESSION['userData']['email'];
                wordwrap($msg,70);
                mail($friendsRow['email'],"Your Friend ".$_SESSION['nickname']." posted something new!",$msg);
                echo '<h2>Hello</h2>';
            }
            }
        }
    }
    else {
      $message = "Please upload an image file.";
    }
  }

  //Redirect back to the upload page
  header("Location: uploadPage.php?msg=$message");

?>
