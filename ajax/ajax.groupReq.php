<?php
require_once('connect.php');
redirect_not_staff();
require_once(CLASS_DIR.'group.php');

// If confirmGroup isset, then both update the group status
if (isset($_POST['confirmGroup'])){
	$groupId = intval($_POST['groupId']);
	if (!empty($groupId)){
		$group  = new Group($groupId);
		$admins = $group->getAdmins();
		$group->changeStatus('active');
		foreach ($admins as $user) {
			$to      	= $user->email;
			$subject 	= 'Group confirmed!';
			$msg 		= "Dear ".$user->first_name.", \n\nThis message is to let you know that your group ".$group->name." has been confirmed. It's a really exciting time and we are really glad to have your group be on board. Feel free to start posting events and have students see it immediately in their events feed! \n\nFrom\nThe Panramic Team";
			$header   	= "From:".ADMIN_EMAIL;
			mail($to,$subject,$msg,$header);
		}
	}
	exit;
}

// If ignoreGroup isset, then remove everything asscoiated with the group
if (isset($_POST['ignoreGroup'])){
	$groupId = intval($_POST['groupId']);
	if (!empty($groupId)){
		$group  = new Group($groupId);
		$group->remove();
	}
	exit;
}

// If editGroup isset, we reestablish the group connections
if (isset($_POST['editGroup'])){
	$groupId      = intval($_POST['groupId']);
	$fieldId  	  = intval($_POST['field']);
	$networkId 	  = intval($_POST['network']);
	if (!empty($groupId)){
		// Update the network connection
		if (!empty($networkId)){
			$update = "UPDATE group_connect SET network_category_id = '$networkId' WHERE group_id = '$groupId' AND field_category_id = '0'";
			mysqli_query($dbc, $update);
		}
		
		// Update the field connection
		if (!empty($fieldId)){
			$update = "UPDATE group_connect SET field_category_id = '$fieldId' WHERE group_id = '$groupId' AND network_category_id = '0'";
			mysqli_query($dbc, $update);
		}
	}
	exit;
}
?>