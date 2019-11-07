<?php

  //This is the upload page where a user can upload an image
  include_once 'header.php';

  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }

  //Alert the success or fail message
  if(isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo "<script type='text/javascript'>alert('$msg');</script>"; 
    unset($_GET['msg']);
  }
?>
<main role="main">
<br>
<section class="jumbotron text-center" style="color:rebeccapurple">
  <div class="container">
    <h2 class="jumbotron-heading">Category List</h2>
  </div>

</main>
</body>
</html