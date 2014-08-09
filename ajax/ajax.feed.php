<?php
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'event.php');

$eventId  = intval($_POST['eventId']);

// Proceed if eventId isnt empty
if (!empty($eventId)){

	// If attendEvent isset, then increment both event_like and event_attend count and set user_event_status to 'attend'
	if (isset($_POST['attendEvent'])){
		$event   = new Event($eventId);
		$rsvp    = ($_POST['rsvp'] == 'true') ? true : false;
		$fbEvent = ($_POST['fbEvent'] == 'true') ? true : false;
					   
		$current_user->attendEvent($event);
		
		// If there it is a facebook event and we have permission, then rsvp on behalf of user
		if ($fbEvent && $rsvp){
			require_once(API_DIR.'facebook.php');
			// If facebook id is not empty, then rsvp the event for user
			$FB = new Facebook(array(
				'appId' => CLIENT_ID,
				'secret' => SECRET,
				'cookie' => true
			));
			
			$select  = "SELECT fb_id FROM events WHERE event_id ='$eventId'";
			$result  = mysqli_query($dbc,$select);
			$event   = mysqli_fetch_assoc($result);
			$fbId    = $event['fb_id'];
			try{
				$FB->api($fbId.'/attending','post');
			} catch(FacebookApiException $e){
			}
		}
		exit;
	}

	// If removeFeed isset, then decrement the attend count and change the user_event_status to 'hidden'
	if (isset($_POST['removeEvent'])){
		$event = new Event($eventId);	
		$current_user->hideEvent($event);
		exit;
	}

	// If removeFeed isset, then decrement the attend count, change the user_event_status to 'hidden', and insert a row in reports
	if (isset($_POST['reportEvent'])){
		$event   = new Event($eventId);
		$comment = $_POST['comment'];
		$args    = array('user' => $current_user, 'comment' => $comment);
		$current_user->hideEvent($event);
		$event->report($args);
		exit;
	}

	// // If evtInvForm and friend isset, then send an invite on the event for each friend the user selected
	// if (isset($_POST['evtInvForm']) && !empty($_POST['friend'])){
	// 	foreach ($_POST['friend'] as $value){
	// 		$friendId = intval($value);
	// 		// Check to see if the user has already been invited to that event, has hidden or attended. If he hasn't then we'll give him an invite
	// 		$select = "SELECT 1 FROM user_invites WHERE user_id ='$friendId' AND event_id ='$eventId'
	// 				   UNION
	// 				   SELECT 1 FROM user_events WHERE user_id ='$friendId' AND event_id ='$eventId' AND user_event_status IN ('hidden','attend')";
	// 		$result = mysqli_query($dbc,$select);
	// 		if (mysqli_num_rows($result) == 0){
	// 			$insert = "INSERT INTO user_invites (user_id, friend_id, event_id) VALUES ('$friendId', '$current_user->id', '$eventId')";
	// 			mysqli_query($dbc,$insert);
	// 		}
	// 	}
	// }
}
?>