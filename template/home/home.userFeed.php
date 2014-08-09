<?php
require_once('template.php');
require_once(CLASS_DIR.'event.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

global $dbc, $current_user;

// If facebook isset, check to see if user is on the facebook app or not
if (isset($_POST['facebook'])){
	$facebook = ($_POST['facebook'] == "true") ? true : false;
}
// Depending if user is on facebook, set the href for the timeline accordingly (when user clicks on timeline, the href will be a parameter revealing if user is on facebook or not. This will modify the group href when user requests for feed
$fb = ($facebook) ? "#facebook" : "#";

// Only display the follwing if pagenum is not set
if (!isset($_POST['pageNum'])): ?>
<div id="feedContainer">
	<ul id="timeline">
		<li id="timelineText">What's Happening:</li>
		<li><a id="thisWeek" href="<?php echo $fb; ?>" class="current">This Week</a></li>
		<li><a id="thisMonth" href="<?php echo $fb; ?>">This Month</a></li>
		<li><a id="myCal" href="<?php echo $fb; ?>"><i id="calImg" /></i> <span id="calText">My Calendar</span></a>
		<?php
		$select = "SELECT 1 
				   FROM user_events_$current_user->letter AS ue 
				   INNER JOIN events AS e
				   ON (ue.event_id = e.event_id)
				   WHERE ue.user_id = '$current_user->id' AND ue.user_event_status = 'attend' AND (e.start_date = CURDATE() OR e.rsvp_date = CURDATE())";
		$result = mysqli_query($dbc,$select);
		$count  = mysqli_num_rows($result);
		if ($count > 0){
			echo'<span id="calNoti">'.$count.'</span></li>';
		}
		?>
	<!-- timeline -->
	</ul>
	<ul id="feedList" class="userFeed">
<?php endif; ?>
		<?php
		// Start selection base on user_events
		$selectInit = "SELECT ue.event_id
					   FROM user_events_$current_user->letter AS ue
					   INNER JOIN events AS e
					   ON (ue.event_id = e.event_id)
					   WHERE ue.user_id ='$current_user->id' ";
					   
		// If myCal isset, only look for events user is attending, else look for normal or like events
		$selectEnd .= (isset($_POST['myCal'])) ? "AND ue.user_event_status = 'attend' " : "AND ue.user_event_status IN ('normal','like') ";			 
					   
		// Depending on which $_POST isset, change the timeline accordingly. For myCal, its anytime in the future. The default will be this week from CURDATE(). Includes rsvp date
		if (isset($_POST['myCal'])){
			$selectEnd .= "AND (e.start_date >= CURDATE())";
		} else if (isset($_POST['thisMonth'])){
			$selectEnd .= "AND (((e.start_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.start_date, '%m') = DATE_FORMAT(CURDATE(),'%m')))
						   OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.rsvp_date, '%m') = DATE_FORMAT(CURDATE(),'%m')))) 
						   ) ";
		} else{
			$selectEnd .= "AND ((e.start_date >= CURDATE() AND e.start_date <= DATE_ADD(CURDATE(), INTERVAL 6 DAY))
						   OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date >= CURDATE() AND e.rsvp_date <= DATE_ADD(CURDATE(), INTERVAL 6 DAY))))
						   ) ";
		}
		
		// Select and order events base on date, network, and likes
		$select =   $selectInit.$selectEnd.
					"GROUP BY event_id
				     ORDER BY network_category_id DESC, start_date ASC, rsvp_date ASC, event_like DESC ";
		
		$select = "SELECT event_id FROM events WHERE event_status = 'pending' ORDER BY network_category_id DESC, start_date ASC, rsvp_date ASC, event_like DESC ";

		// Depending on which page isset, we will start, the default is 0.
		$start   = (isset($_POST['pageNum'])) ? intval($_POST['pageNum']) * 10 : 0;
		$select .= "LIMIT $start,10";
		$result  = mysqli_query($dbc, $select) or die ("Cant query");
		
		$events = array();
		// Loop through all events, create an event object and push it to the array
		while ($event = mysqli_fetch_assoc($result)){
			$eventId   = intval($event['event_id']);
			$event     = new Event($eventId);
			array_push($events, $event);
		}

		get_feedlist(array('events' => $events, 'type' => 'feed'));

	// Display the following if pagenum is not set
	if (!isset($_POST['pageNum'])): ?>
		<div id="showMore">Loading...</div>
	<!-- #feedList -->
	</ul>
<!-- #feedContainer -->
</div>
	<?php endif; ?>
