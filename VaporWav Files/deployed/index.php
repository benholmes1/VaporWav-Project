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

	$lists = $user->getLists($_SESSION['userData']['id']);
	$_SESSION['lists'] = $lists;

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
        $outputh .= '<link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAMMOAADDDgAAAAAA
AAAAAABqITH/aiEx/20iM/9zIjb/gCQ1/5MkSf+aK0v/oSxO/50yVf+nIln/kyNK/4AkNf9zIjX/
bSEz/2ohMf9qITH/aiEx/20hM/93IzP/iygz/6smUv+7Hqz/jk18/8AohP+dQpr/rTuO/8gUrf+o
KFT/jCc0/3cjM/9tITP/aiEx/20hM/92IzT/lyRE/98Xwf/8CfT/yDDQ/6lFnP/5Dez/8w3t/6lE
uP/IMKj//Av4/+EYwv+XJEX/diMz/20iM/9zIjT/jSY5/8Mvc//WK6L/2ied/5dRl//COY3/1img
/9cnm//INJr/m1SQ/9Iuo/+rRqr/yi94/5AlOv9yIjX/fCIy/7YyZP/oSqv/4kea/9NaqP+NWoP/
40Sf/+ZInP/mTKL/3FKn/5llm/+Ra5n/j1V6/9xUsv+lOGL/gCEz/48mPP/CN2X/0Tdu/7pHef93
TnD/nzhq/9I+eP+uSXr/nUBt/4lYhf98NlT/gC9P/4Q5YP+HSHb/uD1t/5IlPP+lLEr/6kyL/9tL
if/nWJr/ZENT/6wyZf+nYI7/kUp2/55Yg/9zQ2L/dCE5/24bKv+GKkz/0kKA//JLjv+lLEv/pytL
/9E7cf+JTG3/okJx/3ZEYf9yMU7/YSo//3YgSP+WToX/bjVT/2wkPP9fFCj/izFY/8Q/eP/POG7/
qCxN/7A6Rv/TfGL/mVhZ/2A4Qv9dHDP/Vxko/2oeLv+fXmX/1Xpi/59za/9aMUv/f0ti/39HY//X
cWH/5X1c/607Sf+oNUP/7JZ0/31WXf9iK0X/YDtU/14xUf9mKDr/kkdg/9+BfP+8eGv/vl5f//GR
eP/Fe27/4H9y//ySbP+nOEb/jSA+/8ZkRP/MgTr/i3ta/0okNP9iWmn/Yi5P/8dwSv/TgDr/44c+
/+OMQP/bgzv/4oY9/9+KPP/JZEL/jiE+/3oaOv+6XTf//80l/42BSv92O0v/r4Za/4NbUf+yckz/
+Lwq/+mvK//rsCr/7bEr/+msKv/1wCf/v2I3/3sZOv9zJTP/hxg//8Z2O//m0C7/578o/+u/IP/s
xib/4bsq/+K5KP/iuij/4bgn/+G2J//rziv/yHk7/4cYP/9zJTP/bCIy/3YiNv+XLzr/zoRG/+nO
aP/nzWn/48Nj/+XCYP/kw2H/5cVi/+nPaP/q0Gn/zYZI/5gwOv93ITb/bCIy/2ohMf9sIzL/dR81
/4keQf+3YWT/0peC/+PAmv/ozKH/6Myh/+PAm//SmIL/uGNk/4ofQf91HzX/bSMy/2ohMf9qITH/
aiEx/2wiMv9zIzP/ehYw/4sdOf+jP0//sVlf/7FZX/+jQE//jB05/3oVMP9zIzP/bCMy/2ohMf9q
ITH/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAA==" rel="icon" type="image/x-icon" />';
        $outputh .= '</header>';

	// Render google login button
	$output = '
	<div class="container-fluid container-large h-100">
		<div class="row align-items-center h-100">
			<div class="col mx-auto">
				<section class="jumbotron text-center">
					<div class="container">
						<h1 class="display-4">VaporWav</h1>
						<p>Show us what you have been working on.</p>
						<p class="lead">
							<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/google-sign-in-btn.png" alt="" class="img-fluid"/></a>
						</p>
					</div>
				</section>
			</div>
		</div>
	</div>';
	//$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/google-sign-in-btn.png" alt="" class="center" style="width:300px"/></a>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>VaporWav - Share your art</title>
	
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="stylesFinal.css">
</head>
<body>

	<?php echo $output; ?>

</body>
</html>
