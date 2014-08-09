<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
if (empty($groupId)){
	$groupId = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
}
$group      = new Group($groupId);
$group->setAPI(); 	// Sets API Settings
$group->setCalendar(); 	// Set calendar settings base on db

$pushSite   = ($group->push_site == 'yes') ? true : false;
$pushFb     = ($group->push_fb == 'yes') ? true : false;
$pushGoogle = ($group->push_google == 'yes') ? true : false;
$time       = time();
?>
<div id="aboutUs">
    <div class="title">About Us</div>
    <div class="titleDivider"></div>
    <p id="aboutNoti" class="noti"></p>
    <form id="aboutUsForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table id="aboutUsTable">
        <tr>
            <th class="label"><label for="groupName">Name:</label></th>
            <td><input class="inputText" type="text" name="name" autocomplete="off" value="<?php echo $group->name; ?>"/></td>
        </tr>
        <tr>
        	<th class="label">Url:</th>
            <td>
            <?php
			if ($group->href_change == 'no'){
				echo'
				<span>Note: You can only change your url once</span><br/>
				<input type="text" class="inputText" name="url" autocomplete="off" value="'.$group->href_name.'" /> 
                <a href="'.MAIN_URL.$group->href_name.'" class="url">'.MAIN_URL.$group->href_name.'</a>
           		<a id="availCheck" href="#">Available?</a><span id="availRe"></span>';			
			} else{
				echo '<a class="url" href="'.MAIN_URL.$group->href_name.'" target="_blank">'.MAIN_URL.$group->href_name.'</a>';
			}
			?>
			</td>
        </tr>
        <tr>
            <th class="label"><label for="groupEmail">Email:</label></th>
            <td><input class="inputText" type="text" name="email"  autocomplete="off" value="<?php echo $group->email; ?>"/></td>
        </tr>
        <tr>
            <th class="label"><label for="venue">Venue:</label></th>
            <td><input class="inputText" type="text" name="venue" autocomplete="off" value="<?php echo $group->venue; ?>"/></td>
        </tr>
		<tr>
            <th class="label"><label for="location">State:</label></th>
			<td>
				<select name="region">
					<option <?php if ($group->region == 'AL') {echo "selected = 'selected'";} ?> value="AL">Alabama</option> 
					<option <?php if ($group->region == 'AK') {echo "selected = 'selected'";} ?> value="AK">Alaska</option> 
					<option <?php if ($group->region == 'AZ') {echo "selected = 'selected'";} ?> value="AZ">Arizona</option> 
					<option <?php if ($group->region == 'AR') {echo "selected = 'selected'";} ?> value="AR">Arkansas</option> 
					<option <?php if ($group->region == 'CA') {echo "selected = 'selected'";} ?> value="CA">California</option> 
					<option <?php if ($group->region == 'CO') {echo "selected = 'selected'";} ?> value="CO">Colorado</option> 
					<option <?php if ($group->region == 'CT') {echo "selected = 'selected'";} ?> value="CT">Connecticut</option> 
					<option <?php if ($group->region == 'DE') {echo "selected = 'selected'";} ?> value="DE">Delaware</option> 
					<option <?php if ($group->region == 'DC') {echo "selected = 'selected'";} ?> value="DC">District Of Columbia</option> 
					<option <?php if ($group->region == 'FL') {echo "selected = 'selected'";} ?> value="FL">Florida</option> 
					<option <?php if ($group->region == 'GA') {echo "selected = 'selected'";} ?> value="GA">Georgia</option> 
					<option <?php if ($group->region == 'HI') {echo "selected = 'selected'";} ?> value="HI">Hawaii</option> 
					<option <?php if ($group->region == 'ID') {echo "selected = 'selected'";} ?> value="ID">Idaho</option> 
					<option <?php if ($group->region == 'IL') {echo "selected = 'selected'";} ?> value="IL">Illinois</option> 
					<option <?php if ($group->region == 'IN') {echo "selected = 'selected'";} ?> value="IN">Indiana</option> 
					<option <?php if ($group->region == 'IA') {echo "selected = 'selected'";} ?> value="IA">Iowa</option> 
					<option <?php if ($group->region == 'KS') {echo "selected = 'selected'";} ?> value="KS">Kansas</option> 
					<option <?php if ($group->region == 'KY') {echo "selected = 'selected'";} ?> value="KY">Kentucky</option> 
					<option <?php if ($group->region == 'LA') {echo "selected = 'selected'";} ?> value="LA">Louisiana</option> 
					<option <?php if ($group->region == 'ME') {echo "selected = 'selected'";} ?> value="ME">Maine</option> 
					<option <?php if ($group->region == 'MD') {echo "selected = 'selected'";} ?> value="MD">Maryland</option> 
					<option <?php if ($group->region == 'MA') {echo "selected = 'selected'";} ?> value="MA">Massachusetts</option> 
					<option <?php if ($group->region == 'MI') {echo "selected = 'selected'";} ?> value="MI">Michigan</option> 
					<option <?php if ($group->region == 'MN') {echo "selected = 'selected'";} ?> value="MN">Minnesota</option> 
					<option <?php if ($group->region == 'MS') {echo "selected = 'selected'";} ?> value="MS">Mississippi</option> 
					<option <?php if ($group->region == 'MO') {echo "selected = 'selected'";} ?> value="MO">Missouri</option> 
					<option <?php if ($group->region == 'MT') {echo "selected = 'selected'";} ?> value="MT">Montana</option> 
					<option <?php if ($group->region == 'NE') {echo "selected = 'selected'";} ?> value="NE">Nebraska</option> 
					<option <?php if ($group->region == 'NV') {echo "selected = 'selected'";} ?> value="NV">Nevada</option> 
					<option <?php if ($group->region == 'NH') {echo "selected = 'selected'";} ?> value="NH">New Hampshire</option> 
					<option <?php if ($group->region == 'NJ') {echo "selected = 'selected'";} ?> value="NJ">New Jersey</option> 
					<option <?php if ($group->region == 'NM') {echo "selected = 'selected'";} ?> value="NM">New Mexico</option> 
					<option <?php if ($group->region == 'NY') {echo "selected = 'selected'";} ?> value="NY">New York</option> 
					<option <?php if ($group->region == 'NC') {echo "selected = 'selected'";} ?> value="NC">North Carolina</option> 
					<option <?php if ($group->region == 'ND') {echo "selected = 'selected'";} ?> value="ND">North Dakota</option> 
					<option <?php if ($group->region == 'OH') {echo "selected = 'selected'";} ?> value="OH">Ohio</option> 
					<option <?php if ($group->region == 'OK') {echo "selected = 'selected'";} ?> value="OK">Oklahoma</option> 
					<option <?php if ($group->region == 'OR') {echo "selected = 'selected'";} ?> value="OR">Oregon</option> 
					<option <?php if ($group->region == 'PA') {echo "selected = 'selected'";} ?> value="PA">Pennsylvania</option> 
					<option <?php if ($group->region == 'RI') {echo "selected = 'selected'";} ?> value="RI">Rhode Island</option> 
					<option <?php if ($group->region == 'SC') {echo "selected = 'selected'";} ?> value="SC">South Carolina</option> 
					<option <?php if ($group->region == 'SD') {echo "selected = 'selected'";} ?> value="SD">South Dakota</option> 
					<option <?php if ($group->region == 'TN') {echo "selected = 'selected'";} ?> value="TN">Tennessee</option> 
					<option <?php if ($group->region == 'TX') {echo "selected = 'selected'";} ?> value="TX">Texas</option> 
					<option <?php if ($group->region == 'UT') {echo "selected = 'selected'";} ?> value="UT">Utah</option> 
					<option <?php if ($group->region == 'VT') {echo "selected = 'selected'";} ?> value="VT">Vermont</option> 
					<option <?php if ($group->region == 'VA') {echo "selected = 'selected'";} ?> value="VA">Virginia</option> 
					<option <?php if ($group->region == 'WA') {echo "selected = 'selected'";} ?> value="WA">Washington</option> 
					<option <?php if ($group->region == 'WV') {echo "selected = 'selected'";} ?> value="WV">West Virginia</option> 
					<option <?php if ($group->region == 'WI') {echo "selected = 'selected'";} ?> value="WI">Wisconsin</option> 
					<option <?php if ($group->region == 'WY') {echo "selected = 'selected'";} ?> value="WY">Wyoming</option>                
				</select>
			</td>
		</tr>
		<tr>
            <th class="label"><label for="street">Street:</label></th>
            <td>
				<input class="inputText" type="text" name="street" autocomplete="off" value="<?php echo $group->street; ?>"/>
				<label for="locality" style="margin:0 5px 0 8px;"><b>City</b></label>
				<input class="inputText" type="text" name="locality" autocomplete="off" value="<?php echo $group->locality; ?>"/>
				<label for="postal" style="margin:0 5px 0 8px;"><b>Postal</b></label>
				<input class="inputText" style="width:50px;" type="text" name="postal" autocomplete="off " value="<?php echo $group->postal; ?>" />
			</td>
        </tr>
        <tr>
            <th class="label" style="vertical-align:top; padding-top: 10px;"><label for="description">Description:</label></th>
            <td><textarea name="description" class="inputText descriptionText"><?php echo $group->description; ?></textarea></td>
        </tr>
        <tr>
			<th></th>
        	<td>
				<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
				<input class="inputSubmit" style="float:right;" type="submit" name="updateGroup" value="Update" />
			</td>
        </tr>
    </table>
    </form>
	
	<div class="title">Push</div>
	<div class="titleDivider"></div>
	<form id="pushForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<p id="pushNoti" class="noti" style="margin: 0 0 10px 5px;"></p>
		<p id="fbNoti" class="noti"></p>
		<p id="googleNoti" class="noti"></p>
		<div class="pushBlock">
			<input type="checkbox" name="pushSite" <?php if ($pushSite){echo 'checked="checked"';} ?>/><label for="pushSite" class="pushSelect"> <?php echo $group->name; ?>'s website </label>
			<input type="checkbox" name="pushFb" <?php if ($pushFb){echo 'checked="checked"';} ?>/><label for="pushFb" class="pushSelect"> Facebook </label>
			<input type="checkbox" name="pushGoogle" <?php if ($pushGoogle){echo 'checked="checked"';} ?>/><label for="pushGoogle" class="pushSelect"> Google Calendar</label>
		</div>
		<ul class="pushBlock">
			<li>- Your Google Calendar email should match your group's email</li>
			<li>- Don't forget to save your push settings!</li>
		</ul>
		<div id="pushSite" class="pushBlock" style="display:none;">
			<p>Simply copy and paste the code to your site's event page. <br /> If you need any help, feel free to contact us and we can help you set it up within minutes!</p>
			<textarea name="msg" class="inputText descriptionText inactiveText"><iframe src="<?php echo MAIN_URL; ?>iframe.group.php?g=<?php echo $group->id;?>&if=1" scrolling="no" frameborder="0" style="width:715px; height:2100px;"></iframe></textarea>
			<div id="widgetContainer">
				<div class="tableHd">Upcoming</div>
				<table>  
					<tbody>
						<tr>
							<td colspan="2">
							<ul class="evtContainer">
								<li class="feed">
									<img class="feedImg" src="css/images/default.png" />
									<span class="feedName">Fall Concert</span>
									<table class="feedInfo">
										<tr>
											<th colspan="2" class="feedDate"><?php echo date('F j',time()); ?></th>
										</tr>
										<tr>
											<th>Network:</th>
											<td>University of Southern California</td>
											</tr>
							
										<tr>
											<th>Where:</th>
											<td class="feedLocation"><?php echo $group->venue; ?></td>
										</tr>
										<tr>
											<th>Hosted By:</th>
											<td class="groupName" value="50"><?php echo $group->name; ?></td>
							
										</tr>
										<tr> 
											<th>Description:</th>
											<td> <div class="feedDescription">This will be our first event of the year. Come and have a great time!</div></td>
										</tr>
									<!--feedInfo -->
									</table>
									<div class="feedSide">
									<div class="feedOptions">
											<div class="sideFill"></div>
											<div class="sideFill"></div>
											<div class="sideFill"></div>
										</div>
										<div class="feedSideDivider"> </div>
										<table class="feedCount"> 
											<tr> 
												<td class="feedLike">829</td>
												<th class="feedLikeText">Likes</th>
							
											</tr>
											<tr class="feedUserAttend"> 
												<td class="feedAttend">688</td>
												<th>Attending</th>
							
											</tr>
											<tr class="feedFriendAttend">
												<td>25</td>
												<th>Friends Attending</th>
						
											</tr>
										</table>
									<!-- .feedSide -->
									</div>
								<!-- .feed -->   
								</li>
							</ul>
							<ul class="mediumList">
								<li><img src="css/images/default.png" /><div class="listName">Fall Concert</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Weekly Meeting</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Spring Concert Meeting</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Networking Event</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Community Outreach</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Spring Concert</div></li>
								<li><img src="css/images/default.png" /><div class="listName">Weekly Meeting</div></li>
							</ul>
							</td>
						</tr>
					</tbody>
				</table>
			<!-- #widgetContainer -->
			</div>
			<div class="tableDivider"></div>
			<div id="styleSelector">
				<span><b>Style:</b></span>
				<input type="radio" name="calStyle"<?php if ($group->style == 'light') { echo 'checked="checked"';} ?> value="light" /><label for="calStyle"> Light </label>
				<input type="radio" name="calStyle"<?php if ($group->style == 'dark') { echo 'checked="checked"';} ?> value="dark" /><label for="calStyle">  Dark </label>
				<input type="radio" name="calStyle"<?php if ($group->style == 'custom') { echo 'checked="checked"';} ?> value="custom" /><label for="calStyle"> Custom </label>
			</div>
			<div id="colorContainer">
				<div class="colorDiv">
					<div id="bgColor" class="colorBox" style="background:#<?php echo $group->background; ?>"></div>
					<div class="colorText">Background</div>
					<input type="hidden" name="bgColor" value="<?php echo $group->background; ?>" />
				</div>
				<div class="colorDiv">
					<div id="hdBg" class="colorBox" style="background:#<?php echo $group->header_bg; ?>"></div>
					<div class="colorText">Header Background</div>
					<input type="hidden" name="hdBg" value="<?php echo $group->header_bg; ?>" />
				</div>
				<div class="colorDiv">
					<div id="hdText" class="colorBox" style="background:#<?php echo $group->header_text; ?>"></div>
					<div class="colorText">Header Text</div>
					<input type="hidden" name="hdText" value="<?php echo $group->header_text; ?>" />
				</div>
				<div class="colorDiv">
					<div id="borderColor" class="colorBox" style="background:#<?php echo $group->border; ?>"></div>
					<div class="colorText">Borders</div>
					<input type="hidden" name="borderColor" value="<?php echo $group->border; ?>" />
				</div>
				<div class="colorDiv">
					<div id="labelColor" class="colorBox" style="background:#<?php echo $group->label; ?>"></div>
					<div class="colorText">Labels</div>
					<input type="hidden" name="labelColor" value="<?php echo $group->label; ?>" />
				</div>
				<div class="colorDiv">
					<div id="textColor" class="colorBox" style="background:#<?php echo $group->text; ?>"></div>
					<div class="colorText">Text</div>
					<input type="hidden" name="textColor" value="<?php echo $group->text; ?>" />
				</div>
			</div>
		<!-- .pushBlock -->
		</div>
		<div class="pushBlock">
			<input type="hidden" name="gcalid" value="<?php echo $group->gcal_id; ?>" />
			<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
			<input class="inputSubmit" type="submit" name="updatePush" value="Update" />
		</div>
	</form>

	<div class="title">Pull</div>
	<div class="titleDivider"></div>
	<form id="pullForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<p id="pullNoti" class="noti"></p>
		<p>Enter your google calendar email and we will pull all your events. Events without a title, location, or description will not be pulled.</p>
		<div id="pullBlock">
			<input class="inputText peText" type="text" name="email" autocomplete="off" value="<?php echo $group->email; ?>" />
			<input type="hidden" name="gcalid" value="<?php echo $group->gcal_id; ?>" />
			<input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
			<input class="inputSubmit"  type="submit" name="updatePush" value="Pull" />
		</div>
	</form>
	
	<div class="title">Photos</div>
 	<div class="titleDivider"></div>
 	<p id="updateNoti" class="noti"></p>
    <table>
		<tr>
			<td>
				<i id="profileImg" style="background: url(images/groups/m<?php echo $group->id.'.jpg?'.$time; ?>) no-repeat 50% 30%;" /> </i>
				<div class="photoLabel"> Main</div>
			</td>
			<?php
			for ($i = 1; $i < 4; $i++){
				echo'
				<td>
					<img id="s'.$i.'" class="sideImg" src="images/groups/s'.$i.$group->id.'.jpg?.'.$time.'" /> 
					<div class="photoLabel"> Side '.$i.'</div>
				</td>';
			}
			?>
        <tr>
    </table>
    <form id="groupPhotoForm" action="ajax/ajax.groupManage.php" enctype="multipart/form-data" target="photoFrame" method="post">
    <table style="margin-top:30px;">
        <tr>
			<th>Main:</th>
			<td>
				<div class="fileContainer">
					<input class="inputText fileText" type="text" />
					<input class="inputFile" type="file" name="m" />
					<input class="inputSubmit fileSubmit" type="button" value="Browse" />
					<a class="cancelBtn" href="#">Cancel</a>						
				</div>
			</td>
		</tr>
        <tr>
			<th>Side 1:</th>
			<td>
				<div class="fileContainer">
					<input class="inputText fileText" type="text" />
					<input class="inputFile" type="file" name="s1"/>
					<input class="inputSubmit fileSubmit" type="button" value="Browse" />
					<a class="cancelBtn" href="#">Cancel</a>						
				</div>
			</td>
		</tr>
        <tr>
			<th>Side 2:</th>
			<td>
				<div class="fileContainer">
					<input class="inputText fileText" type="text" />
					<input class="inputFile" type="file" name="s2"/>
					<input class="inputSubmit fileSubmit" type="button" value="Browse" />
					<a class="cancelBtn" href="#">Cancel</a>						
				</div>			
			</td>
		</tr>
        <tr>
			<th>Side 3:</th>
			<td>
				<div class="fileContainer">
					<input class="inputText fileText" type="text" />
					<input class="inputFile" type="file" name="s3"/>
					<input class="inputSubmit fileSubmit" type="button" value="Browse" />
					<a class="cancelBtn" href="#">Cancel</a>						
				</div>
			</td>
		</tr>
        <tr>
        	<th></th>
        	<td>
                <input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
                <input class="inputSubmit" type="submit" name="updatePhoto" value="Update"/>
            </td>
		</tr>
    </table>
    </form>     
    <iframe id="photoFrame" name="photoFrame" src="ajax/ajax.groupManage.php"></iframe>
<!-- #aboutUs -->
</div>