<?php
include 'header.php';
include 'queries.php';

if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}

$expire = "1 hour";

date_default_timezone_set("UTC");
require './vendor/autoload.php';
?>

<main role="main">
<br>
<section class="jumbotron text-center" style="color:rebeccapurple">
<div class="container">

<?php

$email = $_SESSION['userData']['email'];

$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$bucket_url = "https://s3-{$region}.amazonaws.com/{$bucket}";

$sEmail = $_GET['searchQ'];
$fEmail = $sEmail . "/";
$del = '/';

$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket, 'Prefix' => $fEmail, 'Delimiter' => $del));

$qry1 = $selectId_Users_Email;
$friendR = $conn->query($qry1);
$friendID = $friendR->fetch_assoc();

$getName = $selectFriendNickname_Usernames_Id;
$getNameRes = $conn->query($getName);
$getNameRow = $getNameRes->fetch_assoc();

echo '<h2 class="jumbotron-heading">' . $getNameRow['nickname'] . '\'s Gallery</h2>';

//Friend feature

if ($sEmail!="" && $email != $sEmail)
{ 
  $checkFriend = $selectFriends_User_UserFriend;
  $isFriend = $conn->query($checkFriend);
  $numRows = $isFriend->num_rows;
	//echo"<script type='text/javascript'>alert($numRows);</script>";

	if ($numRows == 0)
	{
        echo '<br>';
        echo '<form action = "addFriend.php" method ="get"><input type ="hidden" name="add" value='.$friendID['id'].'></input> <button class="btn" type="submit">Add Friend</button>';
        echo '<input id="fEmail" name="fEmail" type="hidden" value="' . $_GET["searchQ"] . '">';
        echo '</form>';
	}
}
else
{
  header('Location: index.php');
}

echo '</div>';
echo '</section>';
?>

<div class="container">
<div class="gallery" id="gallery">

<?php
//$images = array();
$Cnt = 0;

foreach ($iterator as $object) {
    $key = $object['Key'];
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key'    => $key,
    ]);

    $res = gettype($cmd);
    //echo "<script type='text/javascript'>alert('$res');</script>";

    $request = $s3->createPresignedRequest($cmd, "+{$expire}");
    $signed_url = (string) $request->getUri();
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
	echo '<div class="mb-3">';
    echo '<a href="imageDisplay.php?key='.$key.'&exp=true"><img class="img-fluid" src="'.$signed_url.'"></a>';
    echo '</div>';
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

</div>
</div>
</main>
</body>
</html>
