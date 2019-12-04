<?php
    //This is the upload action that uploads an image to S3 and updates database
    include 'dbconn.php';
    include 'queries.php';

    //Check if user is logged in
    if(!($_SESSION['login'])){
        header('Location: index.php');
        exit();
    }

    //This is needed to use AWS SDK for PHP
    require './vendor/autoload.php';
    

    $key = $_POST['key'];
    $keyname = explode('/', $key);
    $keyname = end($keyname);

    $delCatQuery = "DELETE FROM `categories` WHERE `keyname` = '".$keyname."'";
    $catRes = $conn->query($delCatQuery);
    if($catRes){
        $message = "Success";
    }
    else{
        $message = $keyname;
    }

    //Add category
    if(!empty($_POST["categories"])){
        $incatlist = $_POST['categories'];
        //Inserting categories into database
        $catconcat = "('".$keyname."','".$incatlist[0]."')";
        $catquery = "INSERT INTO categories (`keyname`, `category_name`) VALUES ".$catconcat;
        for($i = 1; $i < count($incatlist); $i++){
            $catquery = $catquery.", ('".$keyname."', '".$incatlist[$i]."')";
        }
        $catqueryRes = $conn->query($catquery);
        $message = "Success!";
    }
    else {
        $message = "Please select at least one category.";
    }

  //Redirect back to the upload page
  header("Location: imageDisplay.php?key=".$key);

?>
