<?php
require_once('connect.php'); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'user.php');
require_once(CLASS_DIR.'network.php');
redirect_logged_out_users();
// Setup the user
$current_user->setConnections('network');
?>
<div id="network">
    <div class="settingsTitle">Current</div>
        <div class="titleDivider"></div>
        <ul class="mediumList">
        	<?php
			// Loop through user networks and display them
			foreach ($current_user->networks as $network => $networkId){
				$xPos = ($networkId - 1) * - 80;
				echo '<li value="'.$networkId.'"><i style="background: url(css/images/network_th/nc.png) no-repeat '. $xPos .'px 0px;"></i><span class="listName">'.$network.'</span></li>';
            }
			?>
        </ul>    
    <div class="settingsTitle" style="padding-top:15px">Update</div>
    <div class="titleDivider"></div>
        <p id="networkNoti" class="noti"></p>
		<form id="changeNet" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table class="settings">
			<tbody>
				 <tr>
					<th>City:</th>
						<td>
							<select name="network[]">
								<option value="">None</option>
								<?php
								// Loop through all city networks and display them
								foreach (Network::get_all_networks('city') as $network => $networkId){
									echo '<option value="'.$networkId.'">'.$network.'</option>';
								}
								?>
							</select>
					   </td>
					</tr>
				 <tr>
					<th>University:</th>
						<td>
							<select name="network[]">
								<option value="">None</option>
								<?php
								// Loop through all university networks and display them
								foreach (Network::get_all_networks('university') as $network => $networkId){
									echo '<option value="'.$networkId.'">'.$network.'</option>';
								}
								?>
							</select>
					   </td>
					</tr>
					<tr>
						<th></th><td><input type="submit" class="inputSubmit" name="changeMajor" value="Update" /></td>
					</tr>
				</tbody>
			</table>
        </form>
<!-- #networkSettings -->   
</div>