<?php

  //This page displays the image with information
  include_once 'header.php';
  include 'queries.php';
  require_once 's3Access.php';
  
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

  date_default_timezone_set("UTC");
  require './vendor/autoload.php';
  include 'config.php';

  $IAM_KEY = ACCESS_KEY;
  $IAM_SECRET = SECRET_KEY;
 
  $s3Client = new S3Access();
  $checkExists = $s3Client->checkExists($region, $bucket, $key, $IAM_KEY, $IAM_SECRET);
  if($checkExists == 1) {
    $signed_url = $s3Client->get($region, $bucket, $key);
  }
  else {
    echo "Image Not Found";

    header('Location: home.php');
  }
 
  $keyname = explode('/', $key);
  $keyname = end($keyname);
 
  
  
  //get user nickname
  $mail = current(explode('/',$key));
  //echo "<script type='text/javascript'>alert('$keyname');</script>";

  $queryUser = $selectNickname_Innerjoin_UsersUsernames;
  $queryResU = $conn->query($queryUser);
  $userinfo = $queryResU->fetch_assoc();

  //get IDs
  $queryImage = $selectImages_Keyname;
  $queryResI = $conn->query($queryImage);
  $imageinfo = $queryResI->fetch_assoc();

  // Count post total likes and unlikes
  $queryLike = $updateLikes;
  $queryResL = $conn->query($queryLike);
  $likesinfo = $queryResL->fetch_assoc();
  $likescount = $likesinfo['likescount'];

  $checkLikeQuery = $selectAll_Likes_SessionKeyname;
  $checkLikeRes = $conn->query($checkLikeQuery);
  if($checkLikeRes->num_rows == 0) {
    $checkLike = FALSE;
  } else {
    $checkLike = TRUE;
  }

  $date = strtotime($imageinfo['created']);
  $formatDate = date("m/d/y", $date);

  $getComments = $selectAll_CommentsUsernames_Keyname;
  $getCommentsRes = $conn->query($getComments);

?>
    
