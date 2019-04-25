<?php

  //This page displays the image with information

  include 'dbconfig.php';
  include_once 'header_script.php';
  
  //Check if user is logged in
  if($_SESSION['login'] != TRUE) {
    header('Location: index.php');
    exit();
  }

  if(!(isset($_GET['key'])) && !(isset($_GET['id']))){
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
    <div class="post-action">

        <input 
              type="button" 
              value="Like" 
              id="like_<?php echo $keyname; ?>" 
              class="like" 
               
        />
        &nbsp;(<span id="likecount"><?php echo $likescount; ?></span>)&nbsp;

    </div>

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

    <!--------------------------------------------------------Comments-------------------------------------------------------->
    <div class="comment-form-container">
        <form id="frm-comment">
             <!--<div class="input-row">-->
             <!--    <input type="hidden" name="comment_id" id="commentId"-->
             <!--        placeholder="Name" /> -->
             <!--        <input  class="input-field"-->
            <!--                 type="text" -->
            <!--                 name="name" -->
             <!--                id="name" -->
             <!--                placeholder="Name" -->
             <!--        />-->
             <!--</div>-->
     
            <div class="input-row">
                <textarea class="input-field" type="text" name="comment"
                    id="comment" placeholder="Add a Comment">  </textarea>
            </div>
            <div>
                <input type="button" class="btn-submit" id="submitButton"
                    value="Publish" />
                    <!--<div id="comment-message">Comments Added Successfully!</div>-->
            </div>

        </form>
    </div>
    <div id="output"></div><!--im p sure this displays the comments-->
    
    
    <script>
      /*
            function postReply(commentId) {
                $('#commentId').val(commentId);
                $("#name").focus();
            }*/

            $("#submitButton").click(function () {
            	   //$("#comment-message").css('display', 'none');
                var str = $("#frm-comment").serialize();

                $.ajax({
                    url: "addcomment.php",
                    data: str,
                    type: 'post',
                    success: function (response)
                    {
                        var result = eval('(' + response + ')');
                        if (response)
                        {
                        	$("#comment-message").css('display', 'inline-block');
                            $("#name").val("");
                            $("#comment").val("");
                            $("#commentId").val("");
                     	   listComment();
                        } 
                        else
                        {
                            alert("Failed to add comments !");
                            return false;
                        }
                    }
                });
            });
            
            $(document).ready(function () {
            	   listComment();
            });

            function listComment() {
                $.post("listcomment.php",
                        function (data) {
                               var data = JSON.parse(data);
                            
                            var comments = "";
                            var replies = "";
                            var item = "";
                            var parent = -1;
                            var results = new Array();

                            var list = $("<ul class='outer-comment'>");
                            var item = $("<li>").html(comments);

                            for (var i = 0; (i < data.length); i++)
                            {
                                var commentId = data[i]['comment_id'];
                                parent = data[i]['parent_comment_id'];
                                
                                //if (parent == "0")
                                //{
                                    comments = "<div class='comment-row'>"+
                                    "<div class='comment-info'><span class='commet-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='commet-row-label'>at</span> <span class='posted-at'>" + data[i]['date'] + "</span></div>" + 
                                    "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                                   // "<div><a class='btn-reply' onClick='postReply(" + commentId + ")'>Reply</a></div>"+
                                    "</div>";

                                    var item = $("<li>").html(comments);
                                    list.append(item);
                                    /*
                                    var reply_list = $('<ul>');
                                    item.append(reply_list);
                                    listReplies(commentId, data, reply_list);
                                    */

                                //}
                            }
                            $("#output").html(list);
                        });
            }
/*
            function listReplies(commentId, data, list) {
                for (var i = 0; (i < data.length); i++)
                {
                    if (commentId == data[i].parent_comment_id)
                    {
                        var comments = "<div class='comment-row'>"+
                        " <div class='comment-info'><span class='commet-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='commet-row-label'>at</span> <span class='posted-at'>" + data[i]['date'] + "</span></div>" + 
                        "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                        "<div><a class='btn-reply' onClick='postReply(" + data[i]['comment_id'] + ")'>Reply</a></div>"+
                        "</div>";
                        var item = $("<li>").html(comments);
                        var reply_list = $('<ul>');
                        list.append(item);
                        item.append(reply_list);
                        listReplies(data[i].comment_id, data, reply_list);
                    }
                }
            }*/
        </script>
<!----------------------------------------------------------------------------------------------------------------------------->



</body>
</html>
