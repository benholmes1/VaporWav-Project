<?php
include 'dbconn.php';
?>
<!doctype html>
<html lang="en">
<head>
    <!needed this to stop a warning in the validator>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    
    <!-- Favicon Stuff -->
    <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAMMOAADDDgAAAAAA
AAAAAABqITH/aiEx/20iM/9zIjb/gCQ1/5MkSf+aK0v/oSxO/50yVf+nIln/kyNK/4AkNf9zIjX/
bSEz/2ohMf9qITH/aiEx/20hM/93IzP/iygz/6smUv+7Hqz/jk18/8AohP+dQpr/rTuO/8gUrf+o
KFT/jCc0/3cjM/9tITP/aiEx/20hM/92IzT/lyRE/98Xwf/8CfT/yDDQ/6lFnP/5Dez/8w3t/6lE
uP/IMKj//Av4/+EYwv+XJEX/diMz/20iM/9zIjT/jSY5/8Mvc//WK6L/2ied/5dRl//COY3/1img
/9cnm//INJr/m1SQ/9Iuo/+rRqr/yi94/5AlOv9yIjX/fCIy/7YyZP/oSqv/4kea/9NaqP+NWoP/
40Sf/+ZInP/mTKL/3FKn/5llm/+Ra5n/j1V6/9xUsv+lOGL/gCEz/48mPP/CN2X/0Tdu/7pHef93
TnD/nzhq/9I+eP+uSXr/nUBt/4lYhf98NlT/gC9P/4Q5YP+HSHb/uD1t/5IlPP+lLEr/6kyL/9tL
if/nWJr/ZENT/6wyZf+nYI7/kUp2/55Yg/9zQ2L/dCE5/24bKv+GKkz/0kKA//JLjv+lLEv/pytL
/9E7cf+JTG3/okJx/3ZEYf9yMU7/YSo//3YgSP+WToX/bjVT/2wkPP9fFCj/izFY/8Q/eP/POG7/
qCxN/7A6Rv/TfGL/mVhZ/2A4Qv9dHDP/Vxko/2oeLv+fXmX/1Xpi/59za/9aMUv/f0ti/39HY//X
cWH/5X1c/607Sf+oNUP/7JZ0/31WXf9iK0X/YDtU/14xUf9mKDr/kkdg/9+BfP+8eGv/vl5f//GR
eP/Fe27/4H9y//ySbP+nOEb/jSA+/8ZkRP/MgTr/i3ta/0okNP9iWmn/Yi5P/8dwSv/TgDr/44c+
/+OMQP/bgzv/4oY9/9+KPP/JZEL/jiE+/3oaOv+6XTf//80l/42BSv92O0v/r4Za/4NbUf+yckz/
+Lwq/+mvK//rsCr/7bEr/+msKv/1wCf/v2I3/3sZOv9zJTP/hxg//8Z2O//m0C7/578o/+u/IP/s
xib/4bsq/+K5KP/iuij/4bgn/+G2J//rziv/yHk7/4cYP/9zJTP/bCIy/3YiNv+XLzr/zoRG/+nO
aP/nzWn/48Nj/+XCYP/kw2H/5cVi/+nPaP/q0Gn/zYZI/5gwOv93ITb/bCIy/2ohMf9sIzL/dR81
/4keQf+3YWT/0peC/+PAmv/ozKH/6Myh/+PAm//SmIL/uGNk/4ofQf91HzX/bSMy/2ohMf9qITH/
aiEx/2wiMv9zIzP/ehYw/4sdOf+jP0//sVlf/7FZX/+jQE//jB05/3oVMP9zIzP/bCMy/2ohMf9q
ITH/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAA==" rel="icon" type="image/x-icon" />

    <title>VaporWav - Share your art</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="jquery.alphanum-master/jquery.alphanum.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js.map"></script>
    <link rel="stylesheet" href="stylesFinal.css">

    <script src="acctscript.js"></script>
</head>
<body>
  <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <!--<header>
    <div class="padThis">
      <h1>VaporWav</h1>
      <p class="underHeader">Show us what you have been working on.</p>
      <form action="searchUser.php" method="get">
	      <input type="text" name="searchQ" placeholder="Search...">
	      <button type="submit">Submit</button>
      </form>
    </div>
    <nav>
    <!list of the seperate parts of this page>
    <ul>
      <li><a href = "home.php">Home</a>
      <a href = "uploadPage.php">Upload</a>
      <a href = "feed.php">Explore</a>
      <a href = "galleries.php">Galleries</a>
      <a href = "friendPage.php">Friends</a></li>
    </ul>
    <ul class="leftHead">
      <li><a href = "account.php">My Account</a>
      <a href = "logout.php">Logout</a></li>
    </ul>
    </nav>
  </header>-->
  <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="home.php"><h1 style="color:white">VaporWav</h1></a>
    <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarToggle">
      <form style="padding-right:2em" class="form-inline" action="searchUser.php" method="get">
        <div class="input-group">
          <input style="font-family:Tinos" class="form-control" type="text" name="searchQ" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button style="background-color:mediumpurple" class="btn" type="submit"><i style="color:white" class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="home.php"><button class="btn">Home</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="uploadPage.php"><button class="btn">Upload</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="feed.php"><button class="btn">Explore</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="friendPage.php"><button class="btn">Friends</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categoriesPage.php"><button class="btn">Categories</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><button class="btn">Draw</button></a>
        </li>
          <li class="nav-item">
          <a class="nav-link" href="igenPage.php"><button class="btn">Idea Gen</button></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <li class="nav-item" id="dropdown">
          <a class="nav-link"><button class="btn">Notifications</button></a>
          <div id="dropdown-content">
            <?php
            $notifQ = "SELECT * from notifications where userEmail = '" . $_SESSION['userData']['email'] . "'";
            if($notificRes = $conn->query($notifQ)) 
            {
              while($notifications = $notificRes->fetch_assoc()) {
                echo '<p>' . $notifications['message'] . '</p>';
                echo '<br>';
              }
            }
            ?>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="account.php"><button class="btn">My Account</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php"><button class="btn">Logout</button></a>
        </li>
      </ul>
    </div>
  </nav>

<!--<main class="container">-->