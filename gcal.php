<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'google_api.php');

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('content' => $content));
?>
<div id="container" style="min-height: 400px;">
    <p id="miscTitlel">Google Calendar</p>
    <div id="textLine"></div>
    <div class="miscDescription">
    <?php
    $info = array();
	 // If user is an admin of any group, then proceed
	if ($current_user->is_admin($info)){

		$groupId = $info['group_id'];
		$group   = new Group($groupId);
		
		// If token isset, then exchanges the single use token for session token and store it
		if (isset($_GET['token'])){
			$gcal_id  =  Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
			$update   = "UPDATE group_push SET gcal_id = '$gcal_id' WHERE group_id = '$group->id'";
			mysqli_query($dbc,$update);
			echo 'Success! Panramic is now in sync with '.$group->name.'\'s google calendar!';
		} else{
			$authSubUrl = Google_API::getAuthSubUrl();
			echo 'There was a small problem in synchronizing google calendar. <br /> <br />
				  Click <a href='.$authSubUrl.'><u>here</u></a> to grant Panramic permission to access '.$group->name.'\'s google calendar';
		}
	} else{
		echo 'There was an problem in synchronizing with google calendar. You are currently not an administrator of any group';
	}
   	?> 
    </div>
<!-- #container -->
</div>
<?php get_footer(); ?>