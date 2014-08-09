<?php
require_once('connect.php');
redirect_logged_out_users();
?>
<div id="groupSel" class="pageLayout">
<div class="pageHd">Select Group</div>
	<div class="noti pageNoti"></div>
	<form id="groupSelForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table id="groupSelTable">
			<th class="label">Group:</th>
			<td>
				<input class="inputText msgText" type="text" name="group" autocomplete="off"/>
				<input id="group" name="groupId" type="hidden" />
			</td>
			<td>
				<input class="inputSubmit" type="submit" name="groupSelect" value="Select" />
			</td>
		</table>
	</form>
	<ul id="searchList">
	<?php
	// Select all active groups
	$select = "SELECT group_id, group_name
			   FROM groups
			   WHERE group_status = 'active'";
	$result = mysqli_query($dbc,$select);
	while ($row = mysqli_fetch_assoc($result)){
		$groupId	= $row['group_id'];
		$groupName	= $row['group_name'];
		echo '<li value="'.$groupId.'">'.$groupName.'</li>';
	}
	?>
	</ul>
</div>