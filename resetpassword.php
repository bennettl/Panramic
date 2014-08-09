<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />
			<script type="text/javascript" src="js/resetpassword.js"></script>';
get_header(array('content' => $content)); 
?>
<div id="container">
    <p id="miscTitlel">Reset Password</p>
    <div id="textLine"></div>
    <div class="miscDescription">
		<p id="passNoti"> Please enter your email address below:</p>
		<form id="passwordForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="text" class="inputText" name="email" autocomplete="off" />
			<input type="submit" class="inputSubmit" name="send" value="Send" />
		</form>
    </div>
</div>
<?php get_footer(); ?>