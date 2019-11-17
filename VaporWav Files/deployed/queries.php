<?php
//SELECT
//searchImage.php check this var and function
$selectTitle_Images_Title = "SELECT title FROM images WHERE title LIKE '%".$imageCompare."%'";

//searchUser.php check function
$selectNicknameEmail_Usernames_Nickname = "SELECT nickname, email FROM usernames u INNER join users n on u.id = n.id WHERE nickname LIKE '%".$emailCompare."%'";

//searhPage.php check function
$selectId_Users_Email = "SELECT id FROM users WHERE email = '".$sEmail."'";

//searchPage.php check function
$selectFriendNickname_Usernames_Id = "SELECT nickname FROM usernames WHERE id = '".$friendID['id']."'";

//searchPage.php check function
$selectBlocked_Usernames_Id = "SELECT blocked_user FROM blocked WHERE owner = '".$BlockerID['id']."' AND blocked_user = '".$userID."'";

//searchPage.php check function
$selectFriends_User_UserFriend = "SELECT * FROM friends WHERE user = '".$_SESSION['userData']['id']."' AND friend = '".$friendID['id']."'";

//upload.php check func
$selectKeyname_Images = "SELECT keyname from images";

//listcomment.php check func
$selectComments = "SELECT * FROM comments ORDER BY created asc";

//like.php func
$selectLikescount_Likes_Keyname = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."' and userid='".$_SESSION['userData']['id']."'";

//like.php and imageDisplay.php
$updateLikes = "SELECT COUNT(*) AS likescount FROM likes WHERE keyname = '".$keyname."'";

//insertAcct.php 
$selectNickname_Usernames = "SELECT nickname from usernames WHERE id = '".$_SESSION['userData']['id']."'";

//insertAcct.php
$selectPrivacy_Users = "SELECT private FROM users where id = '".$_SESSION['userData']['id']."'";

//ImageDisplay.php
$selectNickname_Innerjoin_UsersUsernames = "SELECT nickname FROM users u INNER JOIN usernames n on u.id = n.id where email = '".$mail."'";

//ImageDisplay.php
$selectImages_Keyname = "SELECT * FROM images WHERE keyname = '".$keyname."'";

//ImageDisplay.php
$selectAll_Likes_SessionKeyname = "SELECT * FROM likes WHERE userid = '".$_SESSION['userData']['id']."' AND keyname = '".$keyname."'";

//ImageDisplay.php
$selectAll_CommentsUsernames_Keyname = "SELECT * FROM comments INNER JOIN usernames ON comments.user_id = usernames.id WHERE image_id = '".$keyname."'";

//friendPage.php
$selectRecipientSender_Friendrequests_Recipient = "SELECT recipient, sender FROM friend_requests WHERE recipient = '".$_SESSION["userData"]["id"]."'";

//friendPage.php
$selectAll_Friends_User = "SELECT * from friends WHERE user ='" .$_SESSION["userData"]["id"]. "'";

//friendPage.php
//$selectFriendDetails_Innerjoin_Users = "SELECT nickname, email, picture FROM users u INNER join usernames n on u.id = n.id WHERE u.id = '" . $rowF["friend"] . "'";
//friendPage.php
//$selectFriendRequest_Usernames_Sender = "SELECT nickname, picture FROM usernames n inner join users u on n.id = u.id where n.id = '".$row["sender"]. "'";
//friendPage.php
$selectAll_Blocked_User = "SELECT blocked_user FROM blocked WHERE owner ='".$_SESSION["userData"]["id"]."'";
//$selectBlockedUserDetails = "SELECT nickname, picture FROM usernames n inner join users u on n.id = u.id where n.id ='".$rowB["blocked_user"]."'";

//deleteImage.php
$selectAll_ImageGalleries = "SELECT * FROM `image_galleries` WHERE `keyname` = '".$keyname."'";

//create_gallery.php
$selectGalleries_SessionData = "SELECT galleries FROM galleries WHERE user_id = '".$_SESSION['userData']['id']."'";

//feed.php
$selectFriendImages_Innerjoin_SessionData = "SELECT u.email, i.keyname from images i inner join friends f on i.id = f.friend inner join users u on f.friend = u.id where f.user = '".$_SESSION['userData']['id']."' order by i.created";

//feed.php
$selectImageDetails_Innerjoin_Organized = "SELECT email, keyname, likes FROM images i INNER JOIN users u ON i.id = u.id WHERE private = '0'
AND i.created BETWEEN date_sub(now(), INTERVAL 1 WEEK) AND now() ORDER BY likes desc";

//feed.php
$selectImageDetails_Followers_Innerjoin_Organized = "SELECT user, email, keyname, likes FROM images i INNER JOIN users u ON i.id = u.id
INNER JOIN followers f ON u.id = f.user WHERE follower = '".$_SESSION["userData"]["id"]."'
AND i.created BETWEEN date_sub(now(), INTERVAL 1 WEEK) AND now() ORDER BY likes desc";

