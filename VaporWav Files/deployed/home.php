<?php

//This page displays the user's gallery

session_start();

//Check if user is logged in, if not redirect to index
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
}

//This is the expire time for the image link
$expire = "1 hour";

//Requires
date_default_timezone_set("UTC");
require './vendor/autoload.php';
include 'config.php';

?>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VaporWav - Share your art</title>
    <link rel="stylesheet" href="stylesJ.css">
</head>
<body>
  <header>
    <div class="padThis">
      <h1>VaporWav</h1>
      <p class="underHeader">Show us what you have been working on.</p>
      <form action="searchPage.php" method="get">
	<input type="text" name="searchQ" placeholder="Search...">
	<button type="submit">Submit</button>
      </form>
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a></li>
      <li>
      <div class="dropdown">
        <a href="galleries.php" class="dropL">Galleries</a>
        <div class="dropdown-content">
          <a href="home.php">Your Gallery</a>
          <?php
            foreach($_SESSION['galleries'] as $gal) {
              echo '<a href="home.php?gal='.$gal.'">'.$gal.'</a>';
            }
          ?>
        </div>
      </div>
      </li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>

<main class="container2">
<h2>Your Gallery</h2>
<br>
    <section class="cards">
<?php

//User's email address
$email = $_SESSION['userData']['email'];

//Start a new AWS S3Client, specify region
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

//Get iterator for user's folder in S3 to get all images
$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $email));

//Iterate over each image to display them
foreach ($iterator as $object) {
    //Get the images key (filename), and etag
    $key = $object['Key'];
    $id = $object['ETag'];
    //This command gets the image from S3 as presigned url
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key'    => $key,
    ]);

    //Create the presigned url, specify expire time declared earlier
    $request = $s3->createPresignedRequest($cmd, "+{$expire}");
    //Get the actual url
    $signed_url = (string) $request->getUri();
    
    //Clean up the etag
    $etag = str_replace('"', '', $id); 
   
    //Display each image as a link to the image display page 
    echo '<article class="card"><a href="imageDisplay.php?key='.$key.'&id='.$etag.'"><figure><img src="'.$signed_url.'"</figure></a></article>';
}
?>
    </section>
</main>
</body>
</html>
