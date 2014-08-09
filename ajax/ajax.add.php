<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
redirect_logged_out_users();
// If memberRow isset, then insert a new row in memberRequest with member_status as pending
if (isset($_POST['memberRequest'])){
	$groupId = intval($_POST['groupId']);
	$group   = new Group($groupId);
	$group->addMember($current_user);
}
?>