<main role="main">
  <div class="container">
    <br>
    <div class="wrapacct" style="text-align:left">

    <!--image title-->  
    <ul class="nav" style="background-color:mediumpurple">
      <h2 class="navbar-brand"><?php echo $imageinfo['title'] ?></h2>
      <?php
        if($_SESSION['userData']['id'] === $imageinfo['id'] && !(isset($_GET['exp']))) {
          ?>
          <li class="nav-item dropdown ml-auto">
            <a class="nav-link pull-right" data-toggle="dropdown" id="imgDropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i style="color:white;vertical-align:middle" class="fa fa-bars"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="imgDropdown">
              <?php
                $keyLen = explode('/', $key);
                if(count($keyLen) <= 2) {
                  echo '<a class="dropdown-item" id="delete" href="deleteImage.php?key='.$key.'">Delete</a>';
                  ?>
                  <a style="color:black" class="nav-link dropdown-toggle" data-toggle="dropdown" id="addDropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Add To Gallery</a>
                  <div class="dropdown-menu" aria-labelledby="addDropdown">
                    <?php
                    foreach($_SESSION['galleries'] as $gal) { 
                      echo '<a class="dropdown-item" href="addToGallery.php?gal='.$gal.'&key='.$key.'">'.$gal.'</a>'; 
                    }
                    ?>
                  </div>
                <?php
                } else {
                  echo '<a class="dropdown-item" id="remove" href="deleteImage.php?key='.$key.'&gal='.$gal.'">Remove</a>';
                }
                ?>
            </div>
          </li>
        <?php
        }
        ?>
    </ul>
        
    <!--image-->
    <figure>
    <a href="<?php echo $signed_url ?>" data-toggle="lightbox"><img src="<?php echo $signed_url ?>"></a>
        <!--image caption-->
        <figcaption><?php echo $imageinfo['caption'] ?></figcaption>
    </figure>

    <div class="imgInfo">
    
      <div class="row">
        <!--image author-->
        <div class="col-6">Created by: <a href="searchPage.php?searchQ=<?php echo $mail; ?>"><?php echo $userinfo['nickname'] ?></a></div>

          <!--like button-->
            <div class="col-6 text-right">
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <a class="twitter-share-button"
            href="https://twitter.com/intent/tweet?text=Come%20check%20this%20art%20piece%20out%20on%20VaporWav"
            data-size="large">Tweet</a>

            <a href="" id="fb_share">Share this on Facebook</a>
            <script>
            window.onload = function() {
            fb_share.href ='http://www.facebook.com/share.php?u=' + location.href;//encodeURIComponent(location.href); 
            }  
            </script>
            <?php
            if($checkLike) {
              echo '<input style="color:#FD01FF" type="button" value="&#xf087" id="unlike_'.$keyname.'" class="like btn fa" />';
            } else {
              echo '<input type="button" value="&#xf087" id="like_'.$keyname.'" class="like btn fa" />';
            } ?>
            <span style="color:white;margin-left:1em;margin-right:1em" id="likecount"><?php echo $likescount; ?></span>
          </div>
      </div>
          
      <div class="row">
        <!--image upload date-->
        <p>Uploaded on: <?php echo $formatDate ?></p>
      </div>

      <textarea placeholder="Comment . . ." style="width:100%;box-sizing:border-box;resize:none" id="comment" name="comment" form="commentForm" required></textarea>
      <form action="addcomment.php" id="commentForm" method="post">
        <input class="btn" id="uploadComment" type="submit" value="Publish">
        <input id="key" name="key" type="hidden" value="<?php echo $keyname; ?>">
        <input id="fullKey" name="fullKey" type="hidden" value="<?php echo $key; ?>">
      </form>
      <!--display comments-->
      <div id="commentSection">
        <?php

          while($comments = $getCommentsRes->fetch_assoc()) {
            $commentDate = date("m/d/y", $comments['created']);
            $commentOut  = '<p>'.$comments['nickname'].'</p>';
            $commentOut .= '<p style="padding-left:3em">'.$comments['comment'].'</p>';
            $commentOut .= '<hr width:100%>';
            echo $commentOut;
          }

          $comment = $getCommentsRes->fetch_assoc();
          echo '<p>'.$comment['nickname'].'</p>';

        ?>
      </div>
      <!--display comments-->
    </div>
    </div>
  </div>
</main>

    <script>
    /*function myFunction() {
      document.getElementById("imgDropdown").classList.toggle("show");
    }*/

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

    /*$('a.acc').click(function(e)
    {
      e.preventDefault();
    });*/

    $('#delete').on('click',function(e) {
      var answer=confirm('Continuing will delete the image from all galleries.\nDo you want to continue?');
      if(answer){
        alert('Deleted');
      }
      else{
        e.preventDefault();      
      }
    });

    $('#remove').on('click',function(e) {
      var answer=confirm('Continuing will remove this image from this gallery only.\nDo you want to continue?');
      if(answer){
        alert('Deleted');
      }
      else{
        e.preventDefault();      
      }
    });

    /*var acc = document.getElementsByClassName("acc");
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
    }*/

    //Like Script
    $(document).ready(function(){

      // like click
      $(".like").click(function(){
          var id = this.id;   // Getting Button id
          var split_id = id.split("_");

          var type = split_id[0];
          var keyname = id.split(/_(.+)/)[1];
          
          var data = {
            key: keyname,
          };

          // AJAX Request
          $.ajax({
              url: 'like.php',
              type: 'post',
              data: data,
              dataType: 'json',
              success: function(data){
                  var likes = data['likes'];

                  //var type = typeof(data['likes']);
                  //alert(type);
                  if(type === "like") {
                    $(".like").attr('id', 'unlike_'+keyname);
                    $(".like").css('color', '#FD01FF');
                  } else {
                    $(".like").attr('id', 'like_'+keyname);
                    $(".like").css('color', 'white');
                  }

                  $("#likecount").text(data['likes']);        // setting likes
              
              },
              error: function() {
                  alert('Error');
              }
          });

      });

    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
    </script>

</body>
</html>
