<?php
//This page displays the user's gallery
include 'header.php';	
include 'queries.php';

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
include 'chromephp/ChromePhp.php';
?>

<main role="main">
  <br>
  <section class="jumbotron text-center" style="color:rebeccapurple">
    <div class="container">
      <h2 class="jumbotron-heading">Your Feed</h2>
    </div>
  </section>
  <br>
  <div class="container">
    <h2>Trending</h2>
    <br>
    <div class="gallery" id="gallery">
<?php
$topQuery = $selectImageDetails_Innerjoin_Organized;
$topResult = $conn->query($topQuery);

//Start a new AWS S3Client, specify region
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$numRows = $topResult->num_rows;
if($numRows >= 6) {
    $n = 6;
} else {
    $n = $numRows;
}

if($numRows == 0) {
    echo '<p>No Results</p>';
} else {
    //Iterate over each image to display them
    for($i = 0; $i < $n; $i++) {
        //Get the images key (filename)
        $image = $topResult->fetch_assoc();
        $key = $image['email'] . '/' . $image['keyname'];
        //This command gets the image from S3 as presigned url
        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key,
        ]);

        //Create the presigned url, specify expire time declared earlier
        $request = $s3->createPresignedRequest($cmd, "+{$expire}");
        //Get the actual url
        $signed_url = (string) $request->getUri();
        
        //Display each image as a link to the image display page 
        echo '<div class="mb-3">';
        echo '<a href="imageDisplay.php?key='.$key.'&exp=true"><img class="img-fluid" src="'.$signed_url.'"></a>';
        echo '</div>';
    }
}
?>
    </div>
  <a class="btn" style="background-color:#663399;color:white;font-family:Tinos" href="explore.php">See More</a>
  <br>
  <br>
  <h2>Friend's Uploads</h2>
    <br>
    <div class="gallery" id="gallery">
  
<?php
  $friendQuery = $selectFriendImages_Innerjoin_SessionData;
  $friendRes = $conn->query($friendQuery);
  if($friendRes->num_rows == 0){
    echo '<p>No Results</p>';
  } else {
    while($friendRow = $friendRes->fetch_assoc()) {
      $friendKey = $friendRow['email'] . "/" . $friendRow['keyname'];
      $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key'    => $friendKey,
      ]);

      //Create the presigned url, specify expire time declared earlier
      $fr_request = $s3->createPresignedRequest($cmd, "+{$expire}");
      //Get the actual url
      $fr_signed_url = (string) $fr_request->getUri();
      
      //Display each image as a link to the image display page 
      echo '<div class="mb-3">';
      echo '<a href="imageDisplay.php?key='.$friendKey.'&exp=true"><img class="img-fluid" src="'.$fr_signed_url.'"></a>';
      echo '</div>';
    }
  }
?>
</div>
</div>
</main>
</body>
</html>
