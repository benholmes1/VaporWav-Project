<?php
  include 'dbconn.php';
  include 'queries.php';

  //Check if user is logged in
  if(!($_SESSION['login'])) {
    header('Location: index.php');
    exit();
  }

    $check = 0;

  //if(!empty($_POST['nname1'] {
    $editName = $updateNickname_Usernames;
    $edit = $conn->query($editName);
    if($edit) {
      $getName = $selectNickname_Usernames;
      $result = $conn->query($getName);
      $nickname = $result->fetch_assoc(); 
      $_SESSION['nickname'] = $nickname["nickname"];
      //header('Location: account.php');
      $check = 1;
   } else {
      //header('Location: account_change.php?fail=1');
      $check = 0;
   }

   if($_POST['privacy'] == "pub") {
     $privacySetting = "0";
   } else {
     $privacySetting = "1";
   }

   $privacyQuery = $updatePrivacy_Users;
   $privacyQRes = $conn->query($privacyQuery);
   if($privacyQRes) {
     $getPrivacy = $selectPrivacy_Users;
     $getResult = $conn->query($getPrivacy);
     $privacy = $getResult->fetch_assoc();
     $_SESSION['private'] = $privacy['private'];
     $check = 1;
   } else {
     $check = 0;
   }

   if($check == 1) {
     echo "Success";
   } else {
     echo "Something went wrong";
   }

   header('Location: account.php');

  //}
//  }
?>

