<?php
/*
 * User Class
 * This class is used for database related (connect, insert, and update) operations
 */

class User {
    private $dbHost     = DB_HOST;
    private $dbUsername = DB_USERNAME;
    private $dbPassword = DB_PASSWORD;
    private $dbName     = DB_NAME;
    private $userTbl    = DB_USER_TBL;
    private $userData;

    //This function connects to the database	
    function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
	
    //This function adds user to database if not already in it
    //Then it populates the user's session data
    function checkUser($userData = array()){
        if(!empty($userData)){
            // Check whether user data already exists in the database
            $checkQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
            $checkResult = $this->db->query($checkQuery);
            if($checkResult->num_rows > 0){
                // Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = NOW() WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
                $update = $this->db->query($query);
            }else{
                // Insert user data in the database
                $query = "INSERT INTO ".$this->userTbl." SET oauth_provider = '".$userData['oauth_provider']."', oauth_uid = '".$userData['oauth_uid']."', first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', created = NOW(), modified = NOW()";
                $insert = $this->db->query($query);
            }
 
            // Get user data from the database
            $result = $this->db->query($checkQuery);
            $userData = $result->fetch_assoc();
        }
        
        // Return user data
        return $userData;
    }

    //This function will set a user's nickname if not already set
    function checkName($userData = array()) {
      $checkQ = "SELECT * FROM usernames WHERE id = '".$userData['id']."'";  
      $checkR = $this->db->query($checkQ);
      if($checkR->num_rows <= 0) {
        $nickname = current(explode('@', $userData['email']));
	$nameQuery = "INSERT INTO usernames SET id = '".$userData['id']."', nickname = '".$nickname."'";
	$insert = $this->db->query($nameQuery);
      }
      $res = $this->db->query($checkQ);
      $nickname = $res->fetch_assoc();

      return $nickname["nickname"];
    }

    function getGalleries($userID) {
        $galQuery = "SELECT galleries FROM galleries WHERE user_id = '".$userID."'";
        $galRes = $this->db->query($galQuery);
        $galArray = [];
        if($galRes->num_rows > 0) {
            while($row = $galRes->fetch_assoc()) {
                $galArray[] = $row['galleries'];
            }
        }
        return $galArray;
    }

    function getPrivacy($userID) {
        $privateSetting = 0;
        $priQuery = "SELECT private FROM users where id = '".$userID."'";
        $priRes = $this->db->query($priQuery);
        $private = $priRes->fetch_assoc();
        if(is_null($private['private']))
        {
            $insertPri = "UPDATE users SET private = '1' WHERE id = '".$userID."'";
            $inRes = $this->db->query($insertPri);
            $privateSetting = 1;
        } else {
            $privateSetting = $private['private'];
        }
        return $privateSetting;
    }

    function getLists($userID) {
        $listQuery = "SELECT list FROM lists WHERE user_id = '".$userID."'";
        $listRes = $this->db->query($listQuery);
        $listArray = [];
        if($listRes->num_rows > 0) {
            while($row = $listRes->fetch_assoc()) {
                $listArray[] = $row['list'];
            }
        }
        return $listArray;
    }

}
