<?php
require_once('connect.php');
require_once(CLASS_DIR.'event.php');
redirect_logged_out_users();

// If update event isset, we validate and update the corresponding information 
if (isset($_POST['updateEventReq'])){		
	$eventId              = intval($_POST['eventId']);
	$event                = new Event($eventId);
	$event->name          = $_POST['name'];
	$event->start_date    = $_POST['startDate'];
	$event->start_time    = $_POST['startTime'];	
	$event->end_date      = $_POST['endDate'];
	$event->end_time      = $_POST['endTime'];
	$event->venue         = $_POST['venue'];
	$event->street        = $_POST['street'];
	$event->locality      = $_POST['locality'];
	$event->region        = $_POST['region'];
	$event->postal        = $_POST['postal'];
	$event->description   = $_POST['description'];
	$event->free_food     = $_POST['freeFood'];
	$args                 = array('type' => 'update');
	
	// If there are no errors, then update the event information and if there is a new image, replace and resize it
	if ($event->valid($args)){
		$event->update();

		// Update connections 
		if (isset($_POST['field']) || isset($_POST['network'])){
			$args               = array();
			$args['field_id']   = $_POST['field'];
			$args['network_id'] = $_POST['network'];
			$event->updateConnections($args);
		}

		$data['response'] = "Event has been successfully updated";
	} else{
		$data['response'] = $event->errorMsg;
	}
	echo json_encode($data);
}


// If confirmEvent isset, we remove the image if it's not the groups image and we delete all rows in tables related to this event
if (isset($_POST['confirmEvent'])){	
	$eventId = intval($_POST['eventId']);
	$event   = new Event($eventId);
	$event->confirm();
}
?>