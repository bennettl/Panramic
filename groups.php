<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_not_staff();

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' => 'Groups', 'content' => $content));
?>
<div id="fb-root"></div>
<div id="container">
    <p id="miscTitlel">The Ultimate Marketing Tool</p>
    <div id="textLine"></div>
	
	<div class="miscDescription">Panramic provides groups with the ultimate marketing tool. We are fully integarated with multiple social platforms, making it seamless for groups to promote and manage their events.</div>
	
	<div class="groupDiv">
		<div style="float:right;margin: 0 15px 15px 0;"><img src="css/images/about/pushpull.png" /></div>
		<div class="miscDescription" style="width: 380px;">
			<div class="miscTitles groupmiscTitle">Push/Pull</div>
			Groups should not have to go all over the place to promote their events. With our push/pull feature, groups can manage and synchronize all their events in one location. <br / > <br /> Every time you create, update, or delete an event, it will be automatically synchronized in four different places: your group's website, Facebook, Google Calendar, and Panramic.</div>
	</div>
	
	<div class="groupDiv">
		<div style="float:left;margin:5px 19px 40px 0"><img src="css/images/about/calwidget.png" /></div>
		<div class="miscDescription"> 
			<div class="miscTitles groupmiscTitle">Calendar Widget</div>
			We want to bring our technology to you. Groups who incorperate our minimalistic calendar widget into their website will have the advanatge of an updated and interactive events page.
			<br /><br /> Groups can also customize the style of their calendars so it fits seamlessly into their websites. An event management system is attached right below the widget so groups can manage their events directly from their own websites. </div>
	</div>
	
	<div class="groupDiv">
		<div style="float:right; margin:-5px 35px 25px 15px;"><img src="css/images/about/fbfeed.png" /></div>
		<div class="miscDescription" style="width:430px;">
			<div class="miscTitles groupmiscTitle">Facebook News Feed</div>Mass event invites through Facebook can only go so far. Facebook's new user interface has been designed so that while people may ignore event invites, they can't ignore their news feed. 
			<br /> <br /> 
			Panramic provides groups with an invaluable marketing asset by pushing all their events straight into our users' Facebook news feed. Establishing a presence in a user's news feed is the most powerful way to promote your events.</div>
	</div>
	
	<div class="groupDiv">
		<div style="float:left; margin: 5px 10px 0 0;"><img src="css/images/about/records.png" /></div>
		<div class="miscDescription">
			<div class="miscTitles groupmiscTitle">Records</div>
			Groups should not have to manually document their events after posting them in several social platforms. With records, you can immediately download a word document or excel file of all your events.
		</div>*/
	echo'
	</div>
<!-- #container -->
</div>
<?php get_footer(); ?>