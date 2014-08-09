<?php 
require_once('connect.php');
require_once('template.php');

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' => 'Server Error', 'content' => $content));
?>
<div id="container" style="min-height: 400px;">
	<div id="fb-root"></div>
    <p id="miscTitlel">Oops... internal server error</p>
    <div id="textLine"></div>
    <div class="miscDescription">The server encountered an internal error or misconfiguration and was unable to complete your request.</div>
<!-- #container -->
</div>
<?php get_footer(); ?>