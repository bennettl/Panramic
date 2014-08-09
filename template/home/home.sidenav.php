<?php
require_once(CLASS_DIR.'user.php');
global $dbc, $current_user;
$current_user->setConnections();
?>
<div id="evtFeedText">Events Feed</div>
<ul id="sidenavTop">
	<li><a id="tab_userFeed" href="#" class="current">Me</a></li>
	<li><a id="tab_friendFeed" href="#">Friends</a></li>
	<li><a id="tab_everyFeed" href="#">Everyone Else</a></li>
	<li class="sidenavDivider"></li>
	<?php
	/*
	echo'
	<li><a id="tab_eventinv" href="#" >Event Invites';
		// Display sideNoti for invites base on events that user didn't attend/hide or that isn't part of user_events for the user
		$select  = "SELECT 1
					FROM user_invites AS ui
					INNER JOIN events AS e
					ON (ui.event_id = e.event_id)
					WHERE ui.user_id = '$userId' AND (e.start_date >= CURDATE() OR (e.ongoing = 'yes' AND e.end_date >= CURDATE()))
					GROUP BY e.event_id";
		$result  = mysqli_query($dbc,$select);
		$count   = mysqli_num_rows($result);
		if ($count > 0){
			echo' <span class="sideNoti">'.$count.'</span>';
		}
	echo'
	</a></li>
	
   <li class="sidenavDivider"></li>';
   */
   
   $info = array();
   // If user is adminstrator of a group
   if ($current_user->is_admin($info)):
   		// Admin of one group or many groups
   		if ($info['group_num'] == 1): ?>
			<li><a id="tab_postEvent" href="#">Post Event</a></li>
			<li><a id="tab_memberReq" href="#">Member Requests
			 	<?php
				// Displays sideNoti for memberReq
				$groupId  = $info['group_id'];
				$select    = "SELECT COUNT(group_member_id) FROM group_members WHERE group_id = '$groupId' AND member_status = 'pending'";
				$result    = mysqli_query($dbc,$select);
				$requests  = mysqli_fetch_assoc($result);
				$count     = $requests['COUNT(group_member_id)'];
				if ($count > 0){ echo '<span class="sideNoti">'.$count.'</span>'; }
				?>
			</a></li>
			<li><a id="tab_groupManage" href="#">Manage Group</a></li>
		<?php else: ?>
			<li><a id="tab_groupBuffer1" href="#postEvent">Post Event</a></li>
			<li><a id="tab_groupBuffer2" href="#memberReq">Member Requests</a></li>
			<li><a id="tab_groupBuffer3" href="#groupManage">Manage Group</a></li>
   		<?php endif; ?>
   <?php endif; ?>   
   <li><a id="tab_groupSubmit" href="#">Submit Group</a></li>
   <li class="sidenavDivider"></li>
   <?php
   // If user is staff, show this portion of sidenav
   if ($current_user->status == 'staff'): ?>
	  	<li><a id="tab_groupSel" href="#">Select Group</a></li>
		<li><a id="tab_groupReq" href="#">Confirm Group
		  	<?php
			// Display sideNoti
			$select  = "SELECT 1 FROM groups WHERE group_status = 'pending';";
			$result  = mysqli_query($dbc,$select);
			$count   = mysqli_num_rows($result);
			if ($count > 0){ echo' <span class="sideNoti">'.$count.'</span>';}
			?>
		</a></li>
		<li><a id="tab_eventReq" href="#">Confirm Event
			<?php
			// Display sideNoti
			$select  = "SELECT COUNT(1) FROM events WHERE event_status = 'pending'";
			$result  = mysqli_query($dbc,$select);
			$row     = mysqli_fetch_assoc($result);
			$count   = $row['COUNT(1)'];
			if ($count > 0){ echo' <span class="sideNoti">'.$count.'</span>';}
			?>
		</a></li>
		<li><a id="tab_masse" href="#">Mass email</a></li>
		<li><a id="tab_report" href="#">Reports
			<?php
			// Display sideNoti 
			$select  = "SELECT 1 FROM reports";
			$result  = mysqli_query($dbc,$select);
			$count   = mysqli_num_rows($result);
			if ($count > 0){ echo ' <span class="sideNoti">'.$count.'</span>';}
			?>
		</a></li>
			  <li class="sidenavDivider"></li>
   <?php endif; ?>
<!-- #sidenavTop -->
</ul>
<div id="sidenavBottom">
	<div class="miniListTitle">Networks</div>
<?php if (count($current_user->networks) == 0): // If this user has no networks, tell them ?>
	<p style="margin-left: 19px;">Select a network to view events</p>
<?php else: ?>
	<ul class="miniList" id="networkList">
		<?php
		// Loop through user networks and display them
		 foreach ($current_user->networks as $network => $networkId){
			$xPos = ($networkId - 1) * -33;
			echo '<li value="'.$networkId.'"><i class="'.$network.'" style="background: url(css/images/mini/mini.png) no-repeat '.$xPos.'px 0px;"></i></li>';
		}
		?>
	</ul>
<?php endif; ?>
	<div class="miniListTitle">Fields</div>
<?php if (count($current_user->fields) == 0): // If this user has no fields, tell them ?>
	<p style="margin-left: 19px;">Select a field to view events</p>
<?php else: ?>
	<ul class="miniList" id="fieldList">
		<?php
		// Loop through user fields and display them
		foreach ($current_user->fields as $field => $fieldId){
			$xPos = ($fieldId - 1) * -33;
			$yPos = -31;
			echo '<li value="'.$fieldId.'"><i class="'.$field.'" style="background: url(css/images/mini/mini.png) no-repeat '.$xPos.'px '.$yPos.'px;"></i></li>';
		}
		?>
	</ul>
<?php endif; ?>
	<div id="filterContainer" class="filterContainer"> 
		<span id="filterOn" class="filterStatus">On</span>
		<span style="color: #8C8C8C;"> | </span>
		<span id="filterOff" class="filterStatus current"> Off</span>
		<span class="miniListTitle">Filter</span>
	</div>
	<div id="freeFoodContainer" class="filterContainer"> 
		<span id="foodOn" class="filterStatus">On</span>
		<span style="color: #8C8C8C"> | </span>
		<span id="foodOff" class="filterStatus current"> Off</span>
		<span class="miniListTitle">Free Food Filter</span>
	</div>
<!-- #sidenavBottom -->
</div>
<div id="feedbackBtn">site feedback</div>