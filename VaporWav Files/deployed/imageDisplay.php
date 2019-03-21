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

  $query = "'SELECT * FROM `images` WHERE `etag` = '\"".id."\"'";
  $queryRes = $conn->query($query);
  $imageinfo = $queryRes->fetch_assoc();
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
    <main>
    <article>
    <section>
    <h2>Recent Upload</h2>
    <div class="bordThis">
    <figure>
    <a href="<?php echo $signed_url ?>"><img src="<?php echo $signed_url ?>" alt = "rickmortyvp" title= "rickmortyvp"/></a>
        <figcaption>My take on a favorite show of mine in my favorite asthetic</figcaption>
    </figure>
     <p>Been working on this for a while, want to see what other people think of it</p>
    <p>Username, Date</p>
    </div>
    </section>
 
    
</body>
</html>
