<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_not_staff();
$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />
			<script type="text/javascript" src="js/stats.js"></script>';
get_header(array('content' => $content));
?>
<div id="container">
    <p id="miscTitlel">Stats</p>
    <div id="textLine"></div>
    <div>
	<ul id="pageList">
		<li><a href="#now" class="current">Now</a></li>
		<li><a href="#week">Weekly</a></li>
		<li><a href="#month">Monthly</a></li>
	</ul>
    <img id="loading" src="css/images/icons/loading.gif" />
	<?php get_stats_log(); ?>
    </div>
<!-- #container -->
</div>
<?php get_footer(); ?>