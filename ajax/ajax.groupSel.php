<?php
require_once('connect.php');
redirect_not_staff();

// If groupSel isset, set change the group the user is administrator of
if (isset($_POST['groupSel'])){
	$groupId = intval($_POST['groupId']);
	$update  = "UPDATE group_members SET group_id = '$groupId' WHERE user_id = '$current_user->id' ";
	mysqli_query($dbc,$update);
}
?>