<?php
require_once('template.php');
require_once(CLASS_DIR.'user.php');
initialize(); // Sets up db connection, user session, etc.
$groupId = (isset($_GET['g'])) ? intval($_GET['g']) : 0;
$args    = array('group_id' => $groupId);
$admin   = ($current_user->is_admin($args)) ? true : false; // determine if current user is admin if this group
$content =' <link rel="stylesheet" type="text/css" href="css/home.css" />
			<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.13.custom.css" />
			<link rel="stylesheet" type="text/css" href="css/colorpicker.css" />
			<link rel="stylesheet" type="text/css" href="css/gprofile.css" />
			<link rel="stylesheet" type="text/css" href="css/iframe.php?g='.$groupId.'"/>
			<script type="text/javascript" src="js/home.js"> </script>
			<script type="text/javascript" src="js/gprofile.js"></script>';
get_header(array('noHeader' =>  true, 'content' => $content));?>
<div id="container" style="border:none;">
	<div id="fb-root"></div>
	<div id="leftContainer">
        <img id="loading" src="css/images/icons/loading.gif" />
    	<div id="contentContainer">
    		<?php
			get_gprofile_evtWall();		
			if ($admin){
				get_home_groupManage();
			}?>
		</div>
     	<div id="sideOptionTip"></div>
        <ul id="friendTip" class="miniList"></ul>
 		<div id="deleteTip">
            <a href="#" id="removeBtn">Remove</a>
            <a href="#"id="reportBtn">Report</a>
        </div>
         <div id="notiMod" class="mod">
            <div class="modHd">Are you sure?</div>
            <div class="delete"></div>
            <div id="notiText"></div>
            <button id="confirmNoti" class="inputSubmit">Yes</button>
         </div>
		 <div id="loginMod" class="mod">
			<div class="modHd">Panramic</div>
			<div class="delete"></div>
			<p>You must <a href="<?php echo MAIN_URL; ?>" target="_parent"><u>sign up</u></a> or be <a href="<?php echo MAIN_URL; ?>" target="_parent"><u>logged into</u></a> Panramic to interact with an events and rsvp it on Facebook</p>
		</div>
    <!-- #leftContainer -->   
	</div>
    <div id="sideTip"></div>
<!-- #container -->
</div>
<?php
// If user is staff, display the js file
if ($admin): ?>
	<script type="text/javascript" src="js/home.admin.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		groupManage();
	});
	</script>
	<script type="text/javascript" src="js/colorpicker.js"></script>
	<script type="text/javascript" src="js/min/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="js/min/jquery.ui.datepicker.min.js"></script>
<?php endif; ?>
</body>
</html>