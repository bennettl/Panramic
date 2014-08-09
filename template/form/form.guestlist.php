<?php 
//Displays the guestlist of networks and field 
global $dbc;
require_once(CLASS_DIR.'field.php');
?>
<ul class="guestTab"> 
	<li><a id="tab_guestNetwork" href="#" class="current">Networks</a></li>
    <li><div class="guestDivider"></div></ll>
    <li><a id="tab_guestField" href="#">Fields</a></li>
    <li> 
		<div class="guestSearchContainer">
			<input class="inputText searchText inactiveText guestText" type="text" value="Find..."/>
			<i class="guestSubmit"></i>
		</div> 
    </li>
</ul>
<div class="guestListContainer">
    <div class="guestNetwork">
    <?php
	for ($i = 1; $i < 3; $i++):
		$networkType = ($i == 1) ? "City" : "University";
		// Loop through the network_categories and networks to make guest_network list
		?>
		<div class="guestHd"><?php echo $networkType; ?> </div>
			 <ul class="listContainer">
			 <?php	 
			  $select = "SELECT nc.network_category_id, nc.network_category_name, gc.count
			  			 FROM network_categories AS nc 
						 INNER JOIN guest_count AS gc
						 ON (nc.network_category_id = gc.network_category_id AND gc.field_category_id ='0')
						 WHERE nc.network_type_id = '$i'";
			  $result2 = mysqli_query($dbc,$select);
		  
			  while ($array2 = mysqli_fetch_array($result2)):
				  $networkId   = intval($array2['network_category_id']);
				  $count	   = intval($array2['count']);
				  $network	   = $array2['network_category_name'];
				  $xPos 	   = ($networkId - 1) * -33;
				  ?> 
				  <li class="list">
					  <input type="checkbox" name="network" value="<?php echo $networkId; ?>" />
					  <a href="#"><i style="background: url(css/images/mini/mini.png) no-repeat <?php echo $xPos; ?>px 0px;"></i>
					  <div class="checkbox"><?php echo $network; ?>

					<?php if ($countGuests): // If countGuests is true, display count next to network ?>
						<span class="guestCount">(<?php echo $count; ?>)</span>
					<?php endif; ?>

					  </div>
					  </a>

					<?php
					  if ($countGuests){
						  // Loop through all fields within the network, in order to determine the count of people in the field within the network
						  $select   = "SELECT field_category_id, count FROM guest_count WHERE network_category_id = '$networkId' AND field_category_id !='0'";
						  $result3  = mysqli_query($dbc,$select);
			  
						  while ($array3 = mysqli_fetch_array($result3)){
							  $fieldId = $array3['field_category_id'];
							  $count   = $array3['count'];
							  echo '<div class="hidden field'.$fieldId.' '.$count.'"></div>';
						  }
					  }
				   ?>
				  </li>
			  <?php endwhile; ?>
		</ul>
	<?php endfor; ?>
    <!-- #guestNetwork -->
    </div>
    <div class="guestField">
		<div class="guestHd">Fields</div>
		<ul class="listContainer">
		<?php
		// Loop through all fields
		foreach (Field::get_all_fields() as $field => $fieldId):
			if ($fieldId != 15):
				$xPos 	    = ($fieldId -1) * -33;
				$yPos	    = -31;
			?> 
				<li class="list">
					<input type="checkbox" name="field" value="<?php echo $fieldId; ?>" />
					<a href="#"><i style="background: url(css/images/mini/mini.png) no-repeat <?php echo $xPos.'px '.$yPos.'px'; ?>"></i>
					<div class="checkbox"><?php echo $field; ?>

					<?php if ($countGuests): // If count is true, display count next to field ?>
					 	<span class="guestCount">(0)</span>
					<?php endif; ?>
				
				    </div>
					</a>
				</li>						
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
    <!-- #guestField -->
	</div>
 <!-- .guestListContainer -->
</div>
