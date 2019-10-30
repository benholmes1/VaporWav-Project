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

      if(isset($_POST["taglist"])){
        $taglist = $_POST['taglist'];
        $tagArray = explode(",",$taglist);
      }
      //Add category
      $catlist = array("Digital Art","Traditional Art","Photography","Comics","Collage","Drawing","Painting","Landscape","Sculpture","Typography","3D Art","Photomanipulation","Pixel Art","Text Art","Vector","Fan Art");
      $catIDlist = [];
      $incatlist = [];
      if(isset($_POST["cat0"])){
        array_push($incatlist,$catlist[0]);
        array_push($catIDlist, 0);
      }
      if(isset($_POST["cat1"])){
        array_push($incatlist,$catlist[1]);
        array_push($catIDlist, 1);
      }
      if(isset($_POST["cat2"])){
        array_push($incatlist,$catlist[2]);
        array_push($catIDlist, 2);
      }
      if(isset($_POST["cat3"])){
        array_push($incatlist,$catlist[3]);
        array_push($catIDlist, 3);
      }
      if(isset($_POST["cat4"])){
        array_push($incatlist,$catlist[4]);
        array_push($catIDlist, 4);
      }
      if(isset($_POST["cat5"])){
        array_push($incatlist,$catlist[5]);
        array_push($catIDlist, 5);
      }
      if(isset($_POST["cat6"])){
        array_push($incatlist,$catlist[6]);
        array_push($catIDlist, 6);
      }
      if(isset($_POST["cat7"])){
        array_push($incatlist,$catlist[7]);
        array_push($catIDlist, 7);
      }
      if(isset($_POST["cat8"])){
        array_push($incatlist,$catlist[8]);
        array_push($catIDlist, 8);
      }
      if(isset($_POST["cat9"])){
        array_push($incatlist,$catlist[9]);
        array_push($catIDlist, 9);
      }
      if(isset($_POST["cat10"])){
        array_push($incatlist,$catlist[10]);
        array_push($catIDlist, 10);
      }
      if(isset($_POST["cat11"])){
        array_push($incatlist,$catlist[11]);
        array_push($catIDlist, 11);
      }
      if(isset($_POST["cat12"])){
        array_push($incatlist,$catlist[12]);
        array_push($catIDlist, 12);
      }
      if(isset($_POST["cat13"])){
        array_push($incatlist,$catlist[13]);
        array_push($catIDlist, 13);
      }
      if(isset($_POST["cat14"])){
        array_push($incatlist,$catlist[14]);
        array_push($catIDlist, 14);
      }
      if(isset($_POST["cat15"])){
        array_push($incatlist,$catlist[15]);
        array_push($catIDlist, 15);
      }

      $pathInS3 = 'https://s3.us-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;
    
      //Select all the keynames from the database
      $keyQuery = $selectKeyname_Images;
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
      $query = $insertImages;
      $queryRes = $conn->query($query);
      //$message = "Success!";

      //Insert Tags into database
      $tagconcat = "('".$keyNoPrefix."','".$tagArray[0]."')";
      $tagquery = "INSERT INTO tags (`keyname`,`tag`) VALUES ".$tagconcat;
      for ($x = 1; $x < count($tagArray); $x++){
        //$tagquery = $tagquery.", ('".$keyNoPrefix."', '".$tagArray[$x]."')";
        if(substr_compare($tagArray[$x], " ", 0, 1) == 0){//if first character in tag is a whitespace
          $tagquery = $tagquery.", ('".$keyNoPrefix."', '".substr($tagArray[$x],1)."')";
        }
        else{
          $tagquery = $tagquery.", ('".$keyNoPrefix."', '".$tagArray[$x]."')";
        }
      }
      $tagqueryRes = $conn->query($tagquery);

      //Inserting categories into database
      $catconcat = "('".$catIDlist[0]."','".$keyNoPrefix."','".$incatlist[0]."')";
      $catquery = "INSERT INTO categories (`category_id`, `keyname`, `category_name`) VALUES ".$catconcat;
      for($i = 1; $i < count($incatlist); $i++){
        $catquery = $catquery.", ('".$catIDlist[i]."', '".$keyNoPrefix."', '".$incatlist[0]."')";
      }
      $catqueryRes = $conn->query($catquery);
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
