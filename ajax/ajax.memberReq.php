<?php
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'group.php');

$userId     = intval($_POST['user_id']);
$groupId    = intval($_POST['group_id']);
$memberId   = intval($_POST['member_id']);
$member_ids = array($member_id);

if (!empty($memberId) && !empty($userId)){
	$user  = new User($userId);
	$group = new Group($groupId);

	// If confirmMember isset, then we update member_status to member
	if (isset($_POST['confirmMember'])){
		$group->changeMemberStatus($member_ids, 'member');
		sendEmail($user);	
	}

	// If ignoreMember isset, then we remove the member
	if (isset($_POST['ignoreMember'])){
		$group->removeMembers($member_ids);
	}

}

function sendEmail($user){
	global $user, $group;
	$to         = $user->email;
	$subject    = $group->name." accepted your member request";
	$message    = "Dear ".$user->first_name.",\n\nThis message is to let you know that ".$group->name." has accepted your member request. As a member, you will know about any events that are exclusively for members of the group. Also, you can distill your group filter to those belonging to your networks, fields, and groups in your account settings.\n\nFrom\nThe Panramic Team";
	$header     = "From:".ADMIN_EMAIL;
	mail($to,$subject,$message,$header);
}
?>