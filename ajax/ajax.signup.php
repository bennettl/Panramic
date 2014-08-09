<?php
require_once('connect.php');
require_once(CLASS_DIR.'user.php');

// When signUp isset, create a new user and sign him up
if (isset($_POST['signUp'])){
	$user             = new User();
	$user->first_name = $_POST['firstname'];
	$user->last_name  = $_POST['lastname'];
	$user->fb_id      = (isset($_POST['fbid'])) ? $_POST['fbid'] : '0';
	$user->sex        = ($_POST['gender'] == 'male') ? 'Male' : 'Female';
	$user->email      = $_POST['email'];
	$user->akey       = keyGen("akey", "users", 20);	
	$user->href_name  = keyGen("href_name", "hrefs", 10);
	$password         = (isset($_POST['password'])) ? $_POST['password'] : 'N/A' ;
	$salt             = sha1(md5($password));
	$user->password   = ($password == 'N/A') ? $password : sha1($salt.$password);
	$birthday         = mysqli_real_escape_string($dbc,trim($_POST['birthday']));
	$month            = substr($birthday,0,2);
	$date             = substr($birthday,3,2);
	$year             = substr($birthday,6,4);
	$user->birthday   = $year.'-'.$month.'-'.$date;
	$args 			  = array('type' => 'post');

	// If there are no errors, then insert user into users table, set session and cookie, and redirect user to step two
	if ($user->valid($args)){
		$user->post(); // Insert user into db and file system
		sendEmail($user); // Send activation email

		// Set session and cookie
		$_SESSION['user_id'] = $user->id;
		setcookie('a', $user->akey, time() + (3600 * 24 * 30 * 12),'/');
		setcookie('e', $user->email, time() + (3600 * 24 * 30 * 12),'/');
		
		$data['success']  = "yes";
	} else{
		$data['success']  = "no";
		$data['response'] = $user->errorMsg; 
	} 
	echo json_encode($data);
}

// Creates random unique key. Basically, what field are you checking (from users) and what is the keyLength
function keyGen($field,$table,$keyLength) {
	global $dbc;
	$string = "0123456789abcdefghijklmnopqrstuvwxyz";
	do{
		$key = '';
		for ($var = 0; $var < $keyLength; $var++){
			$key .= $string[mt_rand(0, strlen($string) -1)];
		}
		$select = "SELECT 1 FROM ".$table." WHERE ".$field."= '$key'";
		$result = mysqli_query($dbc,$select);
	}
	while (mysqli_num_rows($result) != 0);
	return $key;
}

// This function sends the activiation email
function sendEmail($user){
	$to      = $user->email;
	$subject = "Registration at Panramic";
	$aLink   = MAIN_URL."activate.php?k=".$user->akey;
	$msg     = "Dear ".$user->first_name.", \n\n Thank you for registering at Panramic! We require that you validate your registration in order to ensure that the email address you entered was correct. In order to activate your account, simply click the following link \n\n ".$aLink." \n\n If you did not register at our website, please notify us immediately.\n\nFrom\nThe Panramic Team";
	$header  = "From:".ADMIN_EMAIL;
		
	mail($to,$subject,$msg,$header);
		
}
?>