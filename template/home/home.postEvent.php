<?php
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'field.php');
require_once(CLASS_DIR.'group.php');

// If the groupId isset, then the user is admin/officer of a selected group, else, the user is admin of only one group
if (isset($_POST['groupId'])){
	$groupId = intval($_POST['groupId']);
} else{
	$info = array();
	if ($current_user->is_admin($info)){
		$groupId = $info['group_id'];
	} else{
		exit;
	}
}

$group      = new Group($groupId);
$group->setAPI();
$groupImg   = 'images/groups/m'.$group->id.'.jpg';
$pushFb     = ($group->push_fb  == 'yes') ? true : false;
$pushGoogle = ($group->push_google == 'yes') ? true : false;

// Display differently if user is accessing from an iframe
if (isset($_POST['if'])){
echo'<div id="postEventWall">';
} else{
echo'
<div id="postEventWall" class="pageLayout">
<div class="pageHd">What\'s your event</div>';
}
?>
    <p id="eventNoti" class="noti"></p>
    <p id="fbNoti" class="noti"></p>
    <p id="googleNoti" class="noti"></p>
    <form id="postEventForm" enctype="multipart/form-data" action="ajax/ajax.postEvent.php" target="postEventFrame" method="post">
    <table id="postEventTable">
		<tr>
			<th class="label"></th>
            <td>
				<div class="inputContainer" style="width:278px">
					<div id="pushContainer">
						<a class="pushStatus" href="#pull"> Pull</a>
					</div>
					<div id="push" class="pushdiv">
						<input type="checkbox" name="pushFb" <?php if ($pushFb) { echo ' checked="checked" ';} ?>/><label for="pushFb"> Facebook</label>
						<input type="checkbox" name="pushGoogle" <?php  if ($pushGoogle) { echo ' checked="checked" ';} ?>/><label for="pushGoogle"> Google Calendar</label>
					</div>
					<div id="pull" class="pushdiv">
						<input class="inputText peText fbText inactiveText" type="text" name="fbpull" autocomplete="off" value="Paste Facebook Event Url" />
					</div>
				</div>
			</td>
		</tr>
        <tr>
           <th class="label"><label for="name">Name:</label></th>
            <td>
				<input class="inputText peText" type="text" name="name" autocomplete="off" />
	        	<a href="#" id="rsvpBtn" style="margin-left: 5px;">RSVP</a>
			</td>
        </tr>
		<tr id="rsvpDateRow">
			<th class="label">RSVP By:</th>
			<td>
				<input class="inputText dateInput" type="text" name="rsvpDate" autocomplete="off" /> 
				<select name="rsvpTime">
					<option selected="selected" value="-1"> Select Time</option>
					<?php
					// These loops creates the timer and if the post startime matches any of these time, it will be selected. We also make sure its zero filled and switch the am/pm
					for ($i = 1; $i < 12; $i ++){
						$timeVal = ($i + 12).':00:00';
						echo'<option value="'.$timeVal.'">'.$i.':00 pm</option>';
						
						$timeVal = ($i + 12).':30:00';
						echo'<option value="'.$timeVal.'">'.$i.':30 pm</option>';			 
					}                    
						echo'<option value="00:00:00">12:00 am</option>
							 <option value="00:30:00">12:30 am</option>';
					
					 for ($i = 1; $i < 13; $i ++){
						$timeVal = ($i < 10) ? '0'.($i).':00:00': ($i).':00:00';
						$timeEnd = ($i < 12) ? "am" : "pm";
						echo'<option  value="'.$timeVal.'">'.$i.':00 '.$timeEnd.'</option>';
						
						$timeVal = ($i < 10) ? '0'.($i).':30:00': ($i).':30:00';
						echo'<option value="'.$timeVal.'">'.$i.':30 '.$timeEnd.'</option>';			 
					}
					?>    
				</select>
			</td>
		</tr>
        <tr>
            <th class="label"><label for="startDate">Starts:</label></th>
            <td>
                <input class="inputText dateInput" type="text" name="startDate" autocomplete="off" /> 
                <select class="startTimeInput" name="startTime">
                	<?php
					// These loops creates the timer and if the post startime matches any of these time, it will be selected. We also make sure its zero filled and switch the am/pm
					for ($i = 1; $i < 12; $i ++){
						$timeVal = ($i + 12).':00:00';
						if ($i == 1){
							echo'<option value="'.$timeVal.'" selected="selected">'.$i.':00 pm</option>';					
						} else{
							echo'<option value="'.$timeVal.'">'.$i.':00 pm</option>';
						}						
						$timeVal = ($i + 12).':30:00';
						echo'<option value="'.$timeVal.'">'.$i.':30 pm</option>';			 
					}                    
						echo'<option value="00:00:00">12:00 am</option>
							 <option value="00:30:00">12:30 am</option>';
					
					 for ($i = 1; $i < 13; $i ++){
						$timeVal = ($i < 10) ? '0'.($i).':00:00': ($i).':00:00';
						$timeEnd = ($i < 12) ? "am" : "pm";
						echo'<option  value="'.$timeVal.'">'.$i.':00 '.$timeEnd.'</option>';
						
						$timeVal = ($i < 10) ? '0'.($i).':30:00': ($i).':30:00';
						echo'<option value="'.$timeVal.'">'.$i.':30 '.$timeEnd.'</option>';			 
					}
			    	?>    
                </select>
                <a href="#" id="endTimeBtn" style="margin-left: 5px;">Add End Time</a>
            </td>
        <tr>
        <tr id="endDateRow">
            <th class="label"><label for="endTime">Ends:</label></th>
            <td>
                <input class=" inputText dateInput" type="text" name="endDate" autocomplete="off" /> 
                <select class="endTime" name="endTime">
                	<option selected="selected" value="-1"> Select Time</option>
					<?php
					// These loops creates the timer and if the post startime matches any of these time, it will be selected. We also make sure its zero filled and switch the am/pm
					for ($i = 1; $i < 12; $i ++){
						$timeVal = ($i + 12).':00:00';
						echo '<option value="'.$timeVal.'">'.$i.':00 pm</option>';
						
						$timeVal = ($i + 12).':30:00';
						echo '<option value="'.$timeVal.'">'.$i.':30 pm</option>';				 
					}                    
						echo'<option value="00:00:00">12:00 am</option>
						 	 <option value="00:30:00">12:30 am</option>';
					
					 for ($i = 1; $i < 13; $i ++){
						$timeVal = ($i < 10) ? '0'.($i).':00:00': ($i).':00:00';
						$timeEnd = ($i < 12) ? "am" : "pm";
						echo '<option  value="'.$timeVal.'">'.$i.':00 '.$timeEnd.'</option>';
						
						$timeVal = ($i < 10) ? '0'.($i).':30:00': ($i).':30:00';
						echo '<option value="'.$timeVal.'">'.$i.':30 '.$timeEnd.'</option>';			 
					}
				?>
                </select>
            </td>
        </tr>
        <tr>
            <th class="label"><label for="venue">Venue:</label></th>
            <td><input class="inputText locInput" type="text" name="venue" autocomplete="off" value="<?php echo $group->venue; ?>"/></td>
        </tr>
		<tr>
            <th class="label"><label for="street">Street:</label></th>
            <td>
				<input class="inputText locInput" type="text" name="street" autocomplete="off" value="<?php echo $group->street; ?>"/>
				<label for="locality" style="margin:0 5px 0 8px;"><b>City</b></label>
				<input class="inputText locInput" type="text" name="locality" autocomplete="off" value="<?php echo $group->locality; ?>"/>
				<label for="postal" style="margin:0 5px 0 8px;"><b>Postal</b></label>
				<input class="inputText locInput" style="width:50px;" type="text" name="postal" autocomplete="off" value="<?php echo $group->postal; ?>" />
			</td>
        </tr>
        <tr>
            <th class="label"><label for="evtImg">Photo:</label></th>
            <td>
				<img id="evtImg" src="<?php echo $groupImg; ?>" />
				<div id="evtImgOptions">
					<div id="evtFile">
						<div class="fileContainer">
							<div class="noti">Processing...</div>
							<input class="inputText fileText" type="text" />
							<input class="inputFile" type="file" name="evtImg"/>
							<input class="inputSubmit fileSubmit" type="button" value="Browse" />
							<a class="cancelBtn" href="#">Cancel</a>						
						</div>
				
					</div>
					<a id="previewBtn" href="#">Preview</a>
				</div>
			</td>
        </tr>
		<tr>
            <th class="label"><label for="freeFood">Free Food:</label></th>
            <td>
				<input type="radio" name="freeFood" checked="checked" value="no" /><label for="freeFood"> No</label>
				<input type="radio" name="freeFood" value="yes" /><label for="freeFood"> Yes</label>
            </td>
        </tr>
        <tr>
            <th class="label"><label for="description">Description:</label></th>
            <td><textarea class="inputText modText descriptionText" name="description"></textarea></td>
        </tr>
		<tr>
			<th></th>
			<td><div class="tcContainer"> Characters Left: <span class="tcCount">2000</span></td>
		</tr>
		<tr>
        	<th class="label" style="padding-bottom: 10px;"><label for="everyWeek">Every Week:</label></th>
        	<td style="padding-bottom: 15px;">
				<input type="checkbox" name="everyWeek" style="margin: 0 0 0 5px" /> 
			</td>
        </tr>
        <tr>
            <th class="label" style="vertical-align:top;"><label for="evtGuest" id="evtGuest">Guests:</label></th>
            <td> <?php get_form_guestlist(true); ?>  </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="fbstatus" value="false" />
                <input type="hidden" name="fbid" value="0" />
				<input type="hidden" name="region" value="<?php echo $group->region; ?>"/>
			    <input type="hidden" name="fbImg" value="false" />
				<input type="hidden" name="gstatus" value="false" />
                <input type="hidden" name="gcalid" value="<?php echo $group->gcal_id; ?>" />
                <input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
                <input type="hidden" name="email" value="<?php echo $group->email; ?>" />
                <input id="formReset" type="reset" style="display:none;" />
                <input class="inputSubmit" type="submit" name="postEvent" value="Share" />
            </td>
        </tr>
    </table>
    </form>
    <iframe id="postEventFrame" name="postEventFrame" src="ajax/ajax.postEvent.php" ></iframe>
    <iframe id="previewFrame" name="previewFrame" src="ajax.preview.php" ></iframe>
	<form id="previewForm" enctype="multipart/form-data" action="ajax/ajax.preview.php" target="previewFrame" method="post">
		<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
		<input type="submit" name="previewSubmit" />
	</form>
    <div id="eventNote">
		<p><strong>RSVP: </strong>If your event requires RSVP, you can use this option to specific the deadline for RSVP.</p>
		<p><strong>Guest: </strong>The number next to a network or field tells you how many people will you will reach</p>
		<p><strong>Every Week: </strong>This option will generate the same event, same time, for every week until end of the semester. You can modify these events later on. Your group photo will be the default photo for every week events.</p>
    </div>
<!-- #postEventWall -->
</div>