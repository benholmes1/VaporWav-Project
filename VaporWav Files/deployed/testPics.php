<?php
# By Michael Ludvig - https://aws.nz

$expire = "1 minute";

date_default_timezone_set("UTC");
require './vendor/autoload.php';
include 'config.php';
?>
<html>
<head>
<title>Pre-signed URL demo</title>
</head>
<body>
<h1>AWS S3 Pre-signed URL demo</h1>
<p>Upload files and images to S3 Bucket <b><a href="https://console.aws.amazon.com/s3/home?region=<?=$region?>&amp;bucket=<?=$bucket?>&amp;prefix=" target="_blank"><?=$bucket?></a></b> to test the signed and unsigned access.</p>
<table border='1'>
<tr><td><b>Object</b></td><td><b>Unsigned URL</b></td><td><b>Signed URL</b></td></tr>
<?php
$s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
]);

$bucket_url = "https://s3-{$region}.amazonaws.com/{$bucket}";

$iterator = $s3->getIterator('ListObjects', array('Bucket' => $bucket));

foreach ($iterator as $object) {
    $key = $object['Key'];
    print_r($object);
    $cmd = $s3->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key'    => $key,
    ]);

    $res = gettype($cmd);
    echo "<script type='text/javascript'>alert('$res');</script>";

    $request = $s3->createPresignedRequest($cmd, "+{$expire}");
    $signed_url = (string) $request->getUri();

    echo("<tr><td>$key</td><td><a href=\"{$bucket_url}/{$key}\">Direct</a></td><td><a href=\"{$signed_url}\">Expires in $expire</a></td></tr>");
}
?>
</table>
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
