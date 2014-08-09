<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();
require_once(CLASS_DIR.'event.php');

$search = (isset($_POST['s'])) ? mysqli_real_escape_string($dbc,trim($_POST['s'])) : false;
?>
<div id="eventResult">
    <div class="pageHd">Events</div>
    <div>You searched for <strong><?php echo $search; ?></strong></div>
    <ul id="feedList">
    <?php
    if ($search){
        // Search for all events and limit search to 15
        $select = "SELECT event_id FROM search_events WHERE event_name LIKE '%".$search."%' LIMIT 10";
        $result  = mysqli_query($dbc,$select);
	    if (mysqli_num_rows($result) > 0){

            $events = array();
            // Loop through all events, create an event object and push it to the array
            while ($event_row = mysqli_fetch_assoc($result)){
                $eventId   = intval($event_row['event_id']);
                $event     = new Event($eventId);
                array_push($events, $event);
            }

            get_feedlist(array('events' => $events));
        } else{
            echo '<li class="resultNoti"><div>No matches found, please try again.</div></li>';
        }
    } else{
        echo '<li class="resultNoti"><div>No matches found, please try again.</div></li>';
    }
	?>
    </ul>
<!-- #eventResult -->
</div>