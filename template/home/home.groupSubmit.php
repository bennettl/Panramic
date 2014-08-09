<?php 
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'field.php');
?>
<div id="groupSubmit" class="pageLayout">
    <div class="pageHd">Submit Group</div>
    <p id="groupSubmitNoti" class="noti" style="margin-left:10px;"></p>
    <p id="groupSubmitNoti2" style="margin-left: 10px;"> Groups can immediately and effectively reach out to people who are interested in what they have to offer.</p>
    <form id="groupSubmitForm" enctype="multipart/form-data" action="ajax/ajax.groupSubmit.php" target="groupSubmitFrame" method="post">
    <table class="tableForm">
        <tr>
            <th class="label"><label for="name">Group Name:</label></th>
            <td><input class="inputText" type="text" name="name" autocomplete="off" /></td>
        </tr>
		<tr>
            <th class="label"><label for="email">Email:</label></th>
            <td><input class="inputText" type="text" name="email" autocomplete="off" /></td>
        </tr>
 		<tr>
			<th>Main Photo:</th>
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
         	<th></th>
            <td>
            <p style="width:500px;">Please select one network and field that best categorizes your group</p>
			<?php get_form_guestlist(); ?>
        	</td>
        </tr>
        <tr>
            <th class="label"><label for="location">Venue:</label></th>
            <td><input class="inputText" type="text" name="venue" autocomplete="off" /></td>
        </tr>
		<tr>
            <th class="label"><label for="location">State:</label></th>
			<td>
				<select name="region"> 
					<option value="AL">Alabama</option> 
					<option value="AK">Alaska</option> 
					<option value="AZ">Arizona</option> 
					<option value="AR">Arkansas</option> 
					<option value="CA" selected="selected">California</option> 
					<option value="CO">Colorado</option> 
					<option value="CT">Connecticut</option> 
					<option value="DE">Delaware</option> 
					<option value="DC">District Of Columbia</option> 
					<option value="FL">Florida</option> 
					<option value="GA">Georgia</option> 
					<option value="HI">Hawaii</option> 
					<option value="ID">Idaho</option> 
					<option value="IL">Illinois</option> 
					<option value="IN">Indiana</option> 
					<option value="IA">Iowa</option> 
					<option value="KS">Kansas</option> 
					<option value="KY">Kentucky</option> 
					<option value="LA">Louisiana</option> 
					<option value="ME">Maine</option> 
					<option value="MD">Maryland</option> 
					<option value="MA">Massachusetts</option> 
					<option value="MI">Michigan</option> 
					<option value="MN">Minnesota</option> 
					<option value="MS">Mississippi</option> 
					<option value="MO">Missouri</option> 
					<option value="MT">Montana</option> 
					<option value="NE">Nebraska</option> 
					<option value="NV">Nevada</option> 
					<option value="NH">New Hampshire</option> 
					<option value="NJ">New Jersey</option> 
					<option value="NM">New Mexico</option> 
					<option value="NY">New York</option> 
					<option value="NC">North Carolina</option> 
					<option value="ND">North Dakota</option> 
					<option value="OH">Ohio</option> 
					<option value="OK">Oklahoma</option> 
					<option value="OR">Oregon</option> 
					<option value="PA">Pennsylvania</option> 
					<option value="RI">Rhode Island</option> 
					<option value="SC">South Carolina</option> 
					<option value="SD">South Dakota</option> 
					<option value="TN">Tennessee</option> 
					<option value="TX">Texas</option> 
					<option value="UT">Utah</option> 
					<option value="VT">Vermont</option> 
					<option value="VA">Virginia</option> 
					<option value="WA">Washington</option> 
					<option value="WV">West Virginia</option> 
					<option value="WI">Wisconsin</option> 
					<option value="WY">Wyoming</option>
				</select>
			</td>
		</tr>
		<tr>
            <th class="label"><label for="street">Street:</label></th>
            <td>
				<input class="inputText" type="text" name="street" autocomplete="off" />
				<label for="locality" style="margin:0 5px 0 8px;"><b>City</b></label>
				<input class="inputText" type="text" name="locality" autocomplete="off" />
				<label for="postal" style="margin:0 5px 0 8px;"><b>Postal</b></label>
				<input class="inputText" style="width:50px;" type="text" name="postal" autocomplete="off" />
			</td>
        </tr>
        <tr>
            <th class="label" style="vertical-align:top; padding-top: 8px;"><label for="description">Description:</label></th>
            <td><textarea name="description" class="inputText descriptionText"></textarea></td>
        </tr>
        <!-- <tr>
            <th class="label"><label for="gender">Gender <br /> Orientation:</label></th>
            <td>
            	<input type="radio" checked="checked" name="gender" value="N" />None
            	<input type="radio" name="gender" value="M" />Male
            	<input type="radio" name="gender" value="F" />Female
            </td>
        </tr> -->
        <tr>
        	<td style="padding-top:15px;" colspan="2"><input class="inputSubmit" style="padding-top: 3px; padding-bottom: 3px;" type="submit" name="groupSubmit" value="Submit" /></td>
        </tr>
    </table>
    </form>
	<iframe id="groupSubmitFrame" name="groupSubmitFrame" src="ajax/ajax.groupSubmit.php" ></iframe>
<!-- #groupSubmit -->    
</div>