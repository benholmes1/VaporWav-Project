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
include 'dbconfig.php';
include 'chromephp/ChromePhp.php';

$dbHost     = DB_HOST;
$dbUsername = DB_USERNAME;
$dbPassword = DB_PASSWORD;
$dbName     = DB_NAME;
 
// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
  die("Failed to connect with MySQL: " . $conn->connect_error);
}

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
      <a href = "uploadPage.php">Upload</a>
      <a href = "galleries.php">Galleries</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>

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
        
        //Clean up the etag
        $etag = str_replace('"', '', $id); 
    
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
  $friendQuery = "SELECT u.email from users u inner join friends f on u.id = f.friend where f.user = '".$_SESSION['userData']['id']."'"; 
  $friendRes = $conn->query($friendQuery);
  while($friendRow = $friendRes->fetch_assoc()) {
  }

?>
</section>
</main>
</body>
</html>
