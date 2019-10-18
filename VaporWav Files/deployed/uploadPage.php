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
    <h2 class="jumbotron-heading">Upload Image</h2>
  </div>
</section>
  <div class="container">
    <div class="wrapacct">
      <div class="acctform">
        <form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm" class="acctform">
          <label class="btn btn-file">
            <input style="display:none" id="imgFile" name="imgFile" type="file" onchange="$('#upload-file-info').html(this.files[0].name)" required>
            Browse
          </label>
          <span class="label label-info" id="upload-file-info"></span>
	        <input class="btn" id="upload" type="submit" value="Upload" style="float:right">
        </form>
        <div class="form-group">
          <textarea class="form-control" placeholder="Title . . ." style="width:100%;box-sizing:border-box;resize:none" id="title" name="title" form="uploadForm" rows="1" required></textarea>
          <br>
          <textarea class="form-control" placeholder="Description . . ." style="width:100%;height:5em;box-sizing:border-box;resize:none" id="desc" name="desc" form="uploadForm"></textarea>
        </div>
      </div>
    </div>
  </div>
</main>
  <?php include 'footer.php';?>
</body>
</html
