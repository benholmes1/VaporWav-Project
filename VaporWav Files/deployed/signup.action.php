<?php
    include "User.class.php";
    include "dbconn.php";
    $dataArray = array();

    //$string = random_int(100000000000000000000, 120000000000000000000);
    $dataArray['oauth_provider'] = "VaporWav";
    $dataArray['oauth_uid'] = rand(0, 999999);
    $dataArray['first_name'] = $_POST['first'];
    $dataArray['last_name'] = $_POST['last'];
    $dataArray['email'] = $_POST['email'];
    $dataArray['gender'] = "";
    $dataArray['locale'] = "en";
    $dataArray['picture'] = '<link href="data:image/x-icon;base64,
    AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAMMOAADDDgAAAAAA
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
    AAAAAAAAAAAAAA==" rel="icon" type="image/x-icon" />';
    $dataArray['link'] = "";
    $dataArray['password'] = $_POST['password'];
    $dataArray['flag'] = 1;

    
    $user = new User();
    $userData = $user->checkUser2($dataArray);
    
    //$sql = "UPDATE users (`password`, `flag`) VALUES (AES_ENCRYPT(".$dataArray['password'].", 'dinosaurlasers'), '".$dataArray['flag']."');";
    //$sql = "UPDATE users SET password = ".$dataArray['password']." WHERE email = ".$dataArray['email'].";";
    $update = $conn->query($sql);
    
    // Storing user data in the session
	$_SESSION['userData'] = $userData;


	$nickname = $user->checkName($userData);
	$_SESSION['nickname'] = $nickname;

	$galleries = $user->getGalleries($_SESSION['userData']['id']);
	$_SESSION['galleries'] = $galleries;

	$private = $user->getPrivacy($_SESSION['userData']['id']);
	$_SESSION['private'] = $private;
    
    $_SESSION['login'] = true;

    // Render user profile data
    if(!empty($userData)){
        header('Location: home.php');
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
?>
