<?php
require_once('template.php');
require_once (CLASS_DIR.'group.php');
$group    = new Group($groupId);
$facebook = (isset($_GET['fb'])) ? true : false;  // Check if user is in facebook, and set flag
$time     = time(); // Use to make sure photos are fresh
$content  = '<link rel="stylesheet" type="text/css" href="css/gprofile.css" />
			 <script type="text/javascript" src="js/gprofile.js"></script>';
get_header(array('title' => $group->name, 'content' => $content));
?>
<div id="container" 
<?php
// If user is in facebook, change style of container
if ($facebook){
	echo 'style="width: 740px; border: none;"';
}
echo'
>';
?>
<div id="fb-root"></div>
    <div id="leftContainer">
        <div id="tProfile">
            <div id="tlProfile">
				<i id="profileImg" style="background: url('images/groups/m<?php echo $group->id.'.jpg?'.$time; ?>') no-repeat 50% 30%;" /> </i>
				<div id="profileDivider"></div>
            </div>             
            <div id="trProfile">
            	<div id="profileName"><?php echo $group->name; ?></div>
            	<?php if ($current_user->id && !$group->hasMember($current_user)): // if current user isnt member ?>
						<div id="addBtn">Join</div>
				<?php endif; ?>
				<ul id="sideimgContainer">
					<?php
					for ($i = 1; $i < 4; $i++){
						echo '<li><img class="sideImg" src="images/groups/s'.$i.$group->id.'.jpg?'.$time.'" /></li>';
					}
					?>
			 	</ul>
			 	<?php
				// If user is using facebook, then display back button
				if ($facebook){
					echo'<div id="fbBtn"><a href="'.MAIN_URL.'fb.index.php">Back</a></div>';
				}
			 	?>
             <!-- #trProfle -->
             </div>
        <!-- #tProfile -->
        </div>        
        <div id="bProfile">
        	<ul id="tabTop">
                <li><a id="tab_evtWall" href="#<?php echo $group->id; ?>" class="current">Events</a></li>
                <li><a id="tab_infoWall" href="#<?php echo $group->id; ?>">About Us</a></li>
            </ul>
            <img id="loading" src="css/images/icons/loading.gif" />
            <div id="contentContainer">
				<?php get_gprofile_evtWall(); ?>
            </div>
        </div>
        <?php
		// If user is not in facebook, display image stream divs
		if (!$facebook){
			echo'
			<div id="photoOverlay"></div>
			<img id="photoExpand" />';
		}
		?>
        <div id="deleteTip">
            <a href="#" id="removeBtn">Remove</a>
            <a href="#"id="reportBtn">Report</a>
        </div>
        <div id="sideOptionTip"></div>
		<ul id="friendTip" class="miniList"></ul>
        <form id="groupInfo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
        </form>
     <!-- #leftContainer -->
     </div>
	 <?php
	 // If user is not in facebook, display #rightContainer
	 if (!$facebook): ?>
		 <div id="rightContainer">
			<?php get_gprofile_sidenav(); ?>
		 </div>
	 <?php endif; ?>
     <div id="sideTip"></div>  
<!-- #container -->
</div>
<?php get_footer(); ?>