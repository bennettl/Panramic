<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(LIBRARY_DIR.'facebook.php');

// Flag that this is facebook app
$facebook = true;

// If there is no facebook_id in the database, then redirect user to facebook login page
if (isset($current_user->fb_id)){
	require_once(API_DIR.'facebook.php');

	$FB = initialize();
	$params = array(scope => FB_SCOPE,redirect_uri => MAIN_URL.API_DIR."fb.updateid.php");
	$loginUrl = $FB->getLoginUrl($params);
	header('Location:'.$loginUrl);
	exit;
}

// Log out banned users out
if ($current_user->status == 'banned'){
	$logout = "/logout";
	header('Location:'.$logout);
	exit;
}
// Update the user last visit status and user events
$current_user->updateLastVisit();
$current_user->updateEvents();

$content = '<link rel="stylesheet" type="text/css" href="css/home.css" />
			<link rel="stylesheet" type="text/css" href="css/fb.home.css" />
			<script type="text/javascript" src="js/home.js"> </script>';
get_header(array('noHeader' => true, 'content' => $content));
?>
<div id="container">
	<div id="fb-root"></div>
	<div id="leftContainer">
		<div id="fbBtn"><a href="<?php echo MAIN_URL; ?>" target="_blank">Go to site</a></div>
        <img id="loading" src="css/images/icons/loading.gif" />
    	<div id="contentContainer">
			<?php get_home_userFeed(); ?>
		</div>
        <p id="feedNoti" class="noti"></p>
     	<div id="sideOptionTip"></div>
        <ul id="friendTip" class="miniList"></ul>
 		<div id="deleteTip">
            <a href="#" id="removeBtn">Remove</a>
            <a href="#"id="reportBtn">Report</a>
        </div>
         <div id="notiMod" class="mod">
            <div class="modHd">Are you sure?</div>
            <div class="delete"></div>
            <div id="notiText"></strong></div>
            <button id="confirmNoti" class="inputSubmit">Yes</button>
         </div>
		  <form id="fbPermission" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		 	<input type="hidden" name="publishs" value="false" />
		 	<input type="hidden" name="readStream" value="false" />
		 	<input type="hidden" name="rsvp" value="false" />
		 	<input type="hidden" name="readFriend" value="false" />
		 	<input type="hidden" name="offline" value="false" />
		 </form>
    <!-- #leftContainer -->   
	</div>
    <div id="sideTip"></div>
<!-- #container -->
</div>
</body>
</html>