<?php
require_once('connect.php');

if (!isset($_SESSION['user_id'])){
	$current_user = new User();
}

if (isset($_GET['h'])){
	// Find all groups or users that are active
	$profileHref  = mysqli_real_escape_string($dbc, trim($_GET['h']));
	$select       = "SELECT u.user_id, u.first_name, h.group_id 
					 FROM hrefs AS h
					 LEFT JOIN users AS u
					 ON (h.user_id = u.user_id)
					 LEFT JOIN groups AS g 
					 ON (h.group_id = g.group_id)
					 WHERE href_name = '$profileHref' AND (u.user_status != 'banned' OR g.group_status != 'banned')";
	$result       = mysqli_query($dbc,$select);
	$href   	  = mysqli_fetch_assoc($result);
	$profileId 	  = intval($href['user_id']);
	$groupId 	  = intval($href['group_id']);
	
	
	// Depending on the href, display the appropriate page or redirect user to notfound
	if (!empty($profileId)){
		$firstname = htmlentities($href['first_name']);
		require_once("profile.php");
	} else if (!empty($groupId)){
		require_once("gprofile.php");
	} else {
		$notfound = 'notfound';
		header('Location:'. $notfound);
		exit;
	}
} else{
	exit;
}
?>