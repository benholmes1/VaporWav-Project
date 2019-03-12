<?php
// Include configuration file
require_once 'dbc.php';

// Include User library file
require_once 'User.class.php';

if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if(isset($_SESSION['token'])){
	$gClient->setAccessToken($_SESSION['token']);
}

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

	// Render user profile data
    if(!empty($userData)){
        $outputh  = '<header>';
        $outputh .= '<div class="padThis">';
        $outputh .= '<h1>VaporWav</h1>';
        $outputh .= '<p class="underHeader">Show us what you have been working on.</p>';
        $outputh .= '</div>';
 				$outputh .= '<nav>';
				$outputh .= '<!list of the seperate parts of this page>';
				$outputh .= '<ul>';
 				$outputh .= '<li><a href = "index.php">Home</a></li>';
 				$outputh .= '</ul>';
				$outputh .= '<ul class="leftHead">';
				$outputh .= '<li><a href = "account.php">My Account</a>';
				$outputh .= '<a href = "logout.php">Logout</a></li>';
				$outputh .= '</ul>';
				$outputh .= '</nav>';
        $outputh .= '</header>';

				$output  = '<p style="font-family:Streamster;font-size:350%" class="count">Coming Soon</p>';
				$output .= '<hr class="center">';
				$output .= '<p id="demo" class="count"></p>';
				$output .= '<script src="countdown.js"></script>';

    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
}else{
	// Get login url
	$authUrl = $gClient->createAuthUrl();

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
