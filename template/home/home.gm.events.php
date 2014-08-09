<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'network.php');
require_once(CLASS_DIR.'field.php');

$groupId    = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
$group      = new Group($groupId);
$group->setAPI(); 	// Sets API Settings
$pushFb     = ($group->push_fb == 'yes') ? true : false;
$pushGoogle = ($group->push_google == 'yes') ? true : false;
$thisMonth  = date('m',time());

// Only echo the following if month is not set
if (!isset($_POST['month'])): ?>
	<div class="title" style="margin: 10px 0 0 0;">Manage</div>
	<div class="titleDivider"></div>
	<div id="events">
		<ul id="evtTimeline"> 
			<li><a href="#<?php echo $group->id;?>" id="tab_jan" <?php if ($thisMonth == '01') {echo 'class="current" '; } ?>>Jan</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_feb" <?php if ($thisMonth == '02') {echo 'class="current" '; } ?>>Feb</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_mar" <?php if ($thisMonth == '03') {echo 'class="current" '; } ?>>March</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_apr" <?php if ($thisMonth == '04') {echo 'class="current" '; } ?>>April</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_may" <?php if ($thisMonth == '05') {echo 'class="current" '; } ?>>May</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_june" <?php if ($thisMonth == '06') {echo 'class="current" '; } ?>>June</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_july" <?php if ($thisMonth == '07') {echo 'class="current" '; } ?>>July</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_aug" <?php if ($thisMonth == '08') {echo 'class="current" '; } ?>>Aug</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_sept" <?php if ($thisMonth == '09') {echo 'class="current" '; } ?>>Sept</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_oct" <?php if ($thisMonth == '10') {echo 'class="current" '; } ?>>Oct</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_nov" <?php if ($thisMonth == '11') {echo 'class="current" '; } ?>>Nov</a></li>
			<li><a href="#<?php echo $group->id;?>" id="tab_dec" <?php if ($thisMonth == '12') {echo 'class="current" '; } ?>>Dec</a></li>
		</ul>
		<div id="notiContainer" style="clear: left; padding-top: 5px;">
			<p id="eventNoti"></p>				
		</div>
		<ul id="formContainer"><li></li></ul>
