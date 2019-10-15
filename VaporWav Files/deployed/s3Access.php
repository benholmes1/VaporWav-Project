<?php

    class S3Access {
        public function get($region, $bucket, $key) {
            $expire = "1 hour";

            $s3 = new Aws\S3\S3Client([
                'version' => '2006-03-01',
                'region'  => $region,
            ]);

            try {
                $cmd = $s3->getCommand('GetObject', [
                    'Bucket' => $bucket,
                    'Key'    => $key,
                ]);
            } catch (S3Exception $e) {
                echo $e->getMessage();
                echo "\n";
            }
        
            try {
                //Create the presigned url, specify expire time declared earlier
                $request = $s3->createPresignedRequest($cmd, "+{$expire}");
                //Get the actual url
                $signed_url = (string) $request->getUri();
            } catch (S3Exception $e) {
                echo $e->getMessage();
                echo "\n";
            }
            return $signed_url;
        }
<<<<<<< HEAD

        /*public function checkExists($region, $bucket, $key, $IAM_KEY, $IAM_SECRET) {
            $expire = "1 hour";

            // Connect to AWS
            try {
                $credentials = new Aws\Credentials\Credentials($IAM_KEY, $IAM_SECRET);
                $s3 = new Aws\S3\S3Client([
                    'version' => '2006-03-01',
                    'region'  => $region,
                    'credentials' => $credentials
                ]);
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }

            try {
                $check = $s3->headObject([
                    'Bucket' => $bucket,
                    'Key' => $key,
                ]);
                if($check) {
                    $checkKey = 1;
                }
                else {
                    $checkKey = 0;
                }
            }
            catch (S3Exception $e) {
                echo $e->getMessage();
                echo "\n";
            }
            return $checkKey;
        }*/
=======
>>>>>>> ErrorHandling
    }

?>