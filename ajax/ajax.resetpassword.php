<?php
require_once('connect.php');

// If resetPassword isset, we check to see if the email is valid and take the appropriate action
if (isset($_POST['resetPassword'])){
	$email   = mysqli_real_escape_string($dbc,trim($_POST['email']));
	$select  = "SELECT user_id FROM users WHERE email = '$email'";
	$result  = mysqli_query($dbc,$select);
		
	if (mysqli_num_rows($result) == 1){
		$newPass = keyGen(10);
		$user    = mysqli_fetch_assoc($result);
		$user_id = intval($user['user_id']);
		$user    = new User($user_id);
		$args    = array('newPass' => $newPass, 'type' => 'reset');
		$user->changePassword($args);		
		sendEmail();
		
		$data['success']  = "yes";
		$data['response'] = "New password sent to ".$user->email;
	} else{
		$data['success']  = "no";
		$data['response'] = "Email not found. Please try again";
	}
	echo json_encode($data);
	exit;
}

// Creates random password of length $keyLength
function keyGen($keyLength) {
	$string  = "0123456789abcdefghijklmnopqrstuvwxyz";
	$key 	 = '';
	for ($var = 0; $var < $keyLength; $var++){
		$key .= $string[mt_rand(0, strlen($string) -1)];
	}
	return $key;
}

// Send email with new password to user
function sendEmail(){
	global $user, $newPass;
	// Send the password email
	$to 	  = $user->email;
	$subject  = "New Password";
	$msg	  = "Dear ".$user->first_name.", \n\n Here is the temporary password you have requested. Please remember to change it once you sign in. \n\n New password: ".$newPass."\n\n From \n The Panramic Team ";
	$header   = "From:".ADMIN_EMAIL;
	mail($to,$subject,$msg,$header);
}
?>