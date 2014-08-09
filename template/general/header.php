<?php 
if (isset($args)){extract($args);} //Get variables from args 
global $dbc, $current_user; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/universal.css" />
	<link rel="stylesheet" type="text/css" href="/css/input.css" />
	<script type="text/javascript" src="/js/min/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="/js/universal.js"></script>
	<script src="http://connect.facebook.net/en_US/all.js" charset="utf-8"></script>
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-5027722509096470"></script>
	<title><?php echo (isset($title)) ? $title : 'Panramic'; ?></title>
	<?php if (isset($content)) { echo $content; } // Script/meta tags will be placed here
	if ($current_user->status != 'staff'){ get_ga_tracking(); } // Get google analytics tracking script ?>
</head>
<body>
<?php
// Do not display header if noHeader isset
if (!isset($noHeader)): ?>
	<div id="hd">
		<div id="hdContainer">
			<a href="/"><img id="logo" alt="logo" src="/css/images/icons/logo2.png" /></a>
		<?php
		// Depending if the user is signed in or not, we will show the corresponding header   
		if (!isset($_SESSION['user_id'])) : ?>
			<a id="signIn" href="#">Log In</a>
			<?php get_signIn_form(); ?>
		<?php
		elseif (isset($_SESSION['user_id'])):
			$href = htmlentities($current_user->href_name);
			// Depending if the user is in his home, profile, or account page, we will add the corresponding current class
		?>
				<ul id="menu">
					<li><a href="/" <?php if ($args['page'] == 'home') {echo ' class="currentMenu"'; }?> >Home</a></li>
					<li><a href="/<?php echo $href; ?>" <?php if ($args['page'] == 'profile') {echo ' class="currentMenu"';}?>>Profile</a></li>
					<li><a id="account" href="#" <?php if ($args['page'] == 'account') {echo ' class="currentMenu"'; }?>>Account </a></li>
				</ul>
				<ul id="dropdown">
					<li><a href="/account">Settings</a></li>
					<li><a href="/logout">Log Out</a></li>
				</ul>
				
				<div class="searchContainer">
				<form id="searchForm" action="/search.php" method="get">
					<input class="inputText searchText" type="text" name="s"
					<?php
					if (isset($_GET['s'])){
						$searchVal = mysqli_real_escape_string($dbc,strip_tags($_GET['s']));
						echo 'value = "'.$searchVal.'"';
					}
					?>
					 autocomplete="off" />
					<input class="inputSubmit searchSubmit" type="submit" value=""/>
				</form>
				</div>		
		<?php endif; ?>
		<!-- #hdContainer -->
		</div> 
	<!-- #hd -->
	</div>
	<div id="wrapContainer">
<?php endif; // If !$noHeader?>
