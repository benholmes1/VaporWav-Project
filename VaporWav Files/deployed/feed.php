<?php
//This page displays the user's gallery
include 'header.php';
require_once 's3Access.php';	

//Check if user is logged in, if not redirect to index
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
}

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
$topQuery = "SELECT email, keyname, likes FROM images i
            INNER JOIN users u ON i.id = u.id
            WHERE private = '0'
            AND i.created BETWEEN date_sub(now(), INTERVAL 1 WEEK) AND now()
            ORDER BY likes desc";
$topResult = $conn->query($topQuery);

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
        
        $s3Client = new S3Access();
        $url = $s3Client->get($region, $bucket, $key);
        
        //Display each image as a link to the image display page 
        echo '<div class="mb-3">';
        echo '<a href="imageDisplay.php?key='.$key.'&exp=true"><img class="img-fluid" src="'.$url.'"></a>';
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
  $friendQuery = "SELECT u.email, i.keyname from images i inner join friends f on i.id = f.friend inner join users u on f.friend = u.id where f.user = '".$_SESSION['userData']['id']."' order by i.created";
  $friendRes = $conn->query($friendQuery);
  if($friendRes->num_rows == 0){
    echo '<p>No Results</p>';
  } else {
    while($friendRow = $friendRes->fetch_assoc()) {
      $friendKey = $friendRow['email'] . "/" . $friendRow['keyname'];
      $s3Client = new S3Access();
      $fr_signed_url = $s3Client->get($region, $bucket, $friendKey);
      
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
