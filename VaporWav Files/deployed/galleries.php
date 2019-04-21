<?php

//This page will display the user's galleries

include_once 'header.php';

if($_SESSION['login'] != TRUE) {
  header('Location: index.php');
  exit();
}
?>
<div class="container">
  <div class="wrapper">
  <h2 style="font-family:Alien Encounters;size:150%">Your Galleries</h2>
  <div class="gal">
    <button id="galBtn">Create A New Gallery</button>
    <div id="galModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
              <span class="close" style="margin-left:10px">&times;</span>
              <h2>Create a New Gallery</h2>
            </div>
            <div class="modal-body">
              <form action="create_gallery.php" method="post" id="galForm">
                  <label for="name" style="color:white">Enter the name of your gallery:</label>
                  <input type="text" name="name" required>
                  <button type="submit" class="subBtn">Submit</button>
              </form>
            </div>
        </div>
    </div>
    <ul>
      <li><a href="home.php">Your Gallery</a></li>
      <?php
        foreach($_SESSION['galleries'] as $gal) {
          echo '<li><a href="home.php?gal='.$gal.'">'.$gal.'</a></li>';
        }
      ?>
    </ul>
  </div>
  </div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById('galModal');

    // Get the button that opens the modal
    var btn = document.getElementById("galBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
