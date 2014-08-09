<?php
global $dbc, $current_user;
require_once('connect.php'); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'user.php');
redirect_logged_out_users();
?>
<div id="main">
	<div id="generalSect">
		<div class="settingsTitle">General</div>
		<div class="titleDivider"></div>
		<?php
			$current_user->setInfo();
			$dbMonth = substr($current_user->birthday,5,2);
			$dbDate  = substr($current_user->birthday,8,2);
			$dbYear  = substr($current_user->birthday,0,4);
		?>
		<form id="generalForm" enctype="multipart/form-data" action="ajax/ajax.account.php" target="generalFrame" method="post">
			<p id="generalNoti" class="noti"></p>
			<table class="settings">
			<tbody>
				<tr>
					<th>Name:</th>
					<td>
						<input type="text" class="inputText" name="firstname" style="margin-right: 5px;" autocomplete="off" value="<?php echo $current_user->first_name; ?>" />
						<input type="text" class="inputText" name="lastname" autocomplete="off" value="<?php echo $current_user->last_name; ?>" />
					</td>
				</tr>
				<tr>
					<th>Username:</th>
					<td>
					<?php
					if ($current_user->href_change == 'no'){
						echo'
						<span> Note: You can only change your username once</span>
						<input type="text" class="inputText genText" name="username" autocomplete="off"  value="'.$current_user->href_name.'" /> 
						<a href="'.MAIN_URL.$current_user->href_name.'" class="url">'.MAIN_URL.$current_user->href_name.'</a>
						<a id="availCheck" href="#">Available?</a><span id="availRe"></span><br />';
					} else {
						echo '<a href="'.MAIN_URL.$current_user->href_name.'" class="url">'.MAIN_URL.$current_user->href_name.'</a>';
					}
					?>
					</td>
				</tr>
				<tr>
					<th>Email:</th>
					<td colspan="2"><input type="text" class="inputText genText" name="email" autocomplete="off" value="<?php echo $current_user->email; ?>" /></td>
				</tr>
				<tr>
					<th>Hometown:</th>
					<td colspan="2"><input type="text" class="inputText genText" name="hometown" autocomplete="off" value="<?php echo $current_user->hometown; ?>" /></td>
				</tr>
				 <tr>
					<th>Birthday:</th>
						<td colspan="">
							<select name="month">
								<option <?php if ($dbMonth == '00') {echo "selected = 'selected'";} ?> value='00'>Month</option>
								<option <?php if ($dbMonth == '01') {echo "selected = 'selected'";} ?> value='01'>January</option>
								<option <?php if ($dbMonth == '02') {echo "selected = 'selected'";} ?> value='02'>February</option>
								<option <?php if ($dbMonth == '03') {echo "selected = 'selected'";} ?> value='03'>March</option>
								<option <?php if ($dbMonth == '04') {echo "selected = 'selected'";} ?> value='04'>April</option>
								<option <?php if ($dbMonth == '05') {echo "selected = 'selected'";} ?> value='05'>May</option>
								<option <?php if ($dbMonth == '06') {echo "selected = 'selected'";} ?> value='06'>June</option>
								<option <?php if ($dbMonth == '07') {echo "selected = 'selected'";} ?> value='07'>July</option>
								<option <?php if ($dbMonth == '08') {echo "selected = 'selected'";} ?> value='08'>August</option>
								<option <?php if ($dbMonth == '09') {echo "selected = 'selected'";} ?> value='09'>September</option>
								<option <?php if ($dbMonth == '10') {echo "selected = 'selected'";} ?> value='10'>October</option>
								<option <?php if ($dbMonth == '11') {echo "selected = 'selected'";} ?> value='11'>November</option>
								<option <?php if ($dbMonth == '12') {echo "selected = 'selected'";} ?> value='12'>December</option>
							</select>
							<select name="date">
								<option <?php if ($dbDate == '00') {echo "selected = 'selected'";} ?> value="00">Date</option>
								<?php
								// Increment the options for date and sticky it. f it is less than 10, then we concatenate a 0 on the first digit
								for ($date = 1; $date <= 31; $date++){
									if ($date < 10){
										$date = "0" + '\''. $date . '' ; 
									}
									if ($dbDate == $date) {
										echo '<option selected ="selected" value="'.$date.'">'.$date.'</option>';
									} else{
										echo '<option value="'.$date.'">'.$date.'</option>';
									}
									$date = intval($date);
								}
								?>
							</select>
							<select name="year">
								<option <?php if ($dbYear == '00') {echo "selected = 'selected'";} ?> value="0000">Year</option>
								<?php
								// Decrement the values for year and sticky it
								for ($year= 2011; $year >= 1950; $year--){
								   if ($dbYear == $year) {
										echo '<option selected ="selected" value="'.$year.'">'.$year.'</option>';
									} else{
										echo '<option value="'.$year.'">'.$year.'</option>';
									}                       
								}
								?>
							</select> 
					   </td>
					</tr>
					<tr>
						<th>Sex:</th>
						<td>
							<select name="sex">
								<option <?php if ($current_user->sex == "-1"){ echo' selected="selected" '; } ?> value="-1">Sex</option>
								<option <?php if ($current_user->sex == "Male"){ echo' selected="selected" '; } ?> value="male">Male</option>
								<option <?php if ($current_user->sex == "Female"){ echo' selected="selected" '; } ?> value="female">Female</option>
							</select>
						</td>
					 </tr>
					 <tr>
						<th>Photo:</th>
						<td>
							<i id="profileImg" style="background: url('images/users/p<?php echo $current_user->id.'.jpg?'.time(); ?>') no-repeat 50% 30%;" /> </i>
							<div class="fileContainer">
								<input class="inputText fileText" type="text" />
								<input class="inputFile" type="file" name="profileImg" />
								<input class="inputSubmit fileSubmit" type="button" value="Browse" />
								<a class="cancelBtn" href="#">Cancel</a>						
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<input type="submit" class="inputSubmit" name="updateGeneral" value="Update" />
		</form>
		<iframe id="generalFrame" name="generalFrame" src="ajax/ajax.account.php"></iframe>
	<!-- generalSect -->
	</div>
	
	<div id="passSect">
		<div class="settingsTitle">Password</div>
		<div class="titleDivider"></div>
		<form id="passForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<table class="settings">
				<tbody>
					<tr>
						<th></th>
						<td><div id="passNoti" class="noti"></div></td>
					</tr>
					<?php
					if ($current_user->password == 'N/A'){
						echo '<input type="hidden" name="oldPass" value="N/A"/>';
					} else {
						echo'
						<tr>
							<th>Old Password:</th>
							<td><input type="password" class="inputText passText" name="oldPass" autocomplete="off" /></td>
						</tr>';
					}
					?>
					<tr>
						<th>New Password:</th>
						<td colspan="2"><input type="password" class="inputText passText" name="newPass" autocomplete="off" /></td>
					</tr>
					<tr>
						<th>Verify Password:</th>
						<td colspan="2"><input type="password" class="inputText passText" name="newPassV" autocomplete="off"  /></td>
					</tr>
				</tbody>
			</table>
			<input type="submit" class="inputSubmit" name="updatePass" value="Update" />
		</form>
	<!-- passSect -->
	</div>
    <!--
	<div id="lookSect">
		<div class="settingsTitle">Look</div>
		<div class="titleDivider"></div>
		<p id="lookNoti" class="noti">Look successfully updated</p>
		<p>Customize your homepage layout and groups filter</p>
		<form id="lookForm" action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="post">
			<table class="settings">
				<tr>
					<th>Layout:</th>
					<td class="cellLeft">
						<input <?php //if ($look == 'minimalistic'){ echo' checked="checked" ';} ?> type="radio" name="look" value="minimalistic" /><label for="look">Minimalistic</label>
					</td>
					<td>
						<input <?php //if ($look == 'regular'){ echo' checked="checked" ';} ?> type="radio" name="look" value="regular" /><label for="look">Regular</label>
					</td>
				</tr>
			</table>
			<input type="submit" class="inputSubmit" name="updateLook" value="Update" />
		</form>
	<- lookSect 
	</div> -->
	
	<div id="privacySect">
		<div class="settingsTitle">Privacy</div>
			<div class="titleDivider"></div>
			<p id="privacyNoti" class="noti">Privacy successfully updated</p>
			<p>Who can see your general information and future events</p>
			<form id="privacyForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<table class="settings">
				<tr>
					<th>Scope:</th>
					<td style="wid?> h:110px; float: left;">
						<input <?php if ($current_user->privacy == 'friends'){ echo' checked="checked" ';} ?> type="radio" name="privacy" value="friends" /><label for="privacy">Friends only</label>
					</td>
					<td>
						<input <?php if ($current_user->privacy == 'everyone'){ echo' checked="checked" ';} ?> type="radio" name="privacy" value="everyone" /><label for="privacy">Everyone</label>
					</td>
				</tr>
			</table>
			<input type="submit" class="inputSubmit" name="updatePrivacy" value="Update" />
			</form>
	<!-- privacySect -->
	</div>
	
	<div id="notiSect">
		<div class="settingsTitle">Notifications</div>
			<div class="titleDivider"></div>
			<p id="notiNoti" class="noti">Notifications successfully updated</p>
			<p id="fbNoti" class="noti"></p>
			<p>Turn off or on notfications for events</p>
			<form id="notiForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table class="settings">
					<tr>
						<th>Email:</th>
						<td style="width:70px; float: left;">
							<input <?php if ($current_user->email_push == 'yes'){ echo' checked="checked" ';} ?> type="radio" name="emailPush" value="yes" /><label for="noti"> On </label>
						</td>
						<td>
							<input <?php if ($current_user->email_push == 'no'){ echo' checked="checked" ';} ?> type="radio" name="emailPush" value="no" /><label for="noti"> Off </label>
						</td>
					</tr>
					<tr>
						<th>Facebook:</th>
						<td style="width:70px; float: left;">
							<input <?php if ($current_user->fb_push == 'yes'){ echo' checked="checked" ';} ?> type="radio" name="pushFb" value="yes" /><label for="gFilter"> On </label>
						</td>
						<td>
							<input <?php if ($current_user->fb_push == 'no'){ echo' checked="checked" ';} ?> type="radio" name="pushFb" value="no" /><label for="gFilter"> Off </label>
						</td>
					</tr>
				</table>
				<input type="submit" class="inputSubmit" name="updateNoti" value="Update" />
			</form>
	<!-- notiSect -->
	</div>
<!-- #main -->   
</div>