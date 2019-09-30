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
    }

?>