<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

$content = '<link rel="stylesheet" type="text/css" href="css/misc.css" />';
get_header(array('title' =>'Not Found', 'content' => $content)); 
?>
<div id="container" style="min-height: 400px;">
	<div id="fb-root"></div>
    <p id="miscTitlel">Oops... page not found</p>
    <div id="textLine"></div>
    <div class="miscDescription">The page you requested was not found, please make sure you entered the correct url.</div>
<!-- #container -->
</div>
<?php get_footer(); ?>
