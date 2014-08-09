<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
redirect_logged_out_users();

// If groupsubmit isset, proccess the form and display the appropriate response
if (isset($_POST['groupSubmit'])){
	$group              = new Group();
	// info
	$group->name        = $_POST['name'];
	$group->email       = $_POST['email'];
	$group->street      = (isset($_POST['street'])) ? $_POST['street'] : "N/A";
	$group->venue       = $_POST['venue'];
	$group->locality    = $_POST['locality'];
	$group->region      = $_POST['region'];
	$group->postal      = $_POST['postal'];
	$group->description = $_POST['description'];
	$group->gender      = 'N';
	$group->network_id  = $_POST['network'];
	$group->field_id    = $_POST['field'];
	$group->href_name   = keyGen("href_name","hrefs",10);
	//image
	$group->img_name    = 'm';
	$group->size        = $_FILES['m']['size'];
	$group->tmp_path    = $_FILES['m']['tmp_name'];
	$group->img_type    = $_FILES['m']['type'];
	$args               = array('type' => 'post');

	// If it passes insert group information into database and image into filesystem
	if ($group->valid($args)){
		$group->post($user_id);
		echo'
		<div id="success">yes</div>
		<div id="message">Group successfully submitted!</div>';
	} else{
		echo'
		<div id="susccess">no</div>
		<div id="message">'.$group->errorMsg.'</div>';
	}	
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
		$select = "SELECT 1 FROM ".$table." WHERE ". $field ."= '$key'";
		$result = mysqli_query($dbc,$select);
	}
	while (mysqli_num_rows($result) != 0);
	return $key;
}
?>