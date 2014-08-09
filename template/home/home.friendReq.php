<?php 
require_once('connect.php');
redirect_logged_out_users();

?>
<div id="friendReq" class="pageLayout">
    <div class="pageHd">Friend Requests</div>
    <ul class="mediumList">
    <?php
	  // Select all rows from user_friends where the friend_status is 'pending'
    $select = "SELECT u.user_id, u.first_name, u.last_name, h.href_name
               FROM user_friends AS uf
               INNER JOIN users AS u
               ON (uf.friend_id = u.user_id)
			   INNER JOIN hrefs AS h
			   ON (uf.friend_id = h.user_id)
               WHERE uf.user_id = '$userId' AND uf.friend_status = 'pending'";
    $result = mysqli_query($dbc,$select) or die("cant select");
    
    while ($friend = mysqli_fetch_assoc($result)){
        $friendId    = intval($friend['user_id']);
        $fullname    = htmlentities($friend['first_name']." ".$friend['last_name']);
        $friendHref  = htmlentities($friend['href_name']);
        $thumbnail   = "images/users/ut".$friendId.".jpg";
        echo '<li value="'.$friendId.'"><img src="'.$thumbnail.'" /><div class="listName"><a href ="'.$friendHref.'">'.$fullname.'</a></div></li>';
    }
	 ?>
    </ul>
    <div id="friendNoti" class="noti pageNoti">There are currently no friend requests</div>
    <div id="confirmBox"><button class="confirmBtn">Confirm</button><i class="deleteBox"></i></div>    
<!-- #friendReq -->    
</div>