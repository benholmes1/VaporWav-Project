<?php
  include 'header.php';
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }
?>

<?php
    	$msg = "Check it out on your friends list!";
        wordwrap($msg,70);
        mail($_POST['fEmail'],"The user ".$_SESSION['nickname']." sent you a friend request!",$msg);
?>
    <div class="containerContact">
    <form action = "contact_action.php" method = "POST">

        <label for="name">Name</label>
        <input type="text" id="name" name="firstname" placeholder="Your name..">

        <label for="email">Email</label>
        <input type="text" id="email" name="e-mail" placeholder="Your Email..">

        <label for="subject">Subject</label>
        <textarea id="subject" name="subject" placeholder="Write your issue." style="height:200px"></textarea>

        <input type="submit" value="Submit">

    </form>
    </div> 
 </html>