<?php
global $dbc,$group;
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'user.php');

$admins = $group->getMembers(array('member_status' => array('admin')));// $group is already instantiated in gprofile

if (count($admins) > 0): ?>
	<div id="sidenavTop">
		<span class="miniListTitle" href="#">Admins</span>
		<ul class="smallList">
		<?php
		foreach ($admins as $admin){
			$user     = $admin['user'];
			$fullName = $user->first_name.' '.$user->last_name;
			$href     = htmlentities($user->href_name);
			echo '<li><a href="'.$href.'"><img alt="'.$fullName.'" src="'.$user->thumbnail.'" /></a></li>';
		}
		?>
		</ul>
	</div>
	<div class="sidenavDivider"> </div>
<?php endif; ?>
<?php
// Count and display the number of members
$count = $group->memberCount();
if ($count > 0): ?>
	<div id="sidenavBottom">
	<div class="miniListTitle"><span>Members (<?php echo $count; ?>)</span></div>
 	<ul class="smallList">
 	<?php
	$members = $group->getMembers(array('member_status' => array('member'),'limit' => 12));
	foreach ($members as $member){
		$user     = $member['user'];
		$fullName = $user->first_name.' '.$user->last_name;
		$href     = htmlentities($user->href_name);
		echo '<li><a href="'.$href.'"><img alt="'.$fullName.'" src="'.$user->thumbnail.'" /></a></li>';
	}
	?>
	</ul>
<?php endif; ?>
<!-- #sidenavBottom -->         
</div>
<div id="feedbackBtn">site feedback</div>