<?php endif; ?>
<ul id ="evtList" class="mediumList">
	<?php
	$args = array();
	// Depending on which month isset, we will modify $moth accordingly
	$monthArray = array('jan' =>'01','feb'=>'02','mar'=>'03','apr'=>'04','may'=>'05','june'=>'06','july'=>'07','aug'=>'08','sept'=>'09',
						'oct'=>'10','nov'=>'11','dec'=>'12');
	$month    = (isset($_POST['month'])) ? $monthArray[$_POST['month']] :  date('m',time());
	$events = $group->getEvents(array('month' => $month)); // get all events that belong to group, filter by month

	foreach ($events as $event):
		$rsvpDate  = ($event->rsvp_date == "0000-00-00") ? '' : date('m/d/Y',strtotime($event->rsvp_date));
		$startDate = ($event->start_date == "0000-00-00") ? '' : date('m/d/Y',strtotime($event->start_date));
		$endDate   = ($event->end_date == "0000-00-00") ? '' : date('m/d/Y',strtotime($event->end_date));			
		$rsvpTime  = ($event->rsvp_time == "-00:00:01") ? '-1' : $event->rsvp_time;
		$startTime = ($event->start_time == "-00:00:01") ? '-1' : $event->start_time;
		$endTime   = ($event->end_time == "-00:00:01") ? '-1' : $event->end_time;
		$time      = time();
		$thumbnail = $event->thumbnail.'?'.$time;
		$miniclass = 'miniEvt e'.$event->id.' g'.$event->group_id;
	?>
		
		<li class="<?php echo $miniclass; ?>"><img src="<?php echo $thumbnail; ?>" /><div class="listName" style="padding-bottom: 10px;"><?php echo $event->name; ?></div></li>	
		<li class="formList">
		<form class="evtForm" enctype="multipart/form-data" action="ajax/ajax.groupManage.php" target="evtFormFrame" method="post">
		<table>
			<tr>
				<th class="label"><label for="name">Name:</label></th>
				<td><input class="inputText peText" type="text" name="name" autocomplete="off" value="<?php echo $event->name; ?>"/></td>
			</tr>
			<tr>
				<th class="label">RSVP By:</th>
				<td>
					<input class="inputText dateInput" type="text" name="rsvpDate" autocomplete="off" value="<?php echo $rsvpDate; ?>" /> 
					<select name="rsvpTime">
						<?php get_form_time($rsvpTime); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="startDate">Starts:</label></th>
				<td>
					<input class="inputText dateInput" type="text" name="startDate" autocomplete="off" value="<?php echo $startDate; ?>"/> 
					<select class="startTimeInput" name="startTime">
						<?php get_form_time($startTime);  ?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="endTime">Ends:</label></th>
				<td>
					<input class="inputText dateInput" type="text" name="endDate" autocomplete="off" value="<?php echo $endDate; ?>" /> 
					<select class="endTime" name="endTime">
						<?php get_form_time($endTime);  ?>
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
				<td>
					<img class="miniEvtImg" src="<?php echo $thumbnail; ?>"/>
					<div class="miniEvtFile">
						<div class="fileContainer">
							<input class="inputText fileText" type="text" />
							<input class="inputFile" type="file" name="evtImg"/>
							<input class="inputSubmit fileSubmit" type="button" value="Browse" />
							<a class="cancelBtn" href="#">Cancel</a>						
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th class="label"><label for="freeFood">Free Food:</label></th>
				<td>
					<input <?php if ($event->free_food == 'no'){ echo 'checked="checked"';}  ?> type="radio" name="freeFood" value="no" /><label for="foodNo"> No</label>
					<input <?php if ($event->free_food == 'yes'){ echo 'checked="checked"';}  ?> type="radio" name="freeFood" value="yes" /><label for="foodYes"> Yes</label>
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
						$xPos 	     = ($event->network_id - 1) * -33;
						echo '<li><i class="Network | '.Network::get_name($event->network_id).'" style="background: url(css/images/mini/mini.png) no-repeat '. $xPos .'px 0px;"></i></li>';
						$xPos 	     = ($event->field_id - 1) * -33;
						$yPos		 =  -31;
						echo '<li><i class="Field | '.Field::get_name($event->field_id).'" style="background: url(css/images/mini/mini.png) no-repeat '. $xPos .'px '.$yPos.'px;"></i></li>';
						?>
					</ul>
				</td>
			</tr>
			<tr style="display:none;">
				<td colspan="2">
					<input type="hidden" name="eventId" value="<?php echo $event->id; ?>" />
					<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
					<input type="hidden" name="fbstatus" value="false" />
					<input type="checkbox" name="pushFb" <?php if ($pushFb){echo 'checked="checked"';} ?> />
					<input type="hidden" name="gcalid" value="<?php echo $group->gcal_id; ?>" />
					<input type="hidden" name="gstatus" value="false" />
					<input type="checkbox" name="pushGoogle" <?php if ($pushGoogle){echo 'checked="checked"';} ?>/>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top:5px">
					<input class="inputSubmit" type="submit" name="updateEvent" value="Update" />
				</td>
			</tr>
		</table>
		</form>
		<!-- .evtForm ends here -->
		</li>
	<?php endforeach; ?>
	<!-- #evtList -->
</ul>
<?php
// Only echo the following if month is not set
if (!isset($_POST['month'])):
	echo'
		<p class="noti pageNoti">There are currently no events</p>
		<p id="fbNoti" class="noti"></p>
		<p id="googleNoti" class="noti"></p>';
		
		/*
		<div class="title" style="margin: 200px 0 0 0;">Records</div>
		<div class="titleDivider"></div>
		
		<form id="recordForm" action="home.record.php" method="get">
		<p id="recordNoti" class="noti"></p>
		<table id="recordTable">
			<tr>
				<td colspan="3">
					<p>Download a record of '.$group->name.'\'s events</p>
				</td>
				<tr>
					<td>
						<select name="y">
						<option selected ="selected" value="">Year</option>';
						$currentYear = intval(date('Y',time()));
						// Decrement the values for year and sticky it
						for ($year = $currentYear; $year >= 2011; $year--){
							$nextYear = $year + 1;
							echo '<option value="'.$year.$nextYear.'">'.$year.'-'.$nextYear.'</option>';
						}
						echo'
						</select>
					</td>
					<td>
						<select name="t">
							<option selected ="selected" value="">File Type</option>
							<option value="word">Word</option>
							<option value="excel">Excel</option>
						</select>
					</td>
					<td>
						<input type="hidden" name="g" value="'.$group->id.'" />
						<input class="inputSubmit" type="submit" name="download" value="Download" />
					</td>
			</tr>					
		</table>
		</form>
		*/
		?>
		<form id="checkStatus" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
			<input type="hidden" name="email" value="<?php echo $group->email; ?>" />
		</form>
		<iframe id="evtFormFrame" name="evtFormFrame" src="ajax/ajax.groupManage.php"></iframe>
		<div id="deleteList"><i class="deleteBox"></i></div>
	<!-- #events -->
	</div>
<?php endif; ?>