<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

// Redirect user if there isn't an activation key
if (isset($_GET['k'])){
	$akey = mysqli_real_escape_string($dbc,trim($_GET['k']));
} else{
	header('Location:'.MAIN_URL);
	exit;
}
$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('content' => $content));
?>
<div id="container" style="min-height: 400px;">
	<div id="fb-root"></div>
    <p id="miscTitlel">Activation</p>
    <div id="textLine"></div>
    <div class="miscDescription">
    <?php
	// Update the account status
    $update = "UPDATE users SET user_status ='active' WHERE akey ='$akey' AND user_status = 'pending'";
	mysqli_query($dbc,$update);
	
	$row = mysqli_affected_rows($dbc);
	$message = ($row == 1) ? "Account successfully activated!" : "There was a problem activating your account. Either your account was already activated or you entered an invalid key";
	echo $message;
   	?> 
    </div>
</div>
<?php get_footer(); ?>