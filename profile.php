<?php
require_once('template.php');
require_once(CLASS_DIR.'user.php');
// Check if user is in facebook
$facebook 	   = (isset($_GET['fb'])) ? true : false;
// Find privacy settings of the user and filter page accordingly
$profile_user = new User($profileId);
$content = '<link rel="stylesheet" type="text/css" href="css/profile.css" />
			<script src="http://connect.facebook.net/en_US/all.js" charset="utf-8"></script>
			<script type="text/javascript" src="js/profile.js"></script>';
get_header(array('title' => $fullname, 'page' => 'profile', 'content' => $content)); ?>
<div id="container" 
<?php
// If user is in facebook, change style of container
if ($facebook){ echo 'style="width: 740px; border: none;"'; }
?>
>
<div id="fb-root"></div>
	<div id="leftContainer">
        <div id="tProfile">
			<i id="profileImg" style="background: url('images/users/p<?php echo $profile_user->id.'.jpg?'.time(); ?>') no-repeat 50% 30%;" /> </i>
            <div id="profileDivider"></div>
            <ul id="profileNetwork">
            	<?php
          		// Loop through profile user networks and display them
				foreach ($profile_user->networks as $network => $networkId){
					$xPos = ($networkId - 1) * -33;
					echo '<li><i class="'.$network.'" style="background: url('.CSS_DIR.'images/mini/mini.png) no-repeat '. $xPos .'px 0px;"></i></li>';
				}
				?>
            </ul>
            <div id="profileName"><?php echo $profile_user->first_name.' '.$profile_user->last_name; ?></div>
              <?php
			  // If user is using facebook, then display back button
			  if ($facebook){
				  echo'<div id="fbBtn"><a href="'.MAIN_URL.'fb.index.php">Back</a></div>';
			  }
			  ?>
        <!-- #tProfile -->
        </div>
        <div id="bProfile">
        	<ul id="tabTop">
				<li><a href="#<?php echo $profile_user->id; ?>" id="tab_evtWall" class="current">Events</a></li>
				<?php
				if (true){
					echo '<li><a href="#'.$profile_user->id.'" id="tab_infoWall">Info</a></li>';
				}
				?>
            </ul>
	        <img id="loading" src="css/images/icons/loading.gif" />
            <div id="contentContainer">
            	<?php get_profile_evtWall(); ?>
            </div>
        <!-- #bProfile -->
        </div>
        <div id="sideOptionTip"></div>
     	<ul id="friendTip" class="miniList"></ul>
     <!-- #leftContainer -->
    </div>
    <?php
	if (!$facebook): // If user is not in facebook, display #rightContainer ?>
		<div id="rightContainer">
			<?php get_profile_sidenav(); ?>
		</div>
	<?php endif; ?>
	<div id="sideTip"></div>  
<!-- #container -->
</div>
<?php get_footer(); ?>