<?php
require_once('connect.php');
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'google_api.php');

// When user clicks 'postEvent' we will insert event information and create network, fields and connection connections. Note pushDescription is made for facebook and google calendar
if (isset($_POST['postEvent'])){
	// info
	$event              = new Event();
	$event->name        = $_POST['name'];
	$event->description = $_POST['description'];
	$event->free_food   = $_POST['freeFood'];
	// location
	$event->venue       = $_POST['venue'];
	$event->street      = $_POST['street'];
	$event->locality    = $_POST['locality'];
	$event->region      = $_POST['region'];
	$event->postal      = $_POST['postal'];
	// time
	$event->rsvp_date   = $_POST['rsvpDate'];
	$event->rsvp_time   = $_POST['rsvpTime'];	
	$event->start_date  = $_POST['startDate'];
	$event->start_time  = $_POST['startTime'];	
	$event->end_date    = $_POST['endDate'];
	$event->end_time    = $_POST['endTime'];
	// connection
	$event->group_id    = $_POST['groupId'];
	$event->field_id    = $_POST['field'];
	$event->network_id  = $_POST['network'];
	$event->fb_id       = $_POST['fbid'];
	// image
	$event->size        = $_FILES['evtImg']['size'];
	$event->img_type    = $_FILES['evtImg']['type'];
	$event->tmp_path    = $_FILES['evtImg']['tmp_name'];
	$args               = array('type' => 'post');
	// Misc variables
	$everyWeek          = (isset($_POST['everyWeek'])) ? true : false;
	// API variables
	$fbstatus           = ($_POST['fbstatus'] == "true") ? true : false;
	$pushFb             = (isset($_POST['pushFb'])) 	 ? true : false;
	$fbImg              = ($_POST['fbImg'] == "true") 	 ? true : false;
	$gstatus            = ($_POST['gstatus'] == "true")  ? true : false;
	$gcal_id      = $_POST['gcalid'];
	$pushGoogle         = (isset($_POST['pushGoogle']))  ? true : false;
	$email              = $_POST['email'];

	// If everyWeek isset, then we prepare the startMonth, startDate, and startYear
	if (isset($_POST['everyWeek'])){
		$startMonth = date('n',strtotime($_POST['startDate']));
		$startDate  = date('j',strtotime($_POST['startDate']));
		$startYear  = date('Y',strtotime($_POST['startDate']));		
		if ($startMonth < 8){
			$errorMsg = "Please select a month between August and December";
		}
		if (empty($_POST['startDate'])){
			$event->errorMsg = "Please select a start date";
		}
	}
	
	// Validation
	if (isset($_POST['everyWeek']) && !empty($_POST['rsvpDate'])){
		$event->errorMsg = "You cannot RSVP every week events";
	}
	// API Validation
	if ($pushFb && !$fbstatus){
		$event->errorMsg = 'Please go to "Manage Group" to connect with Facebook';
	}
	if ($pushGoogle && (!$gstatus || empty($gcal_id))){
		$event->errorMsg = 'Please go to "Manage Group" to connect with Google Calendar';
	}
	if ($pushGoogle && !empty($gcal_id)){
		require_once(CLASS_DIR.'google_api.php');
		$permission   = Google_API::checkPermission($gcal_id, $email);
	  	if (!$permission){
			$event->errorMsg = $email.' is not connected with Google Calendar. Please go to "Manage Group" to connect';
		}
	}
		
	// If there are no errors, proceed
	if ($event->valid($args)){
		if ($everyWeek){
			everyWeek(); // Post every week events, function will determine the facebook push status 
		} else{
			pushEventAPI(); //Will set fb_id/google_id before posting
			$event->post();
		}
		$message = "Your event has been successfully posted";
	} else{
		$message = $event->errorMsg;
	}
	echo '<div id="msg">'.$message.'</div>';
}

// This function handle pushing events to facebook and google calendar. Error checks are already handled
function pushEventAPI(){
	global $event, $pushFb, $pushGoogle, $gcal_id, $pushDescription;

	$pushDescription    = str_replace('\r\n',"
",trim($event->description));
	
	// If user wants to pushFb, then push it.
	if ($pushFb && empty($event->fb_id)){
		require_once(CLASS_DIR.'fb_api.php');
		$args = array('event' => $event, 'type' => 'post');
		FB_API::pushEvent($args);
	}
	
	// If user wants to push google, then push it.
	if ($pushGoogle){
		require_once(CLASS_DIR.'google_api.php');
		$google_api       = new Google_API($gcal_id);
		$args = array('event' => $event, 'type' => 'post');
		$google_api->pushEvent($args);
	}
}

// This function loops through all the months, taking into consideration the maxDates for each of them, and posts events
function everyWeek(){
	global $event, $pushFb, $fbstatus, $startMonth, $startDate, $startYear;
	for ($month = $startMonth; $month < 13; $month++){
		$maxDate  = array('8' => 32, '9' => 31, '10' => 32, '11' => 31, '12' => 11);
		// if this is the initial month, we start at startDate, otherwise, we start at the dateRemainer
		if ($month == $startMonth){
			for ($date = $startDate; $date < $maxDate[$month]; $date += 7){
				$sqlMonth          = ($month < 10)? '0'.$month: $month;
				$sqlDate           = ($date < 10) ? '0'.$date : $date;
				$weeklyDate        = $startYear."-".$sqlMonth."-".$sqlDate;
				$endDate           = $weeklyDate;
				$event->start_date = $weeklyDate;
				$event->end_date   = $endDate;
				pushEventAPI();  //Will set fb_id/google_id before posting
				$event->post();
			}
		} else {
			for ($date = $dateRemainder; $date < $maxDate[$month]; $date += 7){
				$sqlMonth          = ($month < 10) ? '0'.$month: $month;
				$sqlDate           = ($date < 10) ? '0'.$date : $date;
				$weeklyDate        = $startYear."-".$sqlMonth."-".$sqlDate;
				$endDate           = $weeklyDate;
				$event->start_date = $weeklyDate;
				$event->end_date   = $endDate;
				pushEventAPI();  //Will set fb_id/google_id before posting
				$event->post();
			}
		}
		$dateRemainder = $date - $maxDate[$month] + 1;
	}
}
?>