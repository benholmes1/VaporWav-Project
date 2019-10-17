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
        <h2>Drawing Canvas</h2>
        <div style="height:80vh" id="lc"></div>
    </div>
</main>

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
</body>
</html>
