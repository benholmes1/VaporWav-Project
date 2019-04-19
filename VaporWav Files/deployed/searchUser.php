<?php
session_start();
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
}
$expire = "1 hour";
date_default_timezone_set("UTC");

include 'dbconfig.php';
include 'config.php';
$dbHost     = DB_HOST;
$dbUsername = DB_USERNAME;
$dbPassword = DB_PASSWORD;
$dbName     = DB_NAME;
   
// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
}
$emailCompare = $_GET['searchQ'];
        
$query0 = "SELECT nickname, email FROM usernames u INNER join users n on u.id = n.id WHERE nickname LIKE '%".$emailCompare."%'";

$Cnt = 0;

?>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VaporWav - Share your art</title>
    <link rel="stylesheet" href="stylesJ.css">
</head>
<body>
  <header>
    <div class="padThis">
      <h1>VaporWav</h1>
      <p class="underHeader">Show us what you have been working on.</p>
      <form action="searchUser.php" method="get">
	<input type="text" name="searchQ" placeholder="Search...">
	<button type="submit">Submit</button>
      </form>
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>



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