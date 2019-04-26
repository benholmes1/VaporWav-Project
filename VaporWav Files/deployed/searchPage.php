<?php
session_start();
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}

$expire = "1 hour";

date_default_timezone_set("UTC");
require './vendor/autoload.php';
include 'config.php';
include 'dbconfig.php';
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
      <form action="searchUser.php" method="get">
	<input type="text" name="searchQ" placeholder="Search...">
	<button type="submit">Submit</button>
      </form>
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>



<main class="container2">
<h2>Your Result(s)</h2
<br>
    <section class="cards">
<?php

$email = $_SESSION['userData']['email'];

$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$bucket_url = "https://s3-{$region}.amazonaws.com/{$bucket}";

$sEmail = $_GET['searchQ'];
$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $sEmail));

//Friend feature

if ($sEmail!="" && $email != $sEmail)
{ 
	$qry1 = "SELECT id FROM users WHERE email = '" .$sEmail."'";
	$friendR = $conn->query($qry1);
	$friendID = $friendR->fetch_assoc();
	//echo"<script type='text/javascript'>alert('$friendID["id"]');</script>";

	$friends = $conn->query("SELECT * FROM friends WHERE user = '"  .$_SESSION['userData']['id']. "' AND friend ='" . $friendID["id"] ."'");
	//echo"<script type='text/javascript'>alert($friends);</script>";

	if ($friends->num_row == 0)
	{
	  $query0 = "SELECT id from users WHERE email ='" .$sEmail. "'";
	  if($result = $conn->query($query0))
	  {
	    $row = $result->fetch_assoc();
	  }
	$add='<form action = "addFriend.php" method ="get"><input type ="hidden" name="add" value='.$row['id'].'></input> <button type="submit">Add Friend</button></form>';
	}
}
else
{

}

//$images = array();
$Cnt = 0;

foreach ($iterator as $object) {
    $key = $object['Key'];
    $id = $object['ETag'];
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key'    => $key,
    ]);

    $res = gettype($cmd);
    //echo "<script type='text/javascript'>alert('$res');</script>";

    $request = $s3->createPresignedRequest($cmd, "+{$expire}");
    $signed_url = (string) $request->getUri();
    $etag = str_replace('"','',$id);
    //$images[] = new ImageObject($signed_url, $id);
    /*$imgObj->setUrl($signed_url);
    $imgObj->setId($id);*/
    //$imgObj = new ImageObject($signed_url, $id);

    //echo("<tr><td>$key</td><td><a href=\"{$bucket_url}/{$key}\">Direct</a></td><td><a href=\"{$signed_url}\">Expires in $expire</a></td></tr>");
    $Cnt += 1;
    //this is the new one, not sure if it works
    //ideally you use this in a for loop that grabs each signed url and prints it out this through this echos
   //echo("<article class='card'><a href=""><figure><img src=\"{$signed_url}\"></figure></a></article>");
    // echo "<script type='text/javascript'>alert('$pleaseHelp');</script>";
	 echo '<article class="card"><a href="imageDisplay.php?key='.$key.'&id='.$etag.'"><figure><img src="'.$signed_url.'"</figure></a></article>';

}
if($Cnt == 0) {
	echo '<p>No results found.</p>';
}

/*foreach ($images as $object) {
  $url = $object->getUrl();
  $message = "wrong answer";
  echo "<script type='text/javascript'>alert('$message');</script>";
  echo("<article class='card'><a href=\"testDisplay.php\"><figure><img src=\"{$url}\"></figure></a></article>");
}*/
?>
<br>
<?php echo $add; ?>
    </section>
</main>
</body>
</html>
