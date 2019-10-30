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
          <br>
          <textarea class="form-control" placeholder="Add Tags separated by commas. . ." style="width:100%;box-sizing:border-box;resize:none" id="taglist" name="taglist" form="uploadForm"></textarea>
          
          <!--Categories-->
          <div class="checkbox">
            <label><input type="checkbox" id="cat0" name="cat0" value="Digital Art">Digital Art</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat1" name="cat1" value="Traditional Art">Traditional Art</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat2" name="cat2" value="Photography">Photography</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat3" name="cat3" value="Comics">Comics</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat4" name="cat4" value="Collage">Collage</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat5" name="cat5" value="Drawing">Drawing</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat6" name="cat6" value="Painting">Painting</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat7" name="cat7" value="Landscape">Landscape</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat8" name="cat8" value="Sculpture">Sculpture</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat9" name="cat9" value="Typography">Typography</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat10" name="cat10" value="3D Art">3D Art</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat11" name="cat11" value="Photomanipulation">Photomanipulation</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat12" name="cat12" value="Pixel Art">Pixel Art</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat13" name="cat13" value="Text Art">Text Art</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat14" name="cat14" value="Vector">Vector</label>
          </div>
          <div class="checkbox">
            <label><input type="checkbox" id="cat15" name="cat15" value="Fan Art">Fan Art</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
</body>
</html
