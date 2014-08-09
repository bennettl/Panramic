<?php 
require_once("template.php");
initialize(); // Sets up db connection, user session, etc.

$content ='<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' => 'Terms', 'content' => $content));
?>
<div id="container">
	<div id="fb-root"></div>
    <p id="miscTitlel">Terms of Service</p>
    <div id="textLine"></div>
    <div class="miscDescription">
		<div class="miscTitles">Terms</div>
		<span>By using our services you are agreeing to be bound by the the following terms:</span>
		<ol>
			<li>1. You will not spam any user.</li>
			<lI>2. You will not upload viruses or other malicious code.</li>
			<lI>3. You will not steal any user's login information or access an account belonging to someone else.</li>
			<lI>4. You will not bully, intimidate, or harass any user.</li>
			<lI>5. You will not post content that is violent, hateful, threatening, or pornographic.</li>
			<lI>6. You will not use Panramic to do anything unlawful, misleading, or malicious.</li>
			<lI>7. You will not provide any false personal information, impersonate others, or create an account for anyone other than yourself.</li>
			<lI>8. You will not create more than one personal profile.</li>
			<lI>9. If we disable your account, you will not create another one without our permission.</li>
		</ol>
		
		<span>If you do not agree with any of these terms, you are prohibited from using or accessing Panramic. The materials contained in Panramic are protected by applicable copyright and trade mark law.</span> <br /> <br />
		
		<div class="miscTitles">Our Rights</div> 
		<span>We reserve the rights to:</span><br />
		<ol>
			<lI>1. Send warnings or ban users who have violated any of the above terms.</li>
			<lI>2. Remove or reclaim your username it if we believe it is appropriate.</li>
			<lI>3. Remove any content that are consider hateful, threatening, pornographic, misleading, or malicious.</li>
			<lI>4. Make changes to our Terms of Service for legal or administrative reasons, or to correct an inaccurate statement.</li>
			<lI>5. Contact users through email regarding any major updates or revisions.</li>
			<lI>6. Used the names of submitted groups at Panramic in promoting our services.</li>
			<li>7. Modify or make revisions to group information it if we believe it is appropriate.</li>
		</ol>
		
		<div class="miscTitles">Privacy</div>
		<span>Privacy settings control who can see your general information, friends, and future events. You can control how your information is shared through your privacy settings. There are only two settings: friends and everyone.  Your privacy settings is set to "friends" by default. As the name implies, only your friends will have access to your general information, friends, and future events. When you change your privacy settings to "everyone", you are allowing everyone, including unregister users, to access your information. For more information regarding privacy, please visit our <a href="privacy">Privacy Policy</a>.</span> <br /> <br />
		
		<div class="miscTitles">Disclaimer</div>
		<span>While we try our best to make Panramic as safe and spam free as possible, understand that you are ultimately responsible for your use of the services we provide, any personal information you choose to share, and any consequences thereof. </span> <br /> <br />
		
		<span>Last Revision: July 15, 2011</span>
	
	</div>
<!-- #container -->
</div>
<?php get_footer(); ?>