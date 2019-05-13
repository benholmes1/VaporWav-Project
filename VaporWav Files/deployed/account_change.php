<?php

//This page displays a form for the user to change their nickname

  include_once 'header.php';
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }
?>
<main role="main">
  <div class="container container-small">
  <br>
    <div class="wrapacct">
      <h2 style="text-align:center">Edit Account Settings</h2>
      <div id="form">
        <form action="insertAcct.php" method="post" id="acctForm" class="acctform">
          <label>Nickname: </label>
          <input id="nname" name="nname1" type="text" placeholder="Enter nickname" required>
          <br>
          <p style="text-align:left">Privacy setting:</p>
          <div id="privacyDiv">
            <input type="radio" id="public" name="privacy" value="pub" checked>
            <label for="public">Public</label>
            <input type="radio" id="private" name="privacy" value="pri">
            <label for="private">Private</label>
          </div>
          <br>
	        <button class="btn" type="submit">Submit</button>
	      </form>
      </div>
    </div>
  </div>
</main>
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
