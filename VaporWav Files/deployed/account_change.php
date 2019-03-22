<?php
  include_once 'header_script.php';
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
  } /*else {
    if(isset($_GET['fail']) && $_GET['fail'] == 1)
    {
      $uniqueErr = "Username Taken";
      echo "<script type='text/javascript'>alert('$uniqueErr');</script>";
     
    }
  }
*/
?>
  <div class="container">
    <div class="wrapacct">
      <h2>Edit Account Settings</h2>
      <div id="form">
        <div class="acctform">
	  <label>Nickname: </label>
	  <input id="nname" type="text" placeholder="Enter nickname" required>
	  <br>
	  <input id="submit" type="button" value="Submit">
	</div>
      </div>
    </div>
  </div>
  <script>
   $("#nname").alphanum({
     allowSpace: false,
     allowNewline: false,
     allowOtherCharSets: false
   });
 </script>
</body>
</html
