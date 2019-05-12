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

<main role="main">
    <div class="container">
        <div class="jumbotron">
            <h2 class="jumbotron-heading">Your Result(s)</h2>
        </div>
    </div>
    <div class="container container-small">
        <div class="wrapacct">
            <div class="acct">
            <?php
            if ($result = $conn->query($query0)) {
                if($result->num_rows == 0) {
                    echo '<p style="color:white;font-family:Streamster;font-size:1.4em">No Results Found</p>';
                } else {
                    /* fetch associative array */
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href = 'searchPage.php?searchQ=" . $row["email"] . "'>" . $row["nickname"] . "</a>";
                        echo "<br>";
                    }
                }
                /* free result set */
                $result->free();
            }
            ?>

            </div>
        </div>
    </div>
</main>
</body>
</html>