<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'event.php');

global $dbc, $groupId, $group, $miniEvent;

if (!isset($_SESSION['user_id'])){
	$userId = 0;
}
if (empty($groupId)){
	$groupId = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
}
$group   = empty($group) ? new Group($groupId) : $group;
?>
<div id="evtWall">
<?php 
$events    = array();
$args      = array('future' => true ,'limit' => 7);
$events    = $group->getEvents($args);
if (count($events) > 0): ?>
	<div class="tableHd">Upcoming</div>
	<table>  
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
<?php endif; ?>
<?php
// Show past table if it isnt an iframe or it is an iframe and there are no current events
if (!isset($_GET['if']) || (isset($_GET['if']) && count($events) == 0)):
	$events = array();
	$args   = array('past' => true ,'limit' => 7);
	$events = $group->getEvents($args);
	?>
	<div class="tableHd">Past</div>
	<table>  
		<tbody>
			<tr>
				<td colspan="2">
				<?php
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
<?php endif;
// If its an iframe, display logo
if (isset($_GET['if'])){
	echo '<a href="'.MAIN_URL.'" target="_blank"><img id="miniLogo" src="css/images/icons/minilogo.png" /></a>';
}
?>
<!-- evtWall -->
</div>