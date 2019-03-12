<?php
/*
 * Basic Site Settings and API Configuration
 */

// Database configuration
define('DB_HOST', 'vaporwav-db-instance.cnfuipg6elma.us-west-1.rds.amazonaws.com');
define('DB_USERNAME', 'vaporwav_user');
define('DB_PASSWORD', 'hR5#ath7!f5');
define('DB_NAME', 'vaporwavDB');
define('DB_USER_TBL', 'users');

// Google API configuration
define('GOOGLE_CLIENT_ID', '1096971026332-kmettdih5hqt6ktbem10oatu4f197q1j.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'Ry3KrhSac9moM2EgTi86EZwW');
define('GOOGLE_REDIRECT_URL', 'http://ec2-52-53-194-4.us-west-1.compute.amazonaws.com/index.php');

// Start session
if(!session_id()){
    session_start();
}

// Include Google API client library
require_once 'google-api-php-client/Google_Client.php';
require_once 'google-api-php-client/contrib/Google_Oauth2Service.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('VaporWav Google Login');
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
