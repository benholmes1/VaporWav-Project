<?php
include 'header.php';
include 'queries.php';

if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}
$expire = "1 hour";
date_default_timezone_set("UTC");


if(isset($_POST['action'])) {
    $msg = $_POST['message'];
	wordwrap($msg,70);
    mail($_POST['messEmail'],"The user ".$_SESSION['nickname']." sent you a message!",$msg);
    header('Location: index.php');
}else
?>
<main role="main">
    <div class="container">
    <br>
        <div class="jumbotron">
            <h2 class="jumbotron-heading">Send Message</h2>
            <?php echo '<p>Name: '.$_GET['messEmail'].'</p>'; ?>
        </div>
    </div>
    <textarea placeholder="Message . . ." style="width:100%;box-sizing:border-box;resize:none" id="comment" name="message" form="messageForm" required></textarea>
    <form method="post" id="messageForm" action="sendMessage.php">
    <?php echo '<input type ="hidden" name="messEmail" value='.$_GET['messEmail'].'></input>' ?>
    <?php echo '<input type ="hidden" name="action" value= 1></input>' ?>
    <input class="btn" id="upload" type="submit" style="float:right">
    </form>

<?php
function send()
{
    $msg = $_POST['message'];
	wordwrap($msg,70);
    mail($_GET['messEmail'],"The user ".$_SESSION['nickname']." sent you a message!",$msg);
    header('Location: index.php');
}
?>
</main>
</body>
</html>
