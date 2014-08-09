<?php
require_once('connect.php');
require_once(CLASS_DIR.'database.php');

// If availCheck isset, then check to see if the userHref is available or if the user is entering the same href
if (isset($_POST['availCheck'])){
	$href_name = $_POST['username'];

	if ($current_user->href_name == $href_name){
		$array['success']   = "same";
		$array['response']  = "This is your current username";
	} else{
		$exist = Database::hrefExist($href_name);
		// If it doesnt exist, its available 
		if (!$exist){
			$array['success']   = "yes";
			$array['response']  = "Available!";
		}  else {
			$array['success']   = "no";
			$array['response']  = "Not available";
		}
	}
	echo json_encode($array);
	exit;
}

// If generalForm isset, then check if the userHref and email is already taken. If they are do they belong to the user? If everything checks out, then update the information for the user.
if (isset($_POST['updateGeneral'])){
	$current_user->setInfo();
	$current_user->first_name = $_POST['firstname'];
	$current_user->last_name  = $_POST['lastname'];
	$current_user->hometown   = $_POST['hometown'];
	$current_user->birthday   = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
	$current_user->sex        = $_POST['sex'];
	$changeHref               = (($current_user->href_change == 'no') && ($current_user->href_name != $_POST['username'])) ? true : false;
	$changeEmail              = ($current_user->email != $_POST['email']) ? true : false;
	$current_user->href_name  = $_POST['username'];		
	$current_user->email      = $_POST['email'];
	$args                     = array('type'=> 'update', 'changeHref' => $changeHref, 'changeEmail' => $changeEmail);

	// If href_change is no, href isnt the same as old, check if it exist
	if ($changeHref){
		$exist = Database::hrefExist($current_user->href_name);
		$current_user->errorMsg = ($exist) ? 'Username not available' : $current_user->errorMsg;
	}
	// If email isnt the same as old, check if it exist
	if ($changeEmail){
		$exist = User::emailExist($current_user->email);
		$current_user->errorMsg =  ($exist) ?	'Email already exists' : $current_user->errorMsg;
	}
	
	if ($current_user->valid($args)){
		$current_user->updateInfo($args);
		$message = "Information successfully updated";
	} else {
		$message = $current_user->errorMsg;
	}
	
	// // Validate/upload image only if it's size greater than zero
	$size = intval($_FILES['profileImg']['size']);
	
	if ($size > 0){
		$type   = $_FILES['profileImg']['type'];
		$args 	= array('size' => $size, 'type' => $type);
		// If there are no errors resize and replace the images accordingly
		if ($current_user->validImage($args)){
			$args = array('type' => 'update');
			$current_user->postImage($args);
		} else{
			$message = $current_user->errorMsg;
		}
	}
	echo '<div id="msg">'.$message.'</div>';
	exit;
}

// When updatePass isset, check to see if the oldPass matches the database pass. If it is, then check to see if $newPass matches $newPassV. If both are clear, then update the password for the user and give the appropriate response
if (isset($_POST['updatePass'])){
	$oldPass                = $_POST['oldPass'];
	$newPass                = $_POST['newPass'];
	$newPassV               = $_POST['newPassV'];
	$current_user->errorMsg = ($newPass != $newPassV) ? 'New passwords don\'t match' : '';
	$args                   = array('newPass' => $newPass, 'oldPass' => $oldPass, 'type' => 'update');
	
	if ($current_user->changePassword($args)){
		$data['response'] = "Password successfully changed";
	} else{
		$data['response'] = $current_user->errorMsg;
	}
	echo json_encode($data);
	exit;
}

// If updateLook isset, then update the look and group_filter for the user
if (isset($_POST['updateLook'])){
	$look     = mysqli_real_escape_string($dbc,trim($_POST['look']));
	if ($look == 'minimalistic' || $look == 'regular'){
		$update = "UPDATE user_info_$current_user->letter SET look = '$look' WHERE user_id = '$current_user->id'";
		mysqli_query($dbc,$update);
	}
	exit;
}

// If updatePrivacy isset, then update the privacy settings for the user
if (isset($_POST['updatePrivacy'])){
	$privacy = $_POST['privacy'];
	if ($privacy == 'friends' || $privacy == 'everyone'){
		$update = "UPDATE user_info_$current_user->letter SET privacy = '$privacy' WHERE user_id = '$current_user->id'";
		mysqli_query($dbc,$update);
	}
	exit;
}

// If updateNoti isset, then update the privacy settings for the user
if (isset($_POST['updateNoti'])){
	$emailPush  = $_POST['emailPush'];
	$pushFb		= $_POST['pushFb'];
	if (($emailPush == 'yes' || $emailPush == 'no') && ($pushFb == 'yes' || $pushFb == 'no')){
		$update = "UPDATE users AS u
				   INNER JOIN user_info_$current_user->letter AS ui
				   ON (u.user_id = ui.user_id)
				   SET ui.email_push = '$emailPush', u.fb_push = '$pushFb' 
				   WHERE u.user_id = '$current_user->id'";
		mysqli_query($dbc,$update);
	}
	exit;
}

/* --- network --- */
// If changeMajor isset, will delete all events and networks (with a network_category_id of 3) associated with user. then add creat a new network connection in user_connect and find all relevant events to add in user_connect.
if (isset($_POST['changeNet'])){	
	$network_ids = array_filter($_POST['network']); // remove empty elements
	$current_user->removeNetworks();
	$current_user->postNetworks($network_ids);
}

/* --- #field --- */

// If removeField isset, delete the all events relevant to the fields and the connection in user_connect
if (isset($_POST['removeField']) && isset($_POST['field'])){
	$field_ids = $_POST['field'];
	$current_user->removeFields($field_ids);
}

// If addField isset, insert a row in user_connect if there isnt one
if (isset($_POST['addField']) && isset($_POST['field'])){
	$field_ids = $_POST['field'];
	$current_user->postFields($field_ids);
}

/* --- #group --- */
/*
// If unblockGroup isset, delete the row from userblock
if (isset($_POST['unblockGroup'])){
	foreach($_POST['group'] as $groupId){
		$delete = "DELETE FROM user_block WHERE user_id ='$current_user->id' AND group_id ='$groupId'";
		mysqli_query($dbc, $delete);
	}
}*/

?>