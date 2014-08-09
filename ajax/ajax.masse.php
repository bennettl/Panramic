<?php
require_once('connect.php');
redirect_not_staff();
// if masseForm isset, then we validate the fields and send the emails to the appropriate audience
if (isset($_POST['masse'])){
	$audience  = $_POST['audience'];
	$subject   = $_POST['subject'];
	$msg 	   = $_POST['message'];
	$password  = $_POST['password'];
	$header    = "From:".ADMIN_EMAIL;
	$errorMsg  = '';
	
	// Validation
	if (empty($msg) || empty($subject)){
		$errorMsg = "Please enter all required fields";
	}
	if ($password != "blee908809eelb"){
		$errorMsg = "Invalid password";
	}
	
	// Depending on the audience, execute the appropriate select statement
	switch($audience){
		case "u":
			$select = "SELECT first_name, email FROM users";
			break;
		case "a":
			$select = "SELECT u.first_name, u.email 
					   FROM group_members AS gm
					   INNER JOIN users AS u
					   ON (gm.user_id = u.user_id)
					   WHERE gm.member_status ='admin'
					   GROUP BY u.user_id";
			break;
		default:
			$errorMsg = "Invalid audience";
			break;
	}
	$result = mysqli_query($dbc,$select);
	
	/// If there are no errors, proceed to mass email, else display the error message
	if (empty($errorMsg)){
		while ($user = mysqli_fetch_assoc($result)){
			$to          = $user['email'];
			$firstname   = $user['first_name'];
			$msg         = "Dear ".$firstname.", \n\n".trim($_POST['message'])." \n\n From \n The Panramic Team";
			mail($to,$subject,$msg,$header);
		}
		$data['response'] = "Emails successfully sent!";
	} else{
		$data['response'] = $errorMsg;
	}
	echo json_encode($data);
}
?>