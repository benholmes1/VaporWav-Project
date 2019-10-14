<?php

    class S3Check {
        public function checkExists($region, $bucket, $key, $IAM_KEY, $IAM_SECRET) {
          
            try {
                $s3 = new S3Client([
                    'region'      => $region,
                    'version'     => '2006-03-01',
                    'credentials' => [
                        'key' => $IAM_KEY,
                        'secret' => $IAM_KEY
                    ]
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
        }
    }
?>