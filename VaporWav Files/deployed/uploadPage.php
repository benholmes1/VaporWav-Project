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
  <div class="container">
    <div class="wrapacct">
      <h2>Upload Image</h2>
      <div id="form">
      <div class="acctform">
	<form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm" class="acctform">
          <input id="imgFile" name="imgFile" type="file" required>
	  <input id="upload" type="submit" value="Upload" style="float:right">
        </form>
        <textarea placeholder="Title . . ." style="width:100%;box-sizing:border-box;resize:none" id="title" name="title" form="uploadForm" required></textarea>
	<textarea placeholder="Description . . ." style="width:100%;height:5em;box-sizing:border-box;resize:none" id="desc" name="desc" form="uploadForm"></textarea>
      </div>
      </div>
    </div>
  </div>
</body>
</html
