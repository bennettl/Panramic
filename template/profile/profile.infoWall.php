<?php
require_once(CLASS_DIR.'user.php');
$profileId   = (isset($_POST['profileId'])) ? intval($_POST['profileId']) : false;

if ($profileId): ?>
	<div id="infoWall">
		<?php
		// Get user and set their personal info, networks, and fields
		$profile_user = new User($profileId);
		$profile_user->setInfo();
		$profile_user->setConnections();
		?>
		<div class="tableHd genInfoHd">Info</div>
		<table class="table genInfoTable">
		<?php if ($profile_user->hometown != "N/A"): ?>
			<tbody>
				<tr>
					<th class="infoLabel">Hometown</th>
					<td class="infoData"><?php echo $profile_user->hometown; ?></td>
				</tr>
				<tr><td colspan="2"><div class="tableDivider"></div></td></tr>
			</tbody>
		<?php endif; ?>
		<?php if ($profile_user->sex != 'N/A'): ?>
			<tbody>
				<tr>
					<th class="infoLabel">Sex</th>
					<td class="infoData"><?php echo $profile_user->sex; ?></td>
				</tr>
				<tr><td colspan="2"><div class="tableDivider"></div></td></tr>
			</tbody>
		<?php endif; ?>
		<?php if ($profile_user->birthday != '0000-00-00'): ?>
			<tbody>
				<tr>
					<th class="infoLabel">Birthday</th>
					<td class="infoData"><?php echo date('F j, Y',strtotime($profile_user->birthday)); ?></td>
				</tr>
				<tr><td colspan="2"><div class="tableDivider"></div></td></tr>
			</tbody>
		<?php endif; ?>
		</table>
	<?php if (count($profile_user->networks) > 0): // If this user has networks, show them ?>
		<div class="tableHd">Networks</div>
		<table class="table">  
			<tbody>
				<tr>
					<td colspan="2" class="infoData">
						<div id="infoNetwork">
							<ul class="mediumList">
							<?php
							// Loop through networks and display them
							foreach ($profile_user->networks as $network => $networkId){
								$xPos    	 = ($networkId - 1) * -80;
								echo'
								<li><i style="background: url(css/images/network_th/nc.png) no-repeat '. $xPos .'px 0px;"></i><div class="listName">'.$network.'</div></li>';
							 }
							?>
							</ul>
						</div>
					</td>
				</tr>
				<tr><td colspan="2"><div class="tableDivider"></div></td></tr>
			</tbody>
		</table>
	<?php endif;?> 
	<?php if (count($profile_user->fields) > 0): // If this user has fields, show them ?>
		<div class="tableHd">Fields</div>
		<table class="table">  
			<tbody>
				<tr>
					<td colspan="2" class="infoData">
						<div id="infoField">
							<ul class="mediumList">
							<?php
							// Loop through fields and display them
					 		foreach ($profile_user->fields as $field => $fieldId){
								$xPos      = ($fieldId - 1) * -80;
								echo '<li><i style="background: url(css/images/field_th/fields.png) no-repeat '. $xPos .'px 0px;"></i><div class="listName">'.$field.'</div></li>';
							 }
							?>
							</ul>
						</div>
					</td>
				</tr>
				<tr><td colspan="2"><div class="tableDivider"></div></td></tr>
			</tbody>
		</table>
		<?php endif; ?>
	<!-- #infoWall -->
	</div>
<?php endif; ?>