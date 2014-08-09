<?php
require_once('connect.php');
global $dbc, $current_user, $groupId;
// If the groupId isset, then the user is admin/officer of a selected group, else, the user is admin of only one group
if (isset($_POST['groupId'])){
	$groupId = intval($_POST['groupId']);
} else {
	$info = array();
	if ($current_user->is_admin($info)){
		$groupId = $info['group_id'];
	} else{
		exit;
	}
}

// Display differently if user is accessing from an iframe
if (isset($_GET['if'])): 
	if ($groupId == $_GET['g']): ?>
	<div id="groupManage">
	<div class="pageHd">Events</div>
		<ul id="tabTop">
			<li><a id="tab_postevents" class="current" href="#<?php echo $groupId; ?>">Post</a></li>
			<li><a id="tab_events" href="#<?php echo $groupId; ?>" style="margin-right: 6px;">Manage</a></li>
			<li><a id="tab_about" href="#<?php echo $groupId; ?>">Settings</a></li>
	<?php endif;
else: ?>
	<div id="groupManage" class="pageLayout">
	<div class="pageHd">Manage Group</div>
		<ul id="tabTop">			
			<li><a id="tab_about" class="current" href="#<?php echo $groupId; ?>">Main</a></li>
			<li><a id="tab_events" href="#<?php echo $groupId; ?>" style="margin-right: 6px;">Events</a></li>
			<li><a id="tab_member" href="#<?php echo $groupId; ?>">Members</a></li>
<?php endif; ?>
	    </ul>
	    <div id="groupContainer"></div>
	<!-- #groupMangage -->
	</div>