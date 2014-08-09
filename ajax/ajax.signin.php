<?php
require_once('connect.php');
require_once(CLASS_DIR.'user.php');

// When signIn isset, we check to see is there a match with email and password and pass back the appropriate success/message
if (isset($_POST['signIn'])){
	$email       = $_POST['siEmail'];
	$password    = $_POST['siPassword'];
	$args        = array('email' => $email, 'password' => $password);
	
	if ($user_id = User::userExist($args)){
		$user = new User($user_id);
		signIn();
	} else{
		$data['success'] = "no";
		$data['message'] = "Incorrect email or password";
	}
	echo json_encode($data);
}

// When fbsignIn isset, we check to see is there a match with email and if user is logged into facebook
if (isset($_POST['fbsignIn'])){
	$fbId    = $_POST['fbid'];
	// Continue if fbid is not empty
	if (!empty($fbId)){
		$args    = array('fb_id' => $fbId);
			
		if ($user_id = User::userExist($args)){
			$user = new User($user_id);
			signIn();
		} else{
			$data['success'] = "no";
			$data['message'] = "Your Facebook account is not <br /> connected with Panramic";
		}
	} else{
		$data['success'] = "no";
		$data['message'] = "Must sign in to Facebook";
	}
	echo json_encode($data);
}

// This function sets cookies for user and signs them in
function signIn(){
	global $user, $data;
	$_SESSION['user_id'] = $user->id;
	
	//  Set a cookies
	if (!isset($_COOKIE['e'])){
		setcookie('e', $user->email, time() + (3600 * 24 * 30 * 12), '/');
	}
	if (isset($_POST['rememberMe']) || isset($_POST['fbsignIn'])){
		setcookie('a', $user->akey, time() + (3600 * 24 * 30 * 12), '/');
	}		
	$data['success'] = "yes";
	$data['message'] = MAIN_URL;
}

?>