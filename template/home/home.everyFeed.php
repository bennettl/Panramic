<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

// Only display the follwing if pagenum is not set
if (!isset($_POST['pageNum'])): ?>
<div id="feedContainer">
	<ul id="timeline"> 
		<li id="timelineText">What's Happening:</li>
		<li><a id="thisWeek" href="#" class="current">This Week</a></li>
		<li><a id="thisMonth" href="#">This Month</a></li>
	</ul>
	<ul id="feedList" class="everyFeed">
<?php endif; ?>
		<?php 
		// Select all the events and order them by event_like
		$select = "SELECT e.event_id, e.field_category_id
				   FROM events AS e
				   WHERE e.event_status = 'active' AND e.event_like > '0' ";
		
		$select .= (isset($_POST['freeFood'])) ? "AND e.free_food = 'yes' " : '';
		
		// Depending on which $_POST isset, we will change the timeline accordingly. For myCal, its anytime in the future. The default will be one week from CURDATE(). Includes rsvp date
		if (isset($_POST['thisMonth'])){
			$select .= "AND (((e.start_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.start_date, '%m') = DATE_FORMAT(CURDATE(),'%m')))
					    OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.rsvp_date, '%m') = DATE_FORMAT(CURDATE(),'%m')))) 
					    ) ";		
		} else {
			$select .= "AND ((e.start_date >= CURDATE() AND e.start_date <= DATE_ADD(CURDATE(), INTERVAL 6 DAY))
					    OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date >= CURDATE() AND e.rsvp_date <= DATE_ADD(CURDATE(), INTERVAL 6 DAY))))
		 			    ) ";
		}
		$select	.= "GROUP BY e.event_id
					ORDER BY e.event_like DESC, e.start_date ASC, e.rsvp_date ASC ";
	
		// Depending on which page isset, we will start, the default is 0.
		$start   = (isset($_POST['pageNum'])) ? intval($_POST['pageNum']) * 10 : 0;
		$select .= "LIMIT $start,10";
		$result = mysqli_query($dbc, $select) or die ("cant query");

		$events = array();
		// Loop through all events, create an event object and push it to the array
		while ($event = mysqli_fetch_assoc($result)){
			$eventId   = intval($event['event_id']);
			$event     = new Event($eventId);
			array_push($events, $event);
		}

		get_feedlist(array('events' => $events, 'type' => 'feed'));
		
	// Only display the follwing if pagenum is not set
	if (!isset($_POST['pageNum'])): ?>
		<div id="showMore">Loading...</div>
	<!-- feedList -->
	</ul>
<!-- feedContainer -->
</div>
<?php endif; ?>