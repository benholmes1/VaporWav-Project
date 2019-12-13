<?php
    include "User.class.php";
    include "dbconn.php";
    
    $loginArray = array();
    $loginArray['oauth_provider'] = "VaporWav";
    $loginArray['email'] = $_POST['email'];

    $user = new User();
    $result = $user->loginUser($loginArray);
    if(!empty($result)){
        // Getting user profile info
        $gpUserData = array();
        $gpUserData['oauth_uid']  = !empty($result['id'])?$result['id']:'';
        $gpUserData['first_name'] = !empty($result['given_name'])?$result['given_name']:'';
        $gpUserData['last_name']  = !empty($result['family_name'])?$result['family_name']:'';
        $gpUserData['email'] 	  = !empty($result['email'])?$result['email']:'';
        $gpUserData['gender'] 	  = !empty($result['gender'])?$result['gender']:'';
        $gpUserData['locale'] 	  = !empty($result['locale'])?$result['locale']:'';
        $gpUserData['picture'] 	  = !empty($result['picture'])?$result['picture']:'';
        $gpUserData['link'] 	  = !empty($result['link'])?$result['link']:'';
            
        //Populate User Data
        $userData = $user->checkUser($gpUserData);
        // Storing user data in the session
        $_SESSION['userData'] = $userData;
            
        //$nickname = current(explode('@', $_SESSION['userData']['email']));
            
        $nickname = $user->checkName($userData);
        $_SESSION['nickname'] = $nickname;
            
        $galleries = $user->getGalleries($_SESSION['userData']['id']);
        $_SESSION['galleries'] = $galleries;
            
        $private = $user->getPrivacy($_SESSION['userData']['id']);
        $_SESSION['private'] = $private;
            
        $_SESSION['login'] = true;
        header('Location: home.php');
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
        header('Location: index.php');
    }
   
?>
