<?php

    class S3Check {
        public function checkObjectExists($key, $conn) {
          $checkExistsQuery = "SELECT * FROM images WHERE keyname = '".$key."'";
          $checkExistsResult = $conn->query($checkExistsQuery);
          if($checkExistsResult->num_rows == 0) {
              $check = 0;
          } else {
              $check = 1;
          }
          return $check;
        }
    }
?>