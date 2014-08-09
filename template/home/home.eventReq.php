<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'network.php');
require_once(CLASS_DIR.'field.php');
redirect_logged_out_users();
?>
<div id="eventReq" class="pageLayout">
<div class="pageHd">Confirm Event</div>
	<p id="eventNoti" class="noti pageNoti">There are currently no new events</p>
	<ul id="formContainer"><li></li></ul>
	<ul id ="evtList" class="mediumList">
	<?php
	// Display all events not cofirmed
	$select = "SELECT event_id FROM events WHERE event_status = 'test'";
	$result = mysqli_query($dbc, $select) or die('cant query');
	
	while ($event = mysqli_fetch_assoc($result)):
		$event_id  = intval($event['event_id']);
		$event     = new Event($event_id);
		$startDate = ($event->start_date == "0000-00-00") ? '' : date('m/d/Y',strtotime($event->start_date));
		$endDate   = ($event->end_date == "0000-00-00" || $event->end_date == "9999-12-12") ? '' : date('m/d/Y',strtotime($event->end_date));
		$startTime = ($event->start_time == "00:00:00") ? '' : $event->start_time;
		$endTime   = ($event->end_time == "00:00:00") ? '' : $event->end_time;
		$time      = time();
		$thumbnail = $event->thumbnail.'?'.$time;
		$miniclass = 'miniEvt e'.$event->id.' g'.$event->group_id;
		?>
		
		<li class="<?php echo $miniclass; ?>"><img src="<?php echo $thumbnail; ?>" /><div class="listName" style="padding-bottom: 10px;"><?php echo $event->name; ?></div></li>	
		<li class="formList">
		<form class="evtForm" enctype="multipart/form-data" action="ajax/ajax.groupManage.php" method="post">
		<table>
			<tr>
				<th class="label"><label for="name">Name:</label></th>
				<td><input class="inputText peText" type="text" name="name" autocomplete="off" value="<?php echo $event->name; ?>"/></td>
			</tr>
			<tr>
				<th class="label">Hosted By:</th>
				<td><a href="<?php echo $event->group_href; ?>"><?php echo $event->group; ?></a><td>
			</tr>
			<tr>
				<th class="label"><label for="startDate">Starts:</label></th>
				<td>
					<input class=" inputText startDateInput" type="text" name="startDate" autocomplete="off"  value="<?php echo $startDate; ?>"/> 
					<select class="startTimeInput" name="startTime">
						<?php get_form_time($startTime);  ?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="endTime">Ends:</label></th>
				<td>
					<input class=" inputText endDateInput" type="text" name="endDate" autocomplete="off" value="<?php echo $endDate; ?>" /> 
					<select class="endTime" name="endTime">
						<?php get_form_time($endTime); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="venue">Venue:</label></th>
				<td><input class="inputText locInput" type="text" name="venue" autocomplete="off" value="<?php echo $event->venue; ?>"/></td>
			</tr>
			<tr>
				<th class="label"><label for="street">Street:</label></th>
				<td>
					<input class="inputText locInput" type="text" name="street" autocomplete="off" value="<?php echo $event->street; ?>"/>
					<label for="locality" style="margin:0 5px 0 8px;"><b>City</b></label>
					<input class="inputText locInput" type="text" name="locality" autocomplete="off" value="<?php echo $event->locality; ?>"/>
					<label for="postal" style="margin:0 5px 0 8px;"><b>Postal</b></label>
					<input class="inputText locInput" style="width:50px;" type="text" name="postal" autocomplete="off" value="<?php echo $event->postal; ?>" />
				</td>
			</tr>
			<tr>
				<th class="label"><label for="evtImg">Photo:</label></th>
				<td><img class="miniEvtImg" src="<?php echo $thumbnail; ?>"/></td>
			</tr>
			<tr>
				<th class="label"><label for="evtDescription">Free Food:</label></th>
				<td>
					<input <?php if ($event->free_food == 'no'){ echo 'checked="checked "';}  ?>type="radio" name="freeFood" value="no" /><label for="foodNo"> No</label>
					<input <?php if ($event->free_food == 'yes'){ echo 'checked="checked "';}  ?>type="radio" name="freeFood" value="yes" /><label for="foodYes"> Yes</label>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="description">Description:</label></th>
				<td><textarea class="inputText descriptionText" name="description"><?php echo $event->description; ?></textarea></td>
			</tr>
			<tr>
				<th></th>
				<td><div class="tcContainer">Characters Left: <span class="tcCount">2000</span></td>
			</tr>
			<tr>
				<th class="label"><label for="guests">Guests:</label></th>
				<td>
					<ul class="miniList">
						<?php
						// Display a minilist for the guests
						$networkName  = Network::get_name($event->network_id);
						$xPos      = ($event->network_id - 1) * -33;
						echo '<li><i class="Network | '.$networkName.'" style="background: url(css/images/mini/mini.png) no-repeat '. $xPos .'px 0px;"></i></li>';
						$fieldName = Field::get_name($event->field_id);
						$xPos      = ($event->field_id - 1) * -33;
						$yPos      =  -31;
						echo '<li><i class="Field | '.$fieldName.'" style="background: url(css/images/mini/mini.png) no-repeat '. $xPos .'px '.$yPos.'px;"></i></li>';
						?>
					</ul>
				</td>
			</tr>
			<tr>
				<th></th>
				<td class="guestContainerCell"></td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top:5px">
					<input type="hidden" name="eventId" value="<?php echo $event->id ?>" />
					<input class="inputSubmit" type="submit" name="updateEventReq" value="Update" />
					<input class="inputSubmit" type="submit" name="confirmEvent" value="Confirm" />
				</td>
			</tr>
		</table>
		</form>
		<!-- .evtForm  -->
		</li>
	<?php endwhile;  ?>
	<!-- #evtList -->
	</ul>
	<div id="guestContainer" style="display:none;"><?php get_form_guestList(); ?></div>
	<div id="deleteList"><i class="deleteBox"></i></div>
<!-- #eventReq -->
</div>