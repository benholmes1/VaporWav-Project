<?php
  include 'dbconfig.php';
  session_start();
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
  }

  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }

  $expire = "1 hour";

  date_default_timezone_set("UTC");
  require './vendor/autoload.php';
  include 'config.php';

  if(isset($_GET['key'])) {
    $key = $_GET['key'];
  }
  if(isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  $s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
  ]);

  $cmd = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
    'Key'    => $key,
    'IfMatch' => $id
  ]);//
 
  $request = $s3->createPresignedRequest($cmd, "+{$expire}");
  $signed_url = (string) $request->getUri();   
  
  //get IDs
  $query = "SELECT * FROM images WHERE etag = '".$id."'";
  $queryRes = $conn->query($query);
  $imageinfo = $queryRes->fetch_assoc();
  
  //get user nickname
  $mail = current(explode('/',$key));
  //echo "<script type='text/javascript'>alert('$mail');</script>";

  $query0 = "SELECT nickname FROM users u INNER JOIN usernames n on u.id = n.id where email = '".$mail."'";
  $queryRes0 = $conn->query($query0);
  $userinfo = $queryRes0->fetch_assoc();

  //get created time and date
  $query1 = "SELECT created FROM images WHERE etag = '".$id."'";
  $queryRes1 = $conn->query($query1);
  $uploadDate = $queryRes1->fetch_assoc();
?>

<!doctype html>
<html>
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VaporWav - Share your art</title>
    <link rel="stylesheet" href="stylesFinal.css">
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
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
    </header>
    <main id="imageSolo">
    <article>
    <section>
    <h2><?php echo $imageinfo['title'] ?></h2>
    <div class="bordThis">
    <figure>
    <img src="<?php echo $signed_url ?>">
        <figcaption><?php echo $imageinfo['caption'] ?></figcaption>
    </figure>
    <p>Created by: <?php echo $userinfo['nickname'] ?></p>
    <p>Uploaded on: <?php echo $uploadDate['created'] ?></p>
    </div>
    </section>
 
    
</body>
</html>
