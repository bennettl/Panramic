<?php
require_once('connect.php');
$to = ADMIN_EMAIL;

if (isset($_POST['feedBackForm'])){
	$user       = mysqli_fetch_assoc($result);
	$fullName	= $current_user->first_name.' '.$current_user->last_name;
	$email		= $current_user->email;
	$subject 	= "Feedback";
	$msg	 	= $fullName. " says \n\n".trim($_POST['msg']);
	$header  	= "From:".$fullName."<".$email.">";
	mail($to,$subject,$msg,$header);
}

// if joinForm isset, then we mail the form with the title Contact
if (isset($_POST['contactForm'])){
	$subject    = "Contact";
	$fullName	= htmlentities($_POST['name']);
	$msg	 	= $fullName. " says \n\n".trim($_POST['msg']);
	$header     = "From:".$fullName."<".htmlentities(trim($_POST['email'])).">";
	mail($to,$subject,$msg,$header);
}

// if joinForm isset, then we mail the form with the title Join
if (isset($_POST['joinForm'])){
	$subject    = "Join";
	$fullName	= htmlentities($_POST['name']);
	$msg	    = $fullName. " says \n\n".trim($_POST['msg']);
	$header     = "From:".$fullName."<".htmlentities(($_POST['email'])).">";
	mail($to,$subject,$msg,$header); 
}
?>