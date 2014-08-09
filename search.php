<?php
require_once('connect.php');
require_once('template.php');
redirect_logged_out_users();
$search  = (isset($_GET['s'])) ? mysqli_real_escape_string($dbc, trim($_GET['s'])) : false;

$content = '<link rel="stylesheet" type="text/css" href="css/search.css" />
            <script type="text/javascript" src="js/search.js"></script>';
get_header(array('title' => 'Search', 'content' => $content)); ?>
<div id="container">
	<div id="fb-root"></div>
	<div id="leftContainer">
    	<img id="loading" src="css/images/icons/loading.gif" />
        <div id="searchResults" class="pageLayout">
			<?php get_search_user(); ?>
        </div>
        <div id="sideOptionTip"></div>
     	<ul id="friendTip" class="miniList"></ul>
    </div>
    <div id="rightContainer">
        <ul id="sidenavTop">
            <li><a id="tab_user" href="#<?php echo $search; ?>" class="current">People</a></li>
            <li><a id="tab_event" href="#<?php echo $search; ?>">Events</a></li>
            <li><a id="tab_group" href="#<?php echo $search; ?>">Groups</a></li>
        </ul>
    </div>
    <div id="sideTip"></div>  
<!-- #container -->
</div>
<?php get_footer(); ?>