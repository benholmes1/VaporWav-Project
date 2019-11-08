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

if(isset($_GET['title'])) {
  $canvasTitle = $_GET['title'];
}

if(isset($_GET['key'])) {
  $canvasKey = $_GET['key'];
}
?>

<main role="main">
    <br>
    <div class="container container-large">
      <ul class="nav" style="background-color:mediumpurple">
        <h2 class="navbar-brand"><?php echo $canvasTitle ?></h2>
        <li class="nav-item dropdown ml-auto">
          <a class="nav-link pull-right" data-toggle="dropdown" id="imgDropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i style="color:white;vertical-align:middle" class="fa fa-bars"></i></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="imgDropdown">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newModal">
            New
          </button>
          <form class="canvasSave">
            <input class="btn" type="submit" data-action="save" value="Save">
          </form>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
            Upload
          </button>
          </div>
        </li>
      </ul>
        <div class="canvasUpload" style="height:80vh" id="lc"></div>
    </div>
</main>


<!-- Upload Modal -->
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

<!-- New Modal -->
<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newModalLongTitle">New Canvas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <textarea class="form-control" placeholder="Canvas Title . . ." style="width:100%;box-sizing:border-box;resize:none" id="canvasTitle" name="canvasTitle" form="newCanvas" rows="1" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form class="newCanvas">
          <input class="btn" type="submit" data-action="createCanvas" value="Create">
        </form>
      </div>
    </div>
  </div>
</div>

<script src="canvasAssests/react-0.14.3.js"></script>
<script src="canvasAssests/literallycanvas.js"></script>

<script type="text/javascript">
  //Initialize canvas script
  var lc = LC.init(document.getElementById("lc"), {
    imageURLPrefix: 'canvasAssests/lc-images',
    toolbarPosition: 'bottom',
    defaultStrokeWidth: 2,
    strokeWidths: [1, 2, 3, 5, 10, 20, 30],
    backgroundColor: '#fff'
  });
</script>

<script>
  //Upload canvas script
  $(document).ready(function(){
    //var lc = LC.init(document.getElementsByClassName('canvasUpload')[0]);
    $('[data-action=s3Upload]').click(function(e) {
      e.preventDefault();

      var title = document.getElementById("title").value;
      var desc = document.getElementById("desc").value;
      var taglist = document.getElementById("taglist").value;

      $('.canvasSubmit').html('Uploading...')

      $.ajax({
        url: 'canvasHandler.php',
        type: 'POST',
        data: {
          function: "upload",
          image:  lc.getImage({scaleDownRetina: true}).toDataURL().split(',')[1],
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

<script>
  //Save canvas script
  $(document).ready(function(){
    //var lc = LC.init(document.getElementsByClassName('canvasUpload')[0]);
    $('[data-action=save]').click(function(e) {
      e.preventDefault();

      let params = (new URL(document.location)).searchParams;
      let canvasTitle = params.get("title");

      if(params.has("key")) {
        var canvasKey = params.get("key");
      }

      var snapshot = JSON.stringify(lc.getSnapshot(['shapes', 'imageSize', 'colors', 'position', 'scale', 'backgroundShapes']));

      $.ajax({
        url: 'canvasHandler.php',
        type: 'POST',
        data: {
          function: "save",
          canvasTitle: canvasTitle,
          canvasKey: canvasKey,
          snapshot: snapshot
        },
        success: function(result) {
          window.location.replace("http://ec2-52-53-194-4.us-west-1.compute.amazonaws.com/canvas.php?title="+canvasTitle+"&&key="+result+"&&type=load");
        },
      });
    });
  });
</script>

<script>
  //New canvas script
  $(document).ready(function(){
    //var lc = LC.init(document.getElementsByClassName('canvasUpload')[0]);
    $('[data-action=createCanvas]').click(function(e) {
      e.preventDefault();

      var canvasTitle = document.getElementById("canvasTitle").value;

      window.location.replace("http://ec2-52-53-194-4.us-west-1.compute.amazonaws.com/canvas.php?title="+canvasTitle);
    });
  });
</script>

<script>
  let params = (new URL(document.location)).searchParams;

  if(params.has("type")) {
    var type = params.get("type");
  }

  if(type == "load") {
    window.onload = loadFunction;
  }

  function loadFunction() {

    let params = (new URL(document.location)).searchParams;

    if(params.has("key")) {
      var canvasKey = params.get("key");
    }

    $.ajax({
      url: 'canvasHandler.php',
      type: 'POST',
      data: {
        function: "load",
        canvasKey: canvasKey
      },
      success: function(result) {
        //alert(result);
        var loadObject = JSON.parse(result);
        lc.loadSnapshot(loadObject);
      },
    });
  }
</script>

<script>
  window.onbeforeunload = function(){
    return 'Are you sure you want to leave?';
  };
</script>

</body>
</html>
