<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();

$content = '<link rel="stylesheet" type="text/css" href="css/stepthree.css" />
            <link rel="stylesheet" type="text/css" href="css/account.css" />';
get_header(array('title'=> 'Account','page'=>'account', 'content' => $content));
?>
<div id="container">
	<div id="fb-root"></div>
    <div id="settingsMainTitle" class="settingsTitle">Account Settings</div>
    <ul id="tabTop">
        <li><a id="tab_main" class="current" href="#">Main</a></li>
        <li><a id="tab_network" href="#">Networks</a></li>
        <li><a id="tab_field" href="#">Fields</a></li>
    </ul>
    <img id="loading" src="css/images/icons/loading.gif" />
    <div id="settingsContainer">
    	<?php get_account_main(); ?>
    </div>
</div>
<?php
$content = '<script type="text/javascript" src="js/stepthree.js"></script>
            <script type="text/javascript" src="js/account.js"></script>';
get_footer($content);
?>