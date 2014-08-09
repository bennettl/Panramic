<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

// Log out banned users out
if ($current_user->status == 'banned'){
	$logout = "/logout";
	header('Location:'.$logout);
	exit;
}
// Update the user last visit status and user events
$current_user->updateLastVisit();
$current_user->updateEvents();

$content 	  = '<link rel="stylesheet" type="text/css" href="css/home.css" />
			 	 <script type="text/javascript" src="js/home.js"> </script>';
// If user is staff, display the js file
if ($current_user->is_admin()){
	$content .='<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.13.custom.css" />
				<link rel="stylesheet" type="text/css" href="css/colorpicker.css" />';
} 
get_header(array('content' => $content, 'page' => 'home')); 
?>
<div id="container">
	<div id="fb-root"></div>
	<div id="leftContainer">
	<?php
	// If this is the user's first time, display the tipMod
if (isset($_GET['first'])): ?>
	<div id="firstBContainer" class="firstContainer">
		<div id="firstRdiv" class="mod firstMod">
			<strong>Remove events!</strong> Your events feed will only shows you <strong> ten events </strong>. This is mostly because we don't want you to go through page after page of events: we want you to see the most relevant events at a <strong> single glance </strong>. <br /><br /> It's <strong> perfectly normal to remove events you are not interested in. </strong> in fact, we encourage you to do so!
		</div>
				<img id="firstRimg" class="firstImg" src="css/images/icons/firstremove.png" />
	</div>
<?php endif; ?>		
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
	<div id="rightContainer">
		<?php get_home_sidenav(); ?>
    <!-- #rightContainer -->
    </div>
    <div id="sideTip"></div>
<?php
$content = '';
if ($current_user->is_admin()) {
	$content =' <script type="text/javascript" src="js/home.admin.js"></script>
				<script type="text/javascript" src="js/min/jquery.ui.core.min.js"></script>
				<script type="text/javascript" src="js/min/jquery.ui.datepicker.min.js"></script>
				<script type="text/javascript" src="js/colorpicker.js"></script>';
}
if ($current_user->is_staff()){
	$content .= '<script type="text/javascript" src="js/home.staff.js"></script>';
}
get_footer($content);
?>