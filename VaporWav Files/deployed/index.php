<?php

//This is the index page of the site
//If the user is logged in it will redirect to their home page
//Otherwise, it will display the login button and log them in

// Include configuration file
require_once 'google_config.php';

// Include User library file
require_once 'User.class.php';

//Authenticate the user with the Google Client and redirect to this page
if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

//Set the access token
if(isset($_SESSION['token'])){
	$gClient->setAccessToken($_SESSION['token']);
}

//If the access token is set
if($gClient->getAccessToken()){
	// Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	// Initialize User class
	$user = new User();
	
	// Getting user profile info
	$gpUserData = array();
	$gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:'';
	$gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
	$gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
	$gpUserData['email'] 	  = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
	$gpUserData['gender'] 	  = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
	$gpUserData['locale'] 	  = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
	$gpUserData['picture'] 	  = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';
	$gpUserData['link'] 	  = !empty($gpUserProfile['link'])?$gpUserProfile['link']:'';
	
	// Insert or update user data to the database
    $gpUserData['oauth_provider'] = 'google';
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
	
	// Render user profile data
    if(!empty($userData)){
        header('Location: home.php');
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
}else{ //If the user is not logged in
	// Get login url
	$authUrl = $gClient->createAuthUrl();

        //This header is rendered when the user is not logged in
        $outputh  = '<header>';
        $outputh .= '<div class="padThis">';
        $outputh .= '<h1>VaporWav</h1>';
        $outputh .= '<p class="underHeader">Show us what you have been working on.</p>';
        $outputh .= '</div>';
        $outputh .= '</header>';

	// Render google login button
	$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/google-sign-in-btn.png" alt="" class="center" style="width:300px"/></a>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VaporWav - Share your art</title>
    <link rel="stylesheet" href="stylesFinal.css">
</head>
<body>

<?php echo $outputh; ?>	

<div class="container">
    <?php echo $output; ?>
</div>

</body>
</html>
