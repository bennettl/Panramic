<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

// Depending if the session isset, display the appropriate page
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
	require_once('fb.home.php');
} else{
	require_once('fb.signup.php');
}
?>