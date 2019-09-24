<?php

    class S3 {

        private $s3;
        public $signed_url;

        public function __construct() {
            //Start a new AWS S3Client, specify region
            $s3 = new Aws\S3\S3Client([
                'version' => '2006-03-01',
                'region'  => $region,
            ]);
        }

        public function getClient() {
            return $s3;
        }

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
                return $this->$signed_url;
            } catch (S3Exception $e) {
                echo $e->getMessage();
                echo "\n";
            }
        }
    }

?>