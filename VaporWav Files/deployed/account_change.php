<?php

//This page displays a form for the user to change their nickname

  include_once 'header_script.php';
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }
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
   //Javascript function to restrict entry in nickname field
   $("#nname").alphanum({
     allowSpace: false,
     allowNewline: false,
     allowOtherCharSets: false
   });
 </script>
</body>
</html
