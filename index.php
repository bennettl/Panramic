<?php

// For showcase, just display about page

require_once('about.php');
/*
require_once('connect.php');

$select = "SELECT 1 FROM users WHERE user_id = '$current_user->id' AND user_status = 'staff'";
$result = mysqli_query($dbc,$select);
if (mysqli_num_rows($result) != 1){
	require_once('newconstruction.php');
	exit;
}
// Depending if the session isset, display the appropriate page
if (empty($_SESSION['user_id'])){
	require_once('signup.php');
} else{
	require_once('home.php');
}*/
?>