<?php
global $dbc, $current_user, $profile_user, $profileId;
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

if (!isset($_SESSION['user_id'])){
	$userId = 0;
}
if (empty($profileId)){
	$profileId  = (isset($_POST['profileId'])) ? intval($_POST['profileId']) : false;
}

if ($profileId): ?>
	<div id="evtWall">
	<?php
	$profile_user =  (empty($profile_user)) ? new User($profileId) : $profile_user;
	
	$events    = array();
	$args      = array('future' => true ,'limit' => 7);
	$events    = $profile_user->getEvents($args);
	// If user is viewing his own profile or is friend of profile and has events, show them
	if (($current_user->id == $profile_user->id || $current_user->is_friend($profile_user->id)) && count($events) > 0):
	?>
		<div class="tableHd">Attending</div>
		<table id="currentEvtTable">  
			<tbody>
				<tr>
					<td colspan="2">
						<ul class="evtContainer"></ul>
						<ul class="mediumList">
							<?php get_feedlist( array('events' => $events, 'type' => 'mediumList')); ?>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>  
		<div class="tableDivider"></div>
	<?php endif;
	// Select the seven most recent past events the user is attending
	$events = array();
	$args   = array('past' => true ,'limit' => 7);
	$events = $profile_user->getEvents($args);
	?>
		<div class="tableHd">Attended</div>
		<table>  
			<tbody>
				<tr>
					<td colspan="2">
						<?php
						// If there are no events, notify the user
						if (count($events) > 0): ?>
							<ul class="evtContainer"></ul>
							<ul class="mediumList">
								<?php get_feedlist( array('events' => $events, 'type' => 'mediumList')); ?>
							</ul>
						<?php else: ?>
							<div class="noti pageNoti">There are currently no events</div>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>  
	<!-- evtWall -->
	</div>
<?php endif; ?>