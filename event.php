<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
require_once(CLASS_DIR.'event.php');

$eventId = isset($_GET['e']) ? intval($_GET['e']) : 87;
$type    = isset($_GET['t']) ?  $_GET['t']: 'feed';

// if event doesnt exist, redirect
if (!Event::exist($eventId)){
	header('Location:'.NOT_FOUND);
	exit;
}

$event   = new Event($eventId);
$events  = array($event);

$content = '<meta property="fb:app_id" content="'.CLIENT_ID.'">
			<meta property="og:image" content="'.MAIN_URL.$event->thumbnail.'">
			<meta property="og:title" content="'.$event->name.'">
			<meta property="og:description" content="'.$event->description.'">';
// Depending on what type it is, adjust the the meta tags accordingly
if ($type == 'feed'){
	$content .= '<meta property="og:type" content="article"> 
				 <meta property="og:url" content="'.MAIN_URL.'event.php?type=feed&e='.$event->id.'">';
} else if ($type == 'action'){
	$content .= '<meta property="og:type" content="panramic:read"> 
				 <meta property="og:url" content="'.MAIN_URL.'event.php?type=action&e='.$event->id.'">  ';
}

$content .= '<link rel="stylesheet" type="text/css" href="/css/event.css" />';

get_header(array('content' => $content)); 
?>

<div id="container">
	<div id="fb-root"></div>

	<div id="leftContainer">
	
		<div class="addthis_toolbox addthis_floating_style addthis_counter_style" style="float: left; position: relative; margin: -10px 25px 0 0;" >
			<a class="addthis_button_facebook_like" fb:like:layout="box_count" addthis:title="<?php echo $event->name; ?>"></a>
			<a class="addthis_button_tweet" tw:count="vertical" addthis:title="<?php echo $event->name; ?>"></a>
			<a class="addthis_button_google_plusone" g:plusone:size="tall" addthis:title="<?php echo $event->name; ?>"></a>
			<a class="addthis_counter" addthis:title="<?php echo $event->name; ?>"></a>
			<!-- AddThis Button END -->
		</div>

	<?php get_feedlist(array('events' => $events, 'type' => 'page'));?>

	</div>
	
   	<div id="sideOptionTip"></div>
    <div id="sideTip"></div>

<?php get_footer(); ?>