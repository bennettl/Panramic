<?php 
//These are already set on main profile page 
global $current_user, $profile_user; 
$profile_user->setConnections('field'); ?>
<div id="sidenavTop">
	<?php
	if ($current_user->is_friend($profile_user->id)){
		// If the user isn't seeing his own profile, then we find events that both the user and profileId is attending
		if (!empty($current_user) && $current_user->id != $profile_user->id){
			$select = "SELECT e.event_id, e.field_category_id
					   FROM user_events_$letter AS ue1
					   INNER JOIN user_events_$profile_user->letter AS ue2
					   ON (ue1.event_id = ue2.event_id)
					   INNER JOIN events AS e
					   ON (ue1.event_id = e.event_id AND e.start_date >= CURDATE())
					   WHERE ue1.user_id = '$profile_user->id' AND ue1.user_event_status = 'attend' AND ue2.user_id = '$current_user->id' AND ue2.user_event_status ='attend'
					   LIMIT 5";

			$result  = mysqli_query($dbc,$select);
			$matches = mysqli_num_rows($result);
			if ($matches > 0){
				echo '
				<div class="miniListTitle" style="padding-top:5px;">Mutual Events</div>
				<ul id= "mutualEvt" class="miniList">';
					while ($event = mysqli_fetch_assoc($result)){
						$eventId	 = htmlentities($event['event_id']);
						$field		 = htmlentities($event['field_category_id']);
						$eventTable  = eventTable($fieldId);
						$select      = "SELECT event_name FROM $eventTable WHERE event_id = '$eventId";
						$result1	 = mysqli_query($dbc,$select);
						$eventInfo   = mysqli_fetch_assoc($result);
						$name		 = htmlentities($eventInfo['event_name']);
						$thumbnail	 = "images/events/e".$eventId.".jpg";
						echo '<li><img alt="'.$name.'" src="'.$thumbnail.'" /></li>';
					}
				echo'
				</ul>
				<div class="sidenavDivider"></div>';
			}
		}
	}
	?>
	<?php if (count($profile_user->fields) > 0): // If this user has fields, show them ?>
		<div class="miniListTitle">Fields</div>
		<ul id="fieldList" class="miniList">
			<?php
			// Loop through fields and display them
			foreach ($profile_user->fields as $field => $fieldId){
				$xPos 	     = ($fieldId - 1) * -33;
				$yPos		 = -31;
				echo '<li value="'.$fieldId.'"><i class="'.$field.'" style="background: url(css/images/mini/mini.png) no-repeat '. $xPos .'px '.$yPos.'px;"></i></li>';
			}
			?>
		</ul>		
		<div class="sidenavDivider"> </div>
	<?php endif; ?>
	<!-- #sidenavTop -->    
	</div>
	<div id="sidenavBottom">
	</div>
<div id="feedbackBtn">site feedback</div>