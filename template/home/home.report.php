<?php
require_once('connect.php');
redirect_not_staff();
?>
<div id="report" class="pageLayout">
    <div class="pageHd">Report</div>
    <div id="eventReport" class="reportSection">
    	<div class="reportTitle">Events</div>
		<div class="noti pageNoti">There are no new reports</div>
		<ul class="reportContainer">
			<?php
			$select = "SELECT r.report_comment, e.event_id, e.field_category_id, e.group_id, e.start_date, e.end_date, g.group_id, g.group_name, h.href_name AS hgroup, u.first_name, h2.href_name AS huser
					   FROM reports AS r
					   INNER JOIN events AS e
					   ON (r.event_id = e.event_id)
					   INNER JOIN groups AS g
					   ON (e.group_id = g.group_id)
					   INNER JOIN hrefs AS h
					   ON (e.group_id = h.group_id)
					   INNER JOIN users AS u
					   ON (r.user_id = u.user_id)
					   INNER JOIN hrefs AS h2
					   ON (r.user_id = h2.user_id)
					   WHERE r.event_id != '0'";
			$result = mysqli_query($dbc,$select);

			while ($event = mysqli_fetch_assoc($result)):
				$firstname 	  = htmlentities($event['first_name']);
				$username 	  = htmlentities($event['huser']);
				$comment	  = htmlentities($event['report_comment']);
				$eventId 	  = intval($event['event_id']);
				$fieldId	  = intval($event['field_category_id']);
				$groupId 	  = intval($event['group_id']);
				$group		  = htmlentities($event['group_name']);
				$groupUrl	  = htmlentities($event['hgroup']);

				$eventTable	  = eventTable($fieldId);
				// Retrieve more information about the event from the respective table
				$select = "SELECT event_name, start_time, end_time, venue, street, locality, region, postal, lon, lad, event_description FROM $eventTable WHERE event_id = '$eventId'";
				$result1    = mysqli_query($dbc, $select) or die('cant query');
				$eventInfo  = mysqli_fetch_assoc($result1);
				$name 		  = htmlentities($eventInfo['event_name']);
				$startTime 	  = ($eventInfo['start_time']) == '-00:00:01' ? '' : '<span style="color:#C5C5C5">&nbsp; l&nbsp; </span>'.date('g:i a',strtotime($eventInfo['start_time']));
				$endTime 	  = ($eventInfo['end_time']) == '-00:00:01' ? '' : ' - '.date('g:i a',strtotime($eventInfo['end_time']));
				$endTime      = ($eventInfo['start_time'] == $eventInfo['end_time']) ? '' : $endTime;
				$venue	 	  = htmlentities($eventInfo['venue']);
				$thumbnail 	  = "images/events/e".$eventId.".jpg";
				$description  = htmlentities($eventInfo['event_description']);
				
				// If the timeframe is within a week, show the day of the week, else show the date
				$startDiff = ceil((strtotime($event['start_date']) - time())/ (60 * 60 * 24));
				if ($startDiff < 8){
					switch($startDiff){
						case "0":
							$startDate = "Today";
							break;
						case "1":
							$startDate = "Tomorrow";
							break;
						default:
							$startDate = date('l',strtotime($event['start_date']));
							break;
					}
					
									
					$endDiff = ceil((strtotime($event['end_date']) - time())/ (60 * 60 * 24));	
					if ($endDiff < 8){
						switch($endDiff){
							case "0":
								$endDate = " - Today";
								break;
							case "1":
								$endDate = " - Tomorrow";
								break;
							default:
								$endDate = ' - '.date('l',strtotime($event['end_date']));
								break;
						}
					} else{
						$endDate = ' - '.date('F j',strtotime($event['end_date']));
					}
					$endDate = ($event['start_date'] == $event['end_date']) ? '': $endDate;
				}
				
				$feedDate = $startDate.$endDate.$startTime.$endTime;
				?>
				<li class="feed report">
					<div class="reportNoti">Reported By: 
						<a href="<?php echo $username; ?>"><?php echo $firstname; ?></a><br /> <?php echo $comment; ?><br />
					</div>
					<img class="feedImg" src="<?php echo $thumbnail; ?>" />
					<span class="feedName"><?php echo $name; ?></span> 
					<table class="feedInfo">
					 <tr>
						<th colspan="2" class="feedDate"><?php echo $feedDate; ?></th>
					 </tr>
					 <tr>
						<th>Where:</th>
						<td><?php echo $venue; ?></td>
					 </tr>
					 <tr>
						<th>Hosted By:</th>
						<td class="groupName"><a href="<?php echo $groupUrl; ?>"><?php echo $group; ?></a></td>
					 </tr>
					 
					 <tr> 
						<th>Description:</th>
						<td> <div class="feedDescription"><?php echo $description; ?></div></td>
					</tr>
					<!--feedInfo -->
					</table>
					<form class="reportForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="eReport">
						<input type="checkbox" name="ignore" /> <label for="ignore">Ignore</label>
						<input type="checkbox" name="remove" /> <label for="remove">Remove</label>
						<input type="checkbox" name="warn" /> <label for="warn">Warn Group</label>
						<input type="checkbox" name="ban" /> <label for="ban">Ban Group</label>
						<input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
						<input type="hidden" name="groupId" value="<?php echo $groupId; ?>" />
						<input class="inputSubmit reportSubmit" type="submit" name="submit" value="Submit" />
					</form>
					<div class="noti roptionNoti"></div>
				<!-- .feed -->   
				</li>				
			<?php endwhile; ?>
		</ul>
    <!-- #eventsReport -->
    </div> 
<!-- report -->    
</div>