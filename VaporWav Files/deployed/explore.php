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
?>

<main role="main">
  <br>
  <section class="jumbotron text-center" style="color:rebeccapurple">
    <div class="container">
      <h2 class="jumbotron-heading">Explore</h2>
    </div>
  </section>
  <br>
  <div class="container">
    <div class="gallery" id="gallery">

<?php
$topQuery = "SELECT email, keyname, likes FROM images i
            INNER JOIN users u ON i.id = u.id
            WHERE private = '0'
            ORDER BY likes desc, i.created desc";
$topResult = $conn->query($topQuery);

//Start a new AWS S3Client, specify region
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$numRows = $topResult->num_rows;

if($numRows == 0) {
    echo '<p>No Results</p>';
} else {
    //Iterate over each image to display them
    while($image = $topResult->fetch_assoc()) {
        //Get the images key (filename)
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
        
        //Clean up the etag
        $etag = str_replace('"', '', $id); 
    
        //Display each image as a link to the image display page 
        echo '<div class="mb-3">';
        echo '<a href="imageDisplay.php?key='.$key.'&exp=true"><img class="img-fluid" src="'.$signed_url.'"></a>';
        echo '</div>';
    }
}
?>
</div>
</div>
</main>
</body>
</html>
