<?php
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

<?php
if(isset($_GET['gal']))
{
  echo '<h2 class="jumbotron-heading">'.$_GET['gal'].'</h2>';
} else {
  echo '<h2 class="jumbotron-heading">Your Uploads</h2>';
}

//User's email address
$email = $_SESSION['userData']['email'];
$prefix = $email . "/";
$del = '/';
if(isset($_GET['gal']))
{
  $prefix .= $_GET['gal'];
  $del = '';
  echo '<br>';
  echo '<a class="btn" style="background-color:#663399" href="deleteGallery.php?prefix='.$prefix.'&gal='.$_GET['gal'].'">Delete Gallery</a>';
} else {
  ?>
  <br>
  <button id="galBtn">Create A New Gallery</button>
    <div id="galModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
              <span class="close" style="margin-left:10px">&times;</span>
              <h2>Create a New Gallery</h2>
            </div>
            <div class="modal-body">
              <form action="create_gallery.php" method="post" id="galForm">
                  <label for="name" style="color:white">Enter the name of your gallery:</label>
                  <input type="text" id="name" name="name" required>
                  <button type="submit" class="subBtn">Submit</button>
              </form>
            </div>
        </div>
    </div>
    <?php
}

echo '</div>';
echo '</section>';
?>

<div class="container">
<div class="gallery" id="gallery">

<?php
//Start a new AWS S3Client, specify region
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

//Get iterator for user's folder in S3 to get all images
$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $prefix, 'Delimiter' => $del));

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
    echo '<div class="mb-3">';
    echo '<a href="imageDisplay.php?key='.$key.'&id='.$etag.'"><img class="img-fluid" src="'.$signed_url.'"></a>';
    echo '</div>';
}
?>
</div>
</div>
</main>

<script>
  // Get the modal
  var modal = document.getElementById('galModal');
  // Get the button that opens the modal
  var btn = document.getElementById("galBtn");
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];
  // When the user clicks the button, open the modal 
  btn.onclick = function() {
      modal.style.display = "block";
  }
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
      modal.style.display = "none";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  }
  $("#name").alphanum({
   allowSpace: true,
   allowNewline: false,
   allowOtherCharSets: false,
   allowNumeric: false
   });
</script>

</body>
</html>
