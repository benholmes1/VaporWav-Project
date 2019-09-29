<?php

    class S3Access {

        public function get($key) {
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

        //public function iterate($prefix, $del)
    }

?>