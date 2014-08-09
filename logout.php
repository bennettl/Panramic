<?php
// Unset all session varibles by assigning it to empty array, then we expire the cookie, and then we destry session
session_start();

if (isset($_COOKIE['a'])){
	setcookie('a','',time() - 3600);
}

if (isset($_SESSION['user_id'])){
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])){
		setcookie(session_name(),'', time() - 3600);
	}
	session_destroy();
}

$main = 'http://'.$_SERVER['HTTP_HOST'];
header('Location:'.$main)
?>