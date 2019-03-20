<?php
# By Michael Ludvig - https://aws.nz

$expire = "1 hour";

date_default_timezone_set("UTC");
require './vendor/autoload.php';
include 'config.php';
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
    <section class="cards">
<?php
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$bucket_url = "https://s3-{$region}.amazonaws.com/{$bucket}";

$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket));

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

    //echo("<tr><td>$key</td><td><a href=\"{$bucket_url}/{$key}\">Direct</a></td><td><a href=\"{$signed_url}\">Expires in $expire</a></td></tr>");
    
    //this is the new one, not sure if it works
    //ideally you use this in a for loop that grabs each signed url and prints it out this through this echos
    echo("<article class='card'><a href=\"{$signed_url}\"><figure><img src=\"{$signed_url}\"></figure></a></article>");
}
?>
    </section>
    </main>
<p>
<ul>
<li>If you don't see any objects in the table above either your IAM Policy is incorrect or you have no files in <b><a href="https://console.aws.amazon.com/s3/home?region=<?=$region?>&amp;bucket=<?=$bucket?>&amp;prefix=" target="_blank"><?=$bucket?></a></b> bucket.</li>
<li>The unsigned URLs may return an error depending on the ACL of the file.</li>
<li>The signed URLs expire in <b><?=$expire ?></b>. Reload the page to refresh the URLs.</li>
</ul>
</p>
<p>Source code is available here: <a href="https://github.com/mludvig/aws-s3sign-demo">https://github.com/mludvig/aws-s3sign-demo</a>.</p>
<address>By <a href="https://aws.nz/">Michael Ludvig</a></address>
</body>
</html>
