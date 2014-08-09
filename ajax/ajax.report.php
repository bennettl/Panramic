<?php
require_once('connect.php');
redirect_not_staff();
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'group.php');

$errorMsg = '';

// Error checks
if (!isset($_POST['ignore']) && !isset($_POST['remove']) && !isset($_POST['warn']) && !isset($_POST['ban'])){
	$errorMsg = "Please select an action";
}
if (isset($_POST['ignore']) && (isset($_POST['remove']) || isset($_POST['warn']) || isset($_POST['ban']))){
	$errorMsg = "In order to ignore, you must uncheck other fields";
}
if (!isset($_POST['remove']) && (isset($_POST['warn']) || isset($_POST['ban']))){
	$errorMsg = "Please check remove as well";
}
if (isset($_POST['remove']) && !isset($_POST['warn']) && !isset($_POST['ban'])){
	$errorMsg = "Please check warn or ban";
}
if (isset($_POST['warn']) && isset($_POST['ban'])){
	$errorMsg = "Can't check both warn and ban";
}

if (empty($errorMsg)){
	// If eReport isset, remove the report and take appropriate action
	if (isset($_POST['eReport'])){
		$eventId  = intval($_POST['eventId']);
		$rgroupId = intval($_POST['groupId']);
		$event    = new Event($eventId);
		$group    = new Group($rgroupId);
		
		// Remove the report
		$delete = "DELETE FROM reports WHERE event_id = '$event->id'";
		mysqli_query($dbc,$delete);
		
		// If warn or ban isset, then retrieve admins to contact later
		if (isset($_POST['warn']) || isset($_POST['ban'])){
			$admins = $group->getMembers(array('member_status' => array('admin')));
		}
		
		// If remove isset, then delete everything related to the content and notify admins
		if (isset($_POST['remove'])){
			$event->remove();
		}
	
		// If warn isset, then send a warning emails to all admins
		if (isset($_POST['warn'])){
			$subject  = "Warning";
			$header   = "From:".ADMIN_EMAIL;
	
			foreach ($admins as $admin){
				$user    = $admin['user'];
				$to      = $user->email;
				$message = "Dear ".$user->first_name.", \n\n This is a warning to let you know a user has reported one of your events. We have taken the appropriate action and if we recieve any further reports regarding events your group host, your group, ".$group->name.", may be banned indefinitely. If you believe your account was compromised by someone else, please let us know and change your password immediately.\n\nFrom\nThe Panramic Team";
				mail($to,$subject,$message,$header);
			}
		}
		
		// If ban isset, then update the group status and send emails to all admins
		if (isset($_POST['ban'])){
			$subject  = "Group Banned";
			$header   = "From:".ADMIN_EMAIL;
			$group->changeStatus('banned');
			
			foreach ($admins as $admin){
				$user    = $admin['user'];
				$to      = $user->email;
				$message = "Dear ".$user->first_name.",\n\nDue to several user reports of the events your group, ".$group->name.", have posted, your group has been banned indefinitely. If you would like to appeal your case, please feel free to contact us. \n\nFrom \nThe Panramic Team";
				mail($to,$subject,$message,$header);
			}
		}
	}
}
?>