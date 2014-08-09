<?php
require_once('constant.php');
require_once(CLASS_DIR.'google_api.php');

// If request checkGCal isset, check to see if gcalid is empty and if it isnt, see if the corrent email is in synced
if (isset($_POST['checkGCal'])){
	$email     = $_POST['email'];
	$gcal_id   = $_POST['gcalid'];
	$pageTitle = $_POST['pageTitle'];
	
	if (empty($gcal_id)){
		$data['success']  = false;
		// If pushForm isset, hand back a url, else direct user to Manage Group page
		if (isset($_POST['pushForm'])){
			$authSubUrl   	  = Google_API::getAuthSubUrl();
			$data['message']  = 'Click <a href="'.$authSubUrl.'"><u>here</u></a> to connect with Google Calendar';
		} else{
			$data['message']  = 'Please go to "'.$pageTitle.'" to connect with Google Calendar';	
		}
	} else {
		$googleStatus =  Google_API::checkPermission($gcal_id,$email);
		
		// If googleStatus is true, then we are in synced with the groups email
		if ($googleStatus){
			$data['success'] = true;
			$data['message'] = "Connected with ".$email." Google calendar";
		} else{
			$data['success'] = false;
			// If pushForm isset, hand back a url, else direct user to Manage Group page
			if (isset($_POST['pushForm'])){
				$authSubUrl   	  = Google_API::getAuthSubUrl();
				$data['message']  = 'Click <a href="'.$authSubUrl.'" target="_parent"><u>here</u></a> to connect with '.$email.' Google Calendar';
			} else{
				$data['message']  = 'Please go to "'.$pageTitle.'" to connect with '.$email.' Google Calendar';
			}
		}
	}
	echo json_encode($data);
}
?>