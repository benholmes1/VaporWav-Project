<?php
//This page displays the user's gallery
include 'header.php';	

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
<main class="container2">
  <h2>Your Feed</h2>
  <p>Trending</p>
<?php
echo '<br>';
echo '<section class="cards">';

$topQuery = "SELECT email, keyname, likes FROM images i
            INNER JOIN users u ON i.id = u.id
            WHERE private = '0'
            AND i.created BETWEEN date_sub(now(), INTERVAL 1 WEEK) AND now()
            ORDER BY likes desc";
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
        echo '<article class="card"><a href="imageDisplay.php?key='.$key.'&exp=true"><figure><img src="'.$signed_url.'"</figure></a></article>';
    }
}
?>
    </section>
    <a href="explore.php">See More</a>

    <p>Friend's Uploads</p>

<?php
  echo '<br>';
  echo '<section class="cards">';
  $friendQuery = "SELECT u.email, i.keyname from images i inner join friends f on i.id = f.friend inner join users u on f.friend = u.id where f.user = '".$_SESSION['userData']['id']."' order by i.created";
  $friendRes = $conn->query($friendQuery);
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
    echo '<article class="card"><a href="imageDisplay.php?key='.$friendKey.'&exp=true"><figure><img src="'.$fr_signed_url.'"</figure></a></article>';

  }

?>
</section>
</main>
</body>
</html>
