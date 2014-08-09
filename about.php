<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';

get_header(array('title' => 'About', 'content' => $content)); ?>
<div id="fb-root"></div>
<div id="container">
    <p id="miscTitlel">See every event around you</p>
    <div id="textLine"></div>
	
	<div class="miscDescription">Panramic pulls together <strong> every event</strong> through a single network, filters them based on the person, and pushes them to your Facebook news feed. <br /> <br /></div>
	
	<div class="groupDiv">
		<div style="float: right;margin:0 20px 10px 0;"><img src="css/images/about/spam.png" /></div>
		<div class="miscDescription" style="width: 470px;">
			<div class="miscTitles groupmiscTitle">No more flyers</div>You shouldn't have to go through posters, flyers, newsletters, text messages, facebook invites, catalogues, and pamphlets to simply know what's going on. <br /><br />
			Panramic takes the clutter away and helps you see every event around you at a single glance.
			</div>
	</div>
	
	<div class="groupDiv">
		<div style="float:left;margin:0 40px 40px 0;"><img src="css/images/about/feed.png" /></div>
		<div class="miscDescription"> 
			<div class="miscTitles groupmiscTitle">Networks/Fields</div>
			The events feed is designed to be simple and personal. Base on the networks you are a part of and the fields you are interested in, you will be connected with relevant events. <br /> <br />
			Networks are based on your city and university.<br /> <br />
			Fields are focused on the types of events you are interested in.
			</div>
	</div>
	
	<div class="groupDiv">
		<div style="float:right;margin:0 10px 0 0;"><img src="css/images/about/fbfeed.png" /></div>
		<div class="miscDescription" style="width: 440px;">
			<div class="miscTitles groupmiscTitle">Facebook Integration</div>Events that you can find on Facebook are often limited by your friends. This is part of the reason why there is always a performance, comedy show, concert, networking event, or house party you are missing out on. It's time to change that.
			<br /><br />
			 Panramic is fully integrated with Facebook. You can now have a private and personal events feed in your Facebook news feed. We have also built an application within Facebook for you to quickly browse the latest events.
			<br /><br />
			Seeing every event around you has never been simpler.
		</div>
	</div>
	
	<div class="groupDiv">
		<div style="float:left;margin:0 40px 40px 0;"><img src="css/images/about/foodfeed.png" /></div>
		<div class="miscDescription"> 
			<div class="miscTitles groupmiscTitle">Free lunch for a week</div>
			Introducing one of our most popular features: the free food filter. With a click of a button, you can see every event on campus with free food.<br /> <br />
			</div>
	</div>
	<!-- #container -->
</div>
<?php get_footer(); ?>