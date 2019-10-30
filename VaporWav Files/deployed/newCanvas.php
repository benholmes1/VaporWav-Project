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

$canvasSavesQuery = "SELECT * FROM `saves` where `user_id` = '" . $_SESSION["userData"]["id"] . "'";
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_STRICT;
try {
  $saveQueryResult = $conn->query($canvasSavesQuery);
  if(!($saveQueryResult)) {
    echo "Fail";
  }
} catch (mysqli_sql_exception $e) {
  echo $e->__toString();
}
?>

<main role="main">
    <br>
    <section class="jumbotron text-center" style="color:rebeccapurple">
        <div class="container">
            <h2 class="jumbotron-heading">Drawing Canvas</h2>
            <br>
            <div class="btn-group">
                <button type="button" class="btn mr-2" id="newCanvas" data-toggle="modal" data-target="#newCanvasModal">New Canvas</button>
            </div>
            <div class="btn-group">
                <button class="btn mr-2" type="button" data-toggle="collapse" data-target="#openCanvasCollapse" aria-expanded="false" aria-controls="openCanvasCollapse">Open Canvas</button>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="collapse" id="openCanvasCollapse">
            <div class="card card-body">
                <?php
                    while ($saveRow = $saveQueryResult->fetch_assoc()) {
                        echo '<a href="canvas.php?title='.$saveRow['title'].'&&key='.$saveRow['save_keyname'].'">'.$saveRow['title'].'</a>';
                    }
                ?>
            </div>
        </div>
    </div>
</main>

<!-- New Modal -->
<div class="modal fade" id="newCanvasModal" tabindex="-1" role="dialog" aria-labelledby="newModalCenterTitle" aria-hidden="true">
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

</body>
</html>
