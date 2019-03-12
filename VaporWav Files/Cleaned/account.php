<?php

session_start();

//if(!empty($userData)){
  $output	 = '<h2 style="font-family:Alien Encounters;size:150%">Account Details</h2>';
	$output .= '<div class="ac-data">';
	$output .= '<img src="'.$_SESSION['userData']['picture'].'" style="padding-  top:10px;padding-bottom:10px">';
  $output .= '<div class="acct">';
	$output .= '<p style="font-family:Tinos"><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
	$output .= '</div>';
	$output .= '</div>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>VaporWav Account</title>
<link rel="stylesheet" href="stylesFinal.css"/>
</head>
<body>
<header>
  <div class="padThis">
  <h1>VaporWav</h1>
  <p class="underHeader">Show us what you have been working on.</p>
  </div>
  <nav>
  <!list of the seperate parts of this page>
  <ul>
    <li><a href = "index.php">Home</a></li>
  </ul>
  <ul class="leftHead">
    <li><a href = "account.php">My Account</a>
    <a href = "logout.php">Logout</a></li>
  </ul>
  </nav>
</header>

<div class="container">
	<div class="wrapper">
		<!-- Display login button / Google profile information -->
		<?php echo $output; ?>
	</div>
</div>
</body>
</html>
