<?php

  //This page displays the image with information

  include 'dbconfig.php';
  include_once 'header_script.php';

  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }

  if(!(isset($_GET['key'])) && !(isset($_GET['id']))){
    header('Location: home.php');
    exit();
  }

  $key = $_GET['key'];
  $id = $_GET['id'];
 
  if(($key === '') || ($id === '')){
    header('Location: home.php');
    exit();
  }

  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }

  //Expiration time
  $expire = "1 hour";

  date_default_timezone_set("UTC");
  require './vendor/autoload.php';
  include 'config.php';
 
  //Get image again from S3
  $s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
  ]);

  $cmd = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
    'Key'    => $key,
    'IfMatch' => $id
  ]);//
 
  $request = $s3->createPresignedRequest($cmd, "+{$expire}");
  $signed_url = (string) $request->getUri();   
 
  $keyname = substr($key, strpos($key, "/") + 1);
 
  //get IDs
  $query = "SELECT * FROM images WHERE keyname = '".$keyname."'";
  $queryRes = $conn->query($query);
  $imageinfo = $queryRes->fetch_assoc();
  
  //get user nickname
  $mail = current(explode('/',$key));
  //echo "<script type='text/javascript'>alert('$keyname');</script>";

  $query0 = "SELECT nickname FROM users u INNER JOIN usernames n on u.id = n.id where email = '".$mail."'";
  $queryRes0 = $conn->query($query0);
  $userinfo = $queryRes0->fetch_assoc();

  $query1 = "SELECT created FROM images WHERE etag = '".$id."'";
  $queryRes1 = $conn->query($query1);
  $uploadDate = $queryRes1->fetch_assoc();

  $date = strtotime($uploadDate['created']);
  $formatDate = date("m/d/y", $date);
?>

    <main id="imageSolo">
    <article>
    <section>
    <h2><?php echo $imageinfo['title'] ?></h2>
    <div class="bordThis">
    <figure>
    <img src="<?php echo $signed_url ?>">
        <figcaption><?php echo $imageinfo['caption'] ?></figcaption>
    </figure>
    <p class="same-row">Created by: <?php echo $userinfo['nickname'] ?></p>
    <?php
      if($_SESSION['userData']['id'] === $imageinfo['id']) {
        $dropOut  = '<div class="same-row" style="float:right">';
        $dropOut .= '<button onclick="myFunction()" class="dropbtn">Options</button>';
        $dropOut .= '<div id="imgDropdown" class="dropdown-content">';
        $dropOut .= '  <a id="delete" href="deleteImage.php?key='.$key.'">Delete</a>';
        $dropOut .= '  <a href="#">Add To Gallery</a>';
        $dropOut .= '</div>';
        $dropOut .= '</div>';
        echo $dropOut;
      }
    ?>
    <p>Uploaded on: <?php echo $formatDate ?></p>
    </div>
    </section>
    <script>
    function myFunction() {
      document.getElementById("imgDropdown").classList.toggle("show");
    }

    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }

    $('#delete').on('click',function(e) {
      var answer=confirm('Are you sure you want to delete this image?');
      if(answer){
        alert('Deleted');
      }
      else{
        e.preventDefault();      
      }
    });
    </script>
    
</body>
</html>
