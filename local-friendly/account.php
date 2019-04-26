<?php

//This page will display the user's account information

include_once 'header.php';

?>
<div class="container">
  <div class="wrapper">
    <!-- Display profile information -->
    <h2 style="font-family:Alien Encounters;size:150%">Account Details</h2>
      <div class="ac-data">
        <img src="images/blank-profile.png" style="padding-top:10px;padding-bottom:10px">
          <div class="acct">
            <p style="font-family:Tinos"><b>Username:</b> ben</p>
            <p style="font-family:Tinos"><b>Name:</b> Ben Holmes</p>
            <p style="font-family:Tinos"><b>Email:</b> email@email.com</p>
            <p style="font-family:Tinos"><b>Privacy Status:</b> Private</p>
            <p><a href = "account_change.php">Edit</a></p>
          </div>
      </div>
    </div>
</div>
</body>
</html>
