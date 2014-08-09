<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' => 'Privacy', 'content' => $content)); 
?>
<div id="container">
    <p id="miscTitlel">Privacy Policy</p>
    <div id="textLine"></div>
    <div class="miscDescription">
		<span>Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, and disclose personal information.</span><br /><br />
		
		<div class="miscTitles">Information we collect</div>
		<span>When you sign up for Panramic you provide us with your full name, email, usc email, sex, birthdate, networks, and fields. We also collect other information such as your profile picture, hometown, and your future and past events.</span><br /><br />
		
		<div class="miscTitles">Information you share</div>
		<span>You can control how your information is shared through your privacy settings. There are only two settings: friends and everyone.  Your privacy settings is set to "friends" by default. As the name implies, only your friends will have access to your general information and future events. When you change your privacy settings to "everyone", you are allowing everyone, including unregister users, to access your information.</span><br /><br />
		
		<div class="miscTitles">Information we use</div>
		<span>We use information you provide (networks, fields, etc.) in order to provide you with the best experience in connecting with relevant events and groups.</span><br /><br />
		
		<span>Last Revision: July 15, 2011</span>
	</div>
<!-- #wrapContainer -->
</div>
<?php get_footer(); ?>