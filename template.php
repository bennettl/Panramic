<?php 
require_once('constant.php');

/* Template functions */

/* Initialization template */
function initialize(){
	require_once(INCLUDES_DIR.'connect.php');
}

/* General */
// $content is added right before the head tag
function get_header($args = null){
	require_once(GENERAL_DIR.'header.php');
}
function get_signin_form(){
	require_once(GENERAL_DIR.'header.signin.php');
}
function get_feedlist($args){
	extract($args); // args include event(object), type ('feed', 'mediumList', 'page')
	require_once(GENERAL_DIR.'feedlist.php');
}
function get_ga_tracking(){
	require_once(GENERAL_DIR.'ga.tracking.php');	
}

// $content is added right before the body tag
function get_footer($content = null, $args = null){
	require_once(GENERAL_DIR.'footer.php');
}

/* Home */
function get_home_userFeed(){
	require_once(HOME_DIR.'home.userFeed.php');
}
function get_home_sidenav(){
	require_once(HOME_DIR.'home.sidenav.php');
}
function get_home_groupManage(){
	require_once(HOME_DIR.'home.groupManage.php');
}

/* Profile */
function get_profile_evtWall(){
	require_once(PROFILE_DIR.'profile.evtWall.php');
}

function get_profile_sidenav(){
	require_once(PROFILE_DIR.'profile.sidenav.php');
}

/* Group Profile */
function get_gprofile_evtWall(){
	require_once(GPROFILE_DIR.'gprofile.evtWall.php');
}
function get_gprofile_sidenav(){
	require_once(GPROFILE_DIR.'gprofile.sidenav.php');
}

/* Account */
function get_account_main(){
	require_once(ACCOUNT_DIR.'account.main.php');
}

/* Search */
function get_search_user(){
	require_once(SEARCH_DIR.'search.user.php');
}

/* SignUp */
function get_signUp_form(){
	require_once(SIGNUP_DIR.'signup.form.php');	
}

/* Stats */
function get_stats_log(){
	require_once(STATS_DIR.'stats.log.php');
}

/* Form */

// Time determines which option field will be selected
function get_form_time($time){
	require(FORM_DIR.'form.time.php');
}

// Count will display the total user count next to the network/field
function get_form_guestList($countGuests = false){
	require_once(FORM_DIR.'form.guestlist.php');
}

/* Ajax calls for templates */
if (isset($_POST['template'])){
	$file      = $_POST['file'];
	$path      =  get_directory($_POST['filebase']).$file;
	require_once($path);
}

// Return directory constant base on filebase
function get_directory($filebase){
	switch ($filebase) {
		case 'home':
			return HOME_DIR;
			break;
		case 'gprofile':
			return GPROFILE_DIR;
			break;
		case 'profile':
			return PROFILE_DIR;
			break;
		case 'account':
			return ACCOUNT_DIR;
			break;
		case 'search':
			return SEARCH_DIR;
			break;
		case 'stats':
			return STATS_DIR;
			break;
		default:
			return '/';
			break;
	}
}
?>