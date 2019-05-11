<?php

//This page will display the user's account information

include_once 'header.php';

if($_SESSION['login'])
{
  if($_SESSION['private'] === '1')
  {
    $privacy = "Private";
  } else {
    $privacy = "Public";
  }
  //Html to render if the user is logged in
  $output  = '<div class="ac-data">';
  $output .= '<img style="display:block;margin:auto" class="img-fluid" src="'.$_SESSION['userData']['picture'].'" style="padding-top:10px;padding-bottom:10px"><br>';
  $output .= '<div class="acct">';
  $output .= '<p style="font-family:Tinos"><b>Username:</b> '.$_SESSION['nickname'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
  $output .= '<p style="font-family:Tinos"><b>Privacy Status:</b> '.$privacy.'</p>';
  $output .= '<p><a href = "account_change.php"><button class="btn">Edit</button></a></p>';
  $output .= '</div>';
  $output .= '</div>';
}
else {
  header('Location: index.php');
  exit();
}
?>
<main role="main">
<br>
<section class="jumbotron text-center" style="color:rebeccapurple">
  <div class="container">
    <h2 class="jumbotron-heading">Account Details</h2>
  </div>
</section>
<div class="container container-small">
  <div class="wrapacct">
    <!-- Display profile information -->
    <?php echo $output; ?>
  </div>
</div>
</main>
</body>
</html>
