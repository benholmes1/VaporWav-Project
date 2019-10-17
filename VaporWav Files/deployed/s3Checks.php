<?php

    class S3Check {
        public function checkObjectExists($key, $conn) {
            $driver = new mysqli_driver();
            $driver->report_mode = MYSQLI_REPORT_ALL;

            try {
                $checkExistsQuery = "SELECT * FROM images WHERE keyname = '".$key."'";
                $checkExistsResult = $conn->query($checkExistsQuery);
                if($checkExistsResult->num_rows == 0) {
                    $check = 0;
                } else {
                    $check = 1;
                }
            } catch (mysqli_sql_exception $e) {
                echo $e->__toString();
            }
            return $check;
        }
    }
?>