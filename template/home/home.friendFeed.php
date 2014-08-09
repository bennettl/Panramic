<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'fb_api.php');
redirect_logged_out_users();

// Only display the follwing if pagenum is not set
if (!isset($_POST['pageNum'])): ?>
?>
<div id="feedContainer">
	<ul id="timeline"> 
		<li id="timelineText">What's Happening:</li>
		<li><a id="thisWeek" href="#" class="current">This Week</a></li>
		<li><a id="thisMonth" href="#">This Month</a></li>
	</ul>
	<ul id="feedList" class="friendFeed">
<?php endif; ?>
	<?php
	$FB = FB_API::initialize();
	
	// These are the filters for time
	$weekFilter  = "AND ((e.start_date >= CURDATE() AND e.start_date <= DATE_ADD(CURDATE(), INTERVAL 6 DAY))
					OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date >= CURDATE() AND e.rsvp_date <= DATE_ADD(CURDATE(),INTERVAL 6 DAY)))) ) ";
	$monthFilter = "AND (((e.start_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.start_date, '%m') = DATE_FORMAT(CURDATE(), '%m')))
				 	OR  (e.rsvp_date != '0000-00-00' AND ((e.rsvp_date > DATE_ADD(CURDATE(), INTERVAL 6 DAY) AND DATE_FORMAT(e.rsvp_date, '%m') = DATE_FORMAT(CURDATE(),'%m')))) ) ";

	// Select all fb events and use the time filters
	$select = "SELECT e.event_id, e.fb_id FROM events AS e WHERE e.fb_id != '0' ";
	if (isset($_POST['thisMonth'])){
		$select .= $monthFilter;
	} else{
		$select .= $weekFilter;
	}
	$result = mysqli_query($dbc,$select);
	
	// For every event, see if a user's friend is attneing them, if there is, append it to $friendList
	while ($event = mysqli_fetch_assoc($result)){
		$eventId = $event['event_id'];
		$fbId	 = $event['fb_id'];
		
		// Select uid, name from users in event members that matches with user's friends in user's friend list
		$selectEvent	   = "SELECT uid FROM event_member WHERE eid = '".$fbId."' AND rsvp_status = 'attending' AND uid IN( "; 
		$selectFriend	   = 'SELECT uid FROM friendlist_member WHERE flid IN( '; 
		$selectFriendList  = 'SELECT flid FROM friendlist WHERE owner=me()))';
		$fqlQuery 		   = $selectEvent.$selectFriend.$selectFriendList;
		$fbFriendArray	   = $FB->api(array('method' => 'fql.query','query' =>$fqlQuery));
		$count	  		   = count($fbFriendArray);
		// If array is greater than 0, that means a user's friend is attending it
		if ($count > 0){
			$friendList .= ",'".$eventId."'";								
		}
	}
	// Get the substring to remove the first comma
	$friendList = (!empty($friendList)) ? substr($friendList,1) : "''";
	
	// Select all the events the user's friends are attending and group them into distinct events. Order them by most number of friends attending the event by COUNT(ue.event_id)
	$select = "SELECT e.event_id, e.field_category_id
			   FROM events AS e
			   WHERE e.event_id IN ($friendList) 			   
			   GROUP BY e.event_id
			   ORDER BY e.start_date ASC, e.rsvp_date ASC
			   LIMIT 10";
	
	$result = mysqli_query($dbc, $select) or die ("cant query");
	
	get_feedlist(array('events' => $events, 'type' => 'feed'));

	// Only display the follwing if pagenum is not set
	if (!isset($_POST['pageNum'])): ?>
		<div id="showMore">Loading...</div>
	<!-- feedList -->
	</ul>
<!-- feedContainer -->
</div>
<?php endif; ?>