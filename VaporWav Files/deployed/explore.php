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

echo '<br>';
echo '<section class="cards">';

$topQuery = "SELECT email, keyname, likes FROM images i
            INNER JOIN users u ON i.id = u.id
            WHERE private = '0'
            ORDER BY likes desc";
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
        echo '<article class="card"><a href="imageDisplay.php?key='.$key.'&exp=true"><figure><img src="'.$signed_url.'"</figure></a></article>';
    }
}
?>
    </section>
</main>
</body>
</html>