//explore.php
$selectImageDetails_Explore = "SELECT email, keyname, likes FROM images i INNER JOIN users u ON i.id = u.id WHERE private = '0' ORDER BY likes desc, i.created desc";

//accept.php
$selectAcceptFriend = "SELECT * FROM friends WHERE user = '"  .$_SESSION["userData"]["id"].  "' AND friend = '" .$_GET["accept"]. "'";

//addFriend.php
$selectAll_FriendRequests_SessionData = "SELECT * FROM friend_requests WHERE sender = '" . $_SESSION["userData"]["id"] . "' AND recipient = '" . $_GET["add"] . "'";




//INSERT -----------------------------------------------------------------------------------------------------
//upload.php check func
$insertImages = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`, `likes`) VALUES ('".$_SESSION['userData']['id']."', '".$tag."', '".$keyNoPrefix."', '".$_POST['title']."', '".$description."', CURDATE(), '0')";

//canvasClass.php
$insertCanvas = "INSERT INTO `images`(`id`, `etag`, `keyname`, `title`, `caption`, `created`, `likes`) VALUES ('".$id."', '".$tag."', '".$keyname."', '".$title."', '".$desc."', CURDATE(), '0')";

//like.php
$insertLikes_SessionData = "INSERT INTO `likes`(`userid`, `keyname`) VALUES ('".$_SESSION['userData']['id']."', '".$keyname."')";

//create_galler.php
$insertUserGallery_Galleries = "INSERT INTO `galleries`(`user_id`, `galleries`) VALUES ('".$_SESSION['userData']['id']."', '".$galName."')";

//accept.php
$insertUser_Friend = "INSERT INTO friends SET user = '" .$_GET["accept"]. "', friend = '" .$_SESSION["userData"]["id"] . "'";
$insertSelf_Friend = "INSERT INTO friends SET user = '" .$_SESSION["userData"]["id"]. "', friend = '" .$_GET["accept"]. "'";

//addToGallery.php
$insertKey_ImageGalleries = "INSERT INTO `image_galleries`(`keyname`, `gallery`) VALUES ('".$keyArr[1]."', '".$destKey."')";

//addToList.php
$insertKey_ImageLists = "INSERT INTO `image_lists`(`keyname`, `list`) VALUES ('".$keyArr[1]."', '".$destKey."')";

//addFriend.php
$insertUsers_FriendRequests = "INSERT INTO friend_requests SET sender = '" . $_SESSION["userData"]["id"] . "', recipient = '" . $_GET["add"] . "'";

//addcomment.php
$insertComment_Comments = "INSERT INTO comments(`image_id`,`user_id`,`comment`,`created`) VALUES ('".$keyname."','".$_SESSION['userData']['id']."','".$comment."',CURDATE())";

//DELETE -----------------------------------------------------------------------------------------------------
//like.php
$deleteLikes = "DELETE FROM likes WHERE keyname = '".$keyname."' and userid='".$_SESSION['userData']['id']."'";

//deleteImage.php
$deleteImageGallery = "DELETE FROM `image_galleries` WHERE `keyname` = '".$keyname."'";

//
$deleteImageComments = "DELETE FROM `comments` WHERE `image_id` = '".$keyname."'";

//
$deleteImageLikes = "DELETE FROM `likes` WHERE `keyname` = '".$keyname."'";

//
$deleteImage = "DELETE FROM `images` where `keyname` = '".$keyname."'";

//
$deleteImage_Gallery = "DELETE FROM `image_galleries` WHERE `keyname` = '".$keyname."' AND gallery = '".$key."'";

//
$deleteImageTags = "DELETE FROM `tags` WHERE `keyname` = '".$keyname."'";

//
$deleteImageCategories = "DELETE FROM `categories` WHERE `keyname` = '".$keyname."'";

//deleteGallery
$deleteGalleries = "DELETE FROM `galleries` where `user_id` = '".$id."' and `galleries` = '".$gal."'";

//accept.php
$deleteUsers_FriendRequests = "DELETE FROM friend_requests WHERE sender = '" . $_GET["accept"] . "' AND recipient = '" . $_SESSION["userData"]["id"] . "'";

//UPDATE -----------------------------------------------------------------------------------------------------
//like.php
$updateLikes_Images = "UPDATE images SET likes = '".$likescount."' WHERE keyname = '".$keyname."'";

//insertAcct.php
$updateNickname_Usernames = "UPDATE usernames SET nickname = '".$_POST['nname1']."' WHERE id = '".$_SESSION['userData']['id']."'";

//insertAcct.php
$updatePrivacy_Users = "UPDATE users SET private = '".$privacySetting."' WHERE id = '".$_SESSION['userData']['id']."'";

?>