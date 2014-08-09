<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_not_staff();

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' => 'guide', 'content' => $content)); 
?>
<div id="fb-root"></div>
<div id="container">
    <p id="miscTitlel">Group Guide</p>
    <div id="textLine"></div>
	
	<div class="miscDescription">Below is an overview of Panramic for groups
			<div class="miscTitles groupmiscTitle">Sign Up</div>
			To sign up your group, simply create an account, click submit group on your home page, and fill out the form. We will confirm group submissions within a few hours.<br />

			<div class="miscTitles groupmiscTitle">Post Events</div>
			To post an event, simply fill out the form. If you are connected with Facebook and Google Calendar, we will automatically create a Facebook and Google Calendar event for you. 
			<br /> <br />
* The number next to a network or field tells you how many people will you will reach <br /> <br />

* We strongly recommend posting all your events initially through Panramic as oppose to "pulling" them. This is because we can only update events created initially through Panramic. 

			<div class="miscTitles groupmiscTitle">Manage Events</div>
			In "Manage Group" under "Events", you will find a place to manage all your events. Depending on your push settings, every time you update or delete an event, it be synchronized with Facebook or Google Calendar. <br /> <br /> 
* We can only update events created initially through Panramic.

<div class="miscTitles groupmiscTitle">Push Settings</div>
			In "Manage Group" under "Main", you will find your push settings. Your push settings tells us where you want to synchronize your events. Once you selected where you want to push your events, click "Update" and your settings will be saved. If you choose to push your events to Facebook and Google Calendar, every time you post, update, or delete an event through Panramic, it will be automatically synchronized with Facebook and Google Calendar. <br /> <br />

Make sure you are connected with Facebook and Google Calendar. Being connected simply means you have given Panramic permission to manage events on your behalf in these social platforms.
	
			<div class="miscTitles groupmiscTitle">Calendar Widget</div> 
			Groups who want to push events to their website will need to install our minimalistic calendar widget. The style of their calendar widgets can be modified to fit into any site seamlessly. 
<br /> <br />
			Installing the calendar widget is easier than you think! All you need to do is copy and paste the two lines of code we provided into your website. If you are having any problem, fee free to contact us and we will help you set it up within minutes!
<br /> <br />
		</div>
   	<div id="sideOptionTip"></div>
<!-- #container -->
</div>
<?php get_footer(); ?>