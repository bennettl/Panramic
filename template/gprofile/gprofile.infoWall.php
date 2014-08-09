<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
$groupId       = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
$group         = new Group($groupId);
$group->setConnections();
$group->street = ($group->street == 'N/A') ? '' : $group->street;
// The regular expression filter for link.  If there are urls in the description, add the <a> and <br /. tags, else just add <br /> tags
$reg_exUrl          = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
$group->description = ((preg_match($reg_exUrl, $group->description, $url))) ? nl2br(preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a> ', $group->description)) : nl2br($group->description);
?>
<div id="infoWall">    
    <div class="tableHd">Info</div>
    <table class="table">
        <tbody>
            <tr>
                <th class="infoLabel">About Us</th>
                <td class="infoData"><?php echo $group->description; ?></td>
            </tr>
            <tr><td colspan="2"><div class="tableDivider"></div></td></tr>
        </tbody>

        <tbody>
            <tr>
                <th class="infoLabel">E-mail</th>
                <td class="infoData"><?php echo $group->email; ?></td>
            </tr>
            <tr><td colspan="2"><div class="tableDivider"></div></td></tr>
        </tbody>
        
        <tbody>
            <tr>
                <th class="infoLabel">Location</th>
                <td class="infoData"><?php echo $group->venue.'<br />'.$group->street.' '.$group->locality.', '.$group->region; ?></td>
            </tr>
            <tr><td colspan="2"><div class="tableDivider"></div></td></tr>
        </tbody>
    </table>
	<?php if (count($group->networks) > 0): // If this group has networks, show them ?>
		<div class="tableHd">Networks</div>
		<table class="table">  
			<tbody>
				<tr>
					<td colspan="2" class="infoData">
						<div id="infoNetwork">
							<ul class="mediumList">
								<?php
								// Loop through networks and display them
								foreach ($group->networks as $network => $networkId){
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
	<?php endif; ?>
	<?php if (count($group->fields) > 0): // If this user has fields, show them ?>
		<div class="tableHd">Fields</div>
		<table class="table">  
			<tbody>
				<tr>
					<td colspan="2" class="infoData">
						<div id="infoField">
							<ul class="mediumList">
							<?php
							// Loop through fields and display them
					 		foreach ($group->fields as $field => $fieldId){
								$xPos = ($fieldId - 1) * -80;
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