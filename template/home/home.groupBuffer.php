<?php 
require_once('connect.php');
redirect_logged_out_users();
?>
<div id="groupBuffer" class="pageLayout">
    <div class="pageHd">Select Group</div>
    <ul class="mediumList">
    <?php
	// Select all rows from user_friends where the friend_status is 'pending'
    $select = "SELECT g.group_id, g.group_name
			   FROM group_members AS gm
			   INNER JOIN groups AS g
			   ON (gm.group_id = g.group_id)
			   WHERE gm.user_id ='$current_user->id' AND gm.member_status = 'admin' AND g.group_status = 'active'";
    $result = mysqli_query($dbc,$select) or die("cant select");
    
    while ($group = mysqli_fetch_assoc($result)){
		$groupId    = intval($group['group_id']);
		$name       = htmlentities($group['group_name']);
    	$thumbnail  = "images/groups/m".$groupId.".jpg";
    	echo '<li value="'.$groupId.'"><img src="'.$thumbnail.'" /><div class="listName">'.$name.'</div></li>';
    } 
    ?>
    </ul>
<!-- #groupBuffer -->    
</div>