<?php
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'database.php');

$groupId = intval($_POST['groupId']);
$group   = new Group($groupId);

// If user is admin of group or user is staff
if ($group->hasAdmin($current_user) || $current_user->is_staff()):

	// If aboutUsUpdate isset, then we check to see if the href is already taken before we update the group information
	if (isset($_POST['aboutUsUpdate'])){
		$group->name        = $_POST['name'];
		$group->email       = $_POST['email'];
		$group->street      = $_POST['street'];
		$group->venue       = $_POST['venue'];
		$group->locality    = $_POST['locality'];
		$group->region      = $_POST['region'];
		$group->postal      = $_POST['postal'];
		$group->description = $_POST['description'];
		$group->href_name   = $_POST['url'];
		$changeHref         = (($group->href_change == 'no') && ($group->href_name != $_POST['url'])) ? true : false;
		$args               = array('type'=> 'update', 'changeHref' => $changeHref);
		// If there are nor errors and href is clear, then we proceed
		if ($group->valid($args)){
			$group->updateInfo($args);
			$data['response'] = "Information updated";
		} else{
			$data['response'] = $group->errorMsg;
		}
		echo json_encode($data);
		exit;
	}

	// If availCheckGroup isset, then we check to see if the url is available
	if (isset($_POST['availCheckGroup'])){
		$href_name = $_POST['url'];

		if ($group->href_name == $href_name){
			$array['success']   = "same";
			$array['response']  = "This is your current url";
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

	// If pushUpdate isset, then group_push and group_calendar
	if (isset($_POST['pushUpdate'])){
		$group->push_site   = (isset($_POST['pushSite'])) ? 'yes' : 'no' ;
		$group->push_fb     = (isset($_POST['pushFb'])) ? 'yes' : 'no' ;
		$group->push_google = (isset($_POST['pushGoogle'])) ? 'yes' : 'no' ;
		$group->style       = $_POST['calStyle'];
		$group->background  = strtoupper($_POST['bgColor']);
		$group->header_bg   = strtoupper($_POST['hdBg']);
		$group->header_text = strtoupper($_POST['hdText']);
		$group->border      = strtoupper($_POST['borderColor']);
		$group->label       = strtoupper($_POST['labelColor']);
		$group->text        = strtoupper($_POST['textColor']);
		$group->updateAPIandCal();
		exit;
	}

	// If upload cal isset, check to see if group has a gcal_id and permission is granted. Then upload the calendar events
	if (isset($_POST['uploadCal'])){
		require_once(CLASS_DIR.'google_api.php');	
		$email  	= $_POST['email'];
		$gcal_id  	= $_POST['gcalid'];
		// If group does not havea gcalid, then return a link, else upload the calendar
		if (empty($gcal_id)){
			$authSubUrl   	  = Google_API::getAuthSubUrl();
			$data['message']  = 'Not connected with Google Calendar. Click <a href="'.$authSubUrl.'"><u>here</u></a> to connect';
		} else {
			$google_api = new Google_API($gcal_id);
			$args       = array('group' => $group, 'email' => $email);
			$goole_api->uploadCalendar($args);
			$data['message']  = 'Events successfully uploaded!';
		}
		echo json_encode($data);
		exit;
	}

	// If updatePhoto isset, we check for appropriate file size and type
	if (isset($_POST['updatePhoto'])){
	
		foreach ($_FILES as $name => $array){
			$group->img_name = $name;
			$group->size     = $array['size'];
			$group->img_type = $array['type'];
			$group->tmp_path = $array['tmp_name'];
			$args            = array('type' => 'update');

			if ($group->size > 0 && ($name == 'm' || $name == 's1' || $name == 's2' || $name == 's3')){
				if ($group->validImage()){
					$group->postImage($args);
					$message = "Photos successfully updated!";
				} else{
					$message = $group->errorMsg;
				}
			}	
		}
		// At the end of the day, give the approrpiate message
		if (isset($message)){
			echo'<div id="frameMsg">'.$message.'</div>';
		}
	}

	/* --- #events --- */
	// If update event isset, we validate and update the corresponding information 
	if (isset($_POST['updateEvent'])){		
		$eventId            = intval($_POST['eventId']);
		$event              = new Event($eventId);
		// info
		$event->name        = $_POST['name'];
		$event->description = $_POST['description'];
		$event->free_food   = $_POST['freeFood'];
		// time
		$event->rsvp_date   = $_POST['rsvpDate'];
		$event->rsvp_time   = $_POST['rsvpTime'];	
		$event->start_date  = $_POST['startDate'];
		$event->start_time  = $_POST['startTime'];	
		$event->end_date    = $_POST['endDate'];
		$event->end_time    = $_POST['endTime'];
		// location
		$event->venue       = $_POST['venue'];
		$event->street      = $_POST['street'];
		$event->locality    = $_POST['locality'];
		$event->postal      = $_POST['postal'];	
		// img
		$event->size        = $_FILES['evtImg']['size'];		
		$event->img_type    = $_FILES['evtImg']['type'];	
		$event->tmp_path    = $_FILES['evtImg']['tmp_name'];	
		$errorMsg           = '';
		$args               = array('type' => 'update');
		// API settings
		$group->setAPI();
		$fbid               = $event->fb_id;
		$pushFb             = (isset($_POST['pushFb'])) ? true : false;
		$fbstatus           = ($_POST['fbstatus'] == 'true') ? true : false;
		$gid                = $event->google_id;
		$gcal_id            = $group->gcal_id;
		$gstatus            = ($_POST['gstatus'] == 'true') ? true : false;
		$pushGoogle         = (isset($_POST['pushGoogle'])) ? true : false;
		// Validation on api
		$errorMsg = ($pushFb && !$fbstatus) ? 'Please go to "Manage Group" to connect with Facebook' : $errorMsg;
		$errorMsg = ($pushGoogle && (!$gstatus || empty($gcal_id))) ?'Please go to "Manage Group" to connect with Google Calendar' : $errorMsg;
		
		// If there are no errors, then update the event information and if there is a new image, replace and resize it
		if (empty($errorMsg) && $event->valid($args)){
			pushEventAPI();
			$event->update();
			$message = "Event successfully updated ";
		} else{
			$message = (empty($errorMsg)) ? $event->errorMsg : $errorMsg;
		}
		echo'
			<div id="frameMsg">'.$message.'</div>
			<div id="fbid">'.$fbid.'</div>
			<div id="gid">'.$event->google_id.'</div>';
		exit;
	}


	// group id not require here

	// If removeEvt isset, we remove the image if it's not the groups image and we delete all rows in tables related to this event
	if (isset($_POST['removeEvt'])){
		$group->setAPI();
		$eventId    = intval($_POST['eventId']);
		$event      = new Event($eventId);
		//facebook
		$pushFb     = ($group->push_fb == 'yes') ? true : false;
		$fbstatus   = ($_POST['fbstatus']  == "true") ? true : false;
		$fbid       = $event->fb_id;
		// google
		$pushGoogle = ($group->push_google == 'yes') ? true : false;
		$gstatus    = ($_POST['gstatus']  == "true") ? true : false;
		$google_id  = $event->google_id;
		$gcal_id    = $group->gcal_id;
		
		// If event has a facebook id and user has given permission ($fbstatus), then delete facebook event
		if ($pushFb && !empty($fbid) && $fbstatus){
			require_once(CLASS_DIR.'fb_api.php');
			$args = array('event' => $event, 'type' => 'delete');
			FB_API::pushEvent($args);
		}
		// If user has given us permission ($gstatus), the google id and calendar id of event is not empty, then proceed
		if ($pushGoogle && $gstatus && !empty($google_id) && !empty($gcal_id)){
			require_once(CLASS_DIR.'google_api.php');
			$google_api = new Google_API($gcal_id);
			$args       = array('event' => $event, 'type' => 'delete');
			$google_api->pushEvent($args);
		}
		$event->remove();
		exit;
	}

	/* --- #members --- */

	// If removeMem is set, we loop through each of the member_ids that were selected and delete those rows
	if (isset($_POST['removeMem'])){
		$member_ids = $_POST['member'];
		if (count($member_ids)){
			$group->removeMembers($member_ids);
		}
		exit;
	}

	// If makeMem is set, we loop through each of the member_ids that were selected and update member_status to 'member'
	if (isset($_POST['makeMem'])){
		$member_ids = $_POST['member'];
		if (count($member_ids)){
			$group->changeMemberStatus($member_ids,'member');
		}
		exit;
	}

	// If makeAd is set, we loop through each of the member_ids that were selected and update member_status to 'admin'
	if (isset($_POST['makeAd'])){
		$member_ids = $_POST['member'];
		if (count($member_ids)){
			$group->changeMemberStatus($member_ids,'admin');
		}
		exit;
	}
endif;

// Handles event pushing
function pushEventAPI(){
	global $pushFb, $fbid, $pushGoogle, $gid, $gcal_id, $event, $pushDescription;

	$pushDescription  = str_replace('\r\n',"
",trim($_POST['description']));
	$pushDescription  .= "
	
	To be up to date about our all events and have them pushed to your Facebook news feed, check out ".MAIN_URL;

	// If user wants to push facebook
	if ($pushFb){
		require_once(CLASS_DIR.'fb_api.php');
		$args    = array('event' => $event);
		$success = true;
		//if facebook id is not empty, update it
		if (!empty($fbid)){
			$args['type'] = 'update';
			$success      = FB_API::pushEvent($args);
		}

		// If success fails, then it means the event with this event id does not exist, then create a facebook one
		if (empty($fbid) || !$success){
			$args['type'] = 'post';
			FB_API::pushEvent($args);
		}
	}

	// If user wants to push google and google calendar id, proceed
	if ($pushGoogle && !empty($gcal_id)){
		require_once(CLASS_DIR.'google_api.php');
		$google_api = new Google_API($gcal_id);
		$args       = array('event' => $event);
		$success    = true;

		// If the google id is empty, then create an event, else update it
		if (!empty($gid)){
			$args['type'] = 'update';
			$success =	$google_api->pushEvent($args);
		}
		// Failures means the event with this event id does not exist, then create a new google event
		if (empty($gid) || !$success){
			$args['type'] = 'post';
			$google_api->pushEvent($args);
		}

	}
}
?>