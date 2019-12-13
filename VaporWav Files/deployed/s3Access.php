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

        public function generateKey($conn, $query, $originalKey) {
            $driver = new mysqli_driver();
            $driver->report_mode = MYSQLI_REPORT_STRICT;

            try {
                $keyRes = $conn->query($query);

                if($keyRes->num_rows === 0){
                    $nKey = md5(uniqid(rand(), true));
                } else {
                    //This checks to make sure the keyname is unique
                    $checkKey = True;
                    while($checkKey) {
                        $nKey = md5(uniqid(rand(), true));
                        $newKey = $nKey . '_' . $originalKey;
                        while($row = $keyRes->fetch_array(MYSQLI_ASSOC)) {
                            if($newKey == $row["keyname"]) {
                                $checkKey = True;
                            } else {
                                $checkKey = False;
                            }
                        }
                    }
                }
                $keyRes->close();
            } catch (mysqli_sql_exception $e) {
                echo $e->__toString();
            }
            return $newKey;
        }
    }

?>