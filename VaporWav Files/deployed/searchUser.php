<?php
include 'header.php';
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}
$expire = "1 hour";
date_default_timezone_set("UTC");

$emailCompare = $_GET['searchQ'];
        
$query0 = "SELECT nickname, email FROM usernames u INNER join users n on u.id = n.id WHERE nickname LIKE '%".$emailCompare."%'";

$Cnt = 0;

?>

<main class="container2">
<h2>Your Result(s)</h2>
<br>
    <section class="cards">
<?php
if ($result = $conn->query($query0)) {
    global $Cnt;
    $Cnt = $Cnt + 1;
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        echo "<a href = 'searchPage.php?searchQ=" . $row["email"] . "'>" . $row["nickname"] . "</a>";
        echo "<br>";
    }

    /* free result set */
    $result->free();
}
if($Cnt == 0) {
	echo '<p>No results found.</p>';
}

?>
    </section>
</main>
</body>
</html>