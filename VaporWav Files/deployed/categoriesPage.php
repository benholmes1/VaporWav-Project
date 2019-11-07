<?php

  //This is the upload page where a user can upload an image
  include_once 'header.php';

  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }
  $cateNames = array("Digital Art","Traditional Art","Photography","Comics","Collage","Drawing","Painting","Landscape","Sculpture","Typography","3D Art","Photomanipulation","Pixel Art","Text Art","Vector","Fan Art");
  $catQuery0 = "SELECT COUNT(*) AS cat0count FROM categories WHERE category_name = '".$cateNames[0]."'";
  $catQuery1 = "SELECT COUNT(*) AS cat1count FROM categories WHERE category_name = '".$cateNames[1]."'";
  $catQuery2 = "SELECT COUNT(*) AS cat2count FROM categories WHERE category_name = '".$cateNames[2]."'";
  $catQuery3 = "SELECT COUNT(*) AS cat3count FROM categories WHERE category_name = '".$cateNames[3]."'";
  $catQuery4 = "SELECT COUNT(*) AS cat4count FROM categories WHERE category_name = '".$cateNames[4]."'";
  $catQuery5 = "SELECT COUNT(*) AS cat5count FROM categories WHERE category_name = '".$cateNames[5]."'";
  $catQuery6 = "SELECT COUNT(*) AS cat6count FROM categories WHERE category_name = '".$cateNames[6]."'";
  $catQuery7 = "SELECT COUNT(*) AS cat7count FROM categories WHERE category_name = '".$cateNames[7]."'";
  $catQuery8 = "SELECT COUNT(*) AS cat8count FROM categories WHERE category_name = '".$cateNames[8]."'";
  $catQuery9 = "SELECT COUNT(*) AS cat9count FROM categories WHERE category_name = '".$cateNames[9]."'";
  $catQuery10 = "SELECT COUNT(*) AS cat10count FROM categories WHERE category_name = '".$cateNames[10]."'";
  $catQuery11 = "SELECT COUNT(*) AS cat11count FROM categories WHERE category_name = '".$cateNames[11]."'";
  $catQuery12 = "SELECT COUNT(*) AS cat12count FROM categories WHERE category_name = '".$cateNames[12]."'";
  $catQuery13 = "SELECT COUNT(*) AS cat13count FROM categories WHERE category_name = '".$cateNames[13]."'";
  $catQuery14 = "SELECT COUNT(*) AS cat14count FROM categories WHERE category_name = '".$cateNames[14]."'";
  $catQuery15 = "SELECT COUNT(*) AS cat15count FROM categories WHERE category_name = '".$cateNames[15]."'";
?>
<main role="main">
<br>
<section class="jumbotron text-center" style="color:rebeccapurple">
  <br>
  <div class="jumbotron">
    <h2 class="jumbotron-heading">Category List</h2>
  </div>
  <div class="container">
  <div class="row">
    <div class="col">Column</div>
    <div class="col">Column</div>
    <div class="w-100"></div>
    <div class="col">Column</div>
    <div class="col">Column</div>
  </div>
</div>
  </div>
</div>


</main>
</body>
</html