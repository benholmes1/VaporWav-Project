<?php
include 'header.php';
require_once 's3Access.php';

//Check if user is logged in, if not redirect to index
if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
}

//Requires
date_default_timezone_set("UTC");
require './vendor/autoload.php';
?>

<main role="main">
    <br>
    <div class="container container-large">
      <ul class="nav" style="background-color:mediumpurple">
        <h2 class="navbar-brand">Drawing Canvas</h2>
        <li class="nav-item dropdown ml-auto">
          <a class="nav-link pull-right" data-toggle="dropdown" id="imgDropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i style="color:white;vertical-align:middle" class="fa fa-bars"></i></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="imgDropdown">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
            Upload
          </button>
          </div>
        </li>
      </ul>
        <div class="canvasUpload" style="height:80vh" id="lc"></div>
    </div>
</main>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLongTitle">Image Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <textarea class="form-control" placeholder="Title . . ." style="width:100%;box-sizing:border-box;resize:none" id="title" name="title" form="canvasSubmit" rows="1" required></textarea>
          <br>
          <textarea class="form-control" placeholder="Description . . ." style="width:100%;height:5em;box-sizing:border-box;resize:none" id="desc" name="desc" form="canvasSubmit"></textarea>
          <br>
          <textarea class="form-control" placeholder="Add Tags separated by commas. . ." style="width:100%;box-sizing:border-box;resize:none" id="taglist" name="taglist" form="canvasSubmit"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form class="canvasSubmit">
          <input class="btn" type="submit" data-action="s3Upload" value="Upload">
        </form>
      </div>
    </div>
  </div>
</div>

<form class="canvasSubmit">
    <input type="submit" data-action="s3Upload" value="Upload">
</form>

<script src="canvasAssests/react-0.14.3.js"></script>
<script src="canvasAssests/literallycanvas.js"></script>

<script type="text/javascript">
  var lc = LC.init(document.getElementById("lc"), {
    imageURLPrefix: 'canvasAssests/lc-images',
    toolbarPosition: 'bottom',
    defaultStrokeWidth: 2,
    strokeWidths: [1, 2, 3, 5, 10, 20, 30]
  });
</script>

<script>
  $(document).ready(function(){
    //var lc = LC.init(document.getElementsByClassName('canvasUpload')[0]);
    $('[data-action=s3Upload]').click(function(e) {
      e.preventDefault();

      var title = document.getElementById("title").value;
      var desc = document.getElementById("desc").value;
      var taglist = document.getElementById("taglist").value;

      alert(title);

      $('.canvasSubmit').html('Uploading...')

      $.ajax({
        url: 'canvasClass.php',
        type: 'POST',
        data: {
          // convert the image data to base64
          image:  lc.canvasForExport().toDataURL().split(',')[1],
          type: 'base64',
          title: title,
          desc: desc,
          taglist: taglist
        },
        success: function(result) {
          alert(result);
          $('.canvasSubmit').html('Uploaded')
        },
      });
    });
  });
</script>

</body>
</html>
