<?php
include 'header.php';	
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}
$expire = "1 hour";
date_default_timezone_set("UTC");

$queryF = "SELECT * from friends WHERE user ='" .$_SESSION["userData"]["id"]. "'";


if ($resultF = $conn->query($queryF)) {
    while ($rowF = $resultF->fetch_assoc()) {
        $friendsQuery = "SELECT nickname, email, picture FROM users u INNER join usernames n on u.id = n.id WHERE u.id = '" . $rowF["friend"] . "'";
        if($friendsRes = $conn->query($friendsQuery)) {
            $friendsRow = $friendsRes->fetch_assoc();
            $msg = "Come check it out http://ec2-52-53-194-4.us-west-1.compute.amazonaws.com/searchPage.php?searchQ=".$_SESSION['userData']['email'];
            mail($friendsRow['email'],"Your Friend ".$_SESSION['nickname']." posted something new!",$msg);
        }
        }
    }


?>