<?php

  //This page displays the image with information

  include 'dbconfig.php';
  include_once 'header_script.php';
  
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }

  if(!(isset($_GET['key']))){
    header('Location: home.php');
    exit();
  }

  $key = $_GET['key'];
 
  if($key === '') {
    header('Location: home.php');
    exit();
  }

  $dbHost     = DB_HOST;
  $dbUsername = DB_USERNAME;
  $dbPassword = DB_PASSWORD;
  $dbName     = DB_NAME;
   
  // Connect to the database
  $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
  if($conn->connect_error){
    die("Failed to connect with MySQL: " . $conn->connect_error);
  }

  //Expiration time
  $expire = "1 hour";

  date_default_timezone_set("UTC");
  require './vendor/autoload.php';
  include 'config.php';
 
  //Get image again from S3
  $s3 = new Aws\S3\S3Client([
    'version' => '2006-03-01',
    'region'  => $region,
  ]);

  $cmd = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
    'Key'    => $key,
  ]);//
 
  $request = $s3->createPresignedRequest($cmd, "+{$expire}");
  $signed_url = (string) $request->getUri();   
 
  $keyname = explode('/', $key);
  $keyname = end($keyname);
 
  
  
  //get user nickname
  $mail = current(explode('/',$key));
  //echo "<script type='text/javascript'>alert('$keyname');</script>";

  $queryUser = "SELECT nickname FROM users u INNER JOIN usernames n on u.id = n.id where email = '".$mail."'";
  $queryResU = $conn->query($queryUser);
  $userinfo = $queryResU->fetch_assoc();

  //get IDs
  $queryImage = "SELECT * FROM images WHERE keyname = '".$keyname."'";
  $queryResI = $conn->query($queryImage);
  $imageinfo = $queryResI->fetch_assoc();

  // Count post total likes and unlikes
  $queryLike = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $date = strtotime($imageinfo['created']);
  $formatDate = date("m/d/y", $date);

  $getComments = "SELECT * FROM comments INNER JOIN usernames ON comments.user_id = usernames.id WHERE image_id = '".$keyname."'";
  $getCommentsRes = $conn->query($getComments);

?>
    
    <main id="imageSolo">
    <article>
    <section>

    <!--image title-->  
    <h2><?php echo $imageinfo['title'] ?></h2>
    
    <div class="bordThis">
    
    <!--image-->
    <figure>
    <img src="<?php echo $signed_url ?>">
        <!--image caption-->
        <figcaption><?php echo $imageinfo['caption'] ?></figcaption>
    </figure>
    
    <!--image author-->
    <p class="same-row">Created by: <?php echo $userinfo['nickname'] ?></p>
    
    <!--uploader options---------------------------------------------------------->
    <?php
      if($_SESSION['userData']['id'] === $imageinfo['id'] && !(isset($_GET['exp']))) {
        $dropOut  = '<div class="same-row" style="float:right">';
        $dropOut .= '<button onclick="myFunction()" class="dropbtn">Options</button>';
        $dropOut .= '<div id="imgDropdown" class="dropdown-content">';
        $keyLen = explode('/', $key);
        if(count($keyLen) <= 2) {
          $dropOut .= '  <a id="delete" href="deleteImage.php?key='.$key.'">Delete</a>';
          $dropOut .= '  <a href="#" class="acc">Add To Gallery</a>';
          $dropOut .= '    <div class="panel">';
          echo $dropOut;
          foreach($_SESSION['galleries'] as $gal) { 
            echo '<a href="addToGallery.php?gal='.$gal.'&key='.$key.'">'.$gal.'</a>'; 
          }
          $dropOut  = '    </div>';
          $dropOut .= '</div>';
          $dropOut .= '</div>';
        } else {
          echo $dropOut;
          $dropOut  = '  <a id="delete" href="deleteImage.php?key='.$key.'&gal='.$gal.'">Remove</a>';
          $dropOut .= '    </div>';
          $dropOut .= '</div>';
        }
        echo $dropOut;
      }
    ?>
    <!-------------------------------------------------------------------------->
    
    <!--image upload date-->
    <p>Uploaded on: <?php echo $formatDate ?></p>
    
    
    <!--like button-->
    <div class="post-action" style="float:right">

        <input 
              type="button" 
              value="Like" 
              id="like_<?php echo $keyname; ?>" 
              class="like" 
               
        />
        &nbsp;(<span id="likecount"><?php echo $likescount; ?></span>)&nbsp;

    </div>

    </div>
    <textarea placeholder="Comment . . ." style="width:100%;box-sizing:border-box;resize:none" id="comment" name="comment" form="commentForm" required></textarea>
    <form action="addcomment.php" id="commentForm" method="post">
      <input id="uploadComment" type="submit" value="Publish">
      <input id="key" name="key" type="hidden" value="<?php echo $keyname; ?>">
      <input id="fullKey" name="fullKey" type="hidden" value="<?php echo $key; ?>">
    </form>

    <div id="commentSection">

    <?php

      while($comments = $getCommentsRes->fetch_assoc()) {
        $commentDate = date("m/d/y", $comments['created']);
        $commentOut  = '<p>'.$comments['nickname'].'</p>';
        $commentOut .= '<p>'.$comments['comment'].'</p>';
        $commentOut .= '<hr width:100%>';
        echo $commentOut;
      }

      $comment = $getCommentsRes->fetch_assoc();
      echo '<p>'.$comment['nickname'].'</p>';

    ?>

    </div>

    </section>

    <script>
    function myFunction() {
      document.getElementById("imgDropdown").classList.toggle("show");
    }

    // Close the dropdown menu if the user clicks outside of it
    /*window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }*/

    $('a.acc').click(function(e)
    {
      e.preventDefault();
    });

    $('#delete').on('click',function(e) {
      var answer=confirm('Are you sure you want to delete this image?');
      if(answer){
        alert('Deleted');
      }
      else{
        e.preventDefault();      
      }
    });

    var acc = document.getElementsByClassName("acc");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }
    </script>

</body>
</html>
