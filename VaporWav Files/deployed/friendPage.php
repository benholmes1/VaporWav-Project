<?php
include 'header.php';	
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}
$expire = "1 hour";
date_default_timezone_set("UTC");

$emailCompare = $_GET['searchQ'];
        
$query0 = "SELECT recipient, sender FROM friend_requests WHERE recipient = '".$_SESSION["userData"]["id"]."'";
$queryF = "SELECT * from friends WHERE user ='" .$_SESSION["userData"]["id"]. "'";

$Cnt = 0;

?>
<main id="container3">
<h2> Your Friends</h2>
<section id="friends">
<?php
    if ($resultF = $conn->query($queryF)) {
    /* fetch associative array */
    while ($rowF = $resultF->fetch_assoc()) {
        $queryFF = "SELECT nickname FROM usernames where id = '" . $rowF["friend"] . "'";
        $queryTest = "SELECT email FROM usernames u INNER join users n on u.id = n.id WHERE u.id = '" . $rowF["friend"] . "'";
        $resultTest = $conn->query($queryTest);
        $rowTest = $resultTest->fetch_assoc();
        if($resultFF = $conn->query($queryFF)) {
            $rowFF = $resultFF->fetch_assoc();
            //echo "<p>" . $rowFF["nickname"] . ", </p>";
            echo "<a href = 'searchPage.php?searchQ=" . $rowTest["email"] . "'>" . $rowFF["nickname"] . "</a>";
            echo "<br>";
            //echo "<a href = 'searchPage.php?searchQ=" . $row["email"] . "'>" . $row["nickname"] . "</a>";
        }
        }
    }

    /* free result set */
    //$result->free();
?>
</section>
<h2>Your Friend Requests</h2>
<br>
    <section id = "requests">
<?php
if ($result = $conn->query($query0)) {
    while ($row = $result->fetch_assoc()) {
        $query1 = "SELECT nickname FROM usernames where id = '".$row["sender"]. "'";
        if($result1 = $conn->query($query1)) {
            $row1 = $result1->fetch_assoc();
            echo "<p>" . $row1["nickname"];
            echo '<form action = "accept.php" method="get"><input type="hidden" name="accept" value='.$row["sender"].'></input> <button type="submit">Accept</button></form></p>';
        }
        echo "<br>";
    }

    /* free result set */
    $result->free();
}

?>
    </section>
</main>
</body>
</html>