<?php 
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'group.php');

// If the groupId isset, then the user is admin/officer of a selected group, else, the user is admin of only one group
if (isset($_POST['groupId'])){
	$groupId = intval($_POST['groupId']);
} else{
	$info = array();
	if ($current_user->is_admin($info)){
		$groupId = $info['group_id'];
	} else{
		exit;
	}
}

$group   = new Group($groupId);
$args    = array('member_status' => array('pending'));
$members = $group->getMembers($args);
?>
<div id="memberReq" class="pageLayout">
    <div class="pageHd">Member Request</div>
    <ul class="mediumList">
   	<?php
	// Loop through each member and display their information
	foreach ($members as $member) {
		$user      = $member['user'];
		$member_id = $member['member_id'];
		$fullName  = $user->first_name.' '.$user->last_name;
		$class     = 'u'.$user->id.' g'.$group->id.' m'.$member_id;
		echo '<li class="'.$class.'"><img src="'.$user->thumbnail.'" /><div class="listName">'.$fullName.'</div></li>';
    }
	?>
    </ul>
    <p id="memberNoti" class="noti pageNoti">There are currently no member requests</p>
    <div id="confirmBox"><button class="confirmBtn">Confirm</button><i class="deleteBox"></i></div>    
<!-- #memberReq -->    
</div>