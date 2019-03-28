<?php

//This page will display the user's account information

include_once 'header.php';

if($_SESSION['login'])
{
  //Html to render if the user is logged in
  $output  = '<h2 style="font-family:Alien Encounters;size:150%">Account Details</h2>';
  $output .= '<div class="ac-data">';
  $output .= '<img src="'.$_SESSION['userData']['picture'].'" style="padding-top:10px;padding-bottom:10px">';
  $output .= '<div class="acct">';
  $output .= '<p style="font-family:Tinos"><b>Username:</b> '.$_SESSION['nickname'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
  $output .= '<p><a href = "account_change.php">Edit</a></p>';
  $output .= '</div>';
  $output .= '</div>';
}
else {
  header('Location: index.php');
  exit();
}
?>
<div class="container">
  <div class="wrapper">
    <!-- Display profile information -->
    <?php echo $output; ?>
  </div>
</div>
</body>
</html>
