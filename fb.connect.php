<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

$href = isset($_GET['signup']) ? '/steptwo' : '/';
 
// If update fbId isset, then update the facebook id of the user. Else display the page that directs user to facebook log in page and redirects here
if (isset($_GET['update'])){
	require_once(LIBRARY_DIR.'facebook.php');

	$FB = FB_API::initialize();
	$fbid = $FB->getUser();
	// If facebook id is not empty, then update it, and direct user to home page
	if (!empty($fbid)){
		// If fb_id exist, sign user out, else update the facebook id
		$exist = User::fbidExist($fb_id);
		if ($exist){
			header('Location:'.MAIN_URL.'logout.php');
			exit;
		} else{
			$update = "UPDATE users SET fb_id = '$fbid' WHERE user_id = '$current_user->id'";
			mysqli_query($dbc,$update);
			// uploadFbImage();
			header('Location:'.$href);
			exit;
		}
	}
}
$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('noHeader' => true, 'content' => $content)); 
?>
<div id="fb-root"></div>
<div id="container" style="background: #FFFFFF; margin-top: 15px; border: 1px solid #FFFFFF; border-radius: 3px 3px 3px 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px;">
    <p id="miscTitlel">Connect with Facebook</p>
    <div id="textLine"></div>
	<div class="groupDiv">
		<div style="float:right;margin:0 10px 0 0;"><img src="css/images/about/fbfeed.png" /></div>
		<div class="miscDescription" style="width: 460px;">
			<div class="miscTitles groupmiscTitle"></div>
			There are several reasons why you will have a better user experience if you are connected with facebook 
			<br /><br />
			1. We have developed a way to send events you are intereted in directly into your Facebook news feed. Seeing every event around you has never been simpler
			<br /><br />
			Note: You can turn this feature on/off in your settings		 
			<br /><br />
			2. Know how many of your Facebook friends are attending an event
			<br /><br />
			3. We can RSVP events on Facebook for you
			<br /><br />
			4. We have built an application within Facebook for you to quickly browse the latest events
		</div>
		<div style="margin: 30px 0 0 0">
			<div id="connectFb" class="<?php echo $href; ?>"></div>
			<a id="later" href="<?php echo $href; ?>">Maybe later</a>
		</div>
		<div id="errorMsg"></div>
	</div>
<!-- #container -->
</div>
</body>
</html>