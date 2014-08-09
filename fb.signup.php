<?php 
require_once('template.php');
$content = '<link rel="stylesheet" type="text/css" href="css/fb.css" />
			<script type="text/javascript" src="js/fb.js"></script>
			<script type="text/javascript" src="js/signup.js"></script>';
get_header(array('noHeader' => true, 'content' => $content));
?>
<div id="container">
	<div id="fb-root"></div>
	<div id="titleContainer">
		<div id="title">Panramic</div>
		<div id="subTitle">see every event around you <a id="learnText" href="<?php echo MAIN_URL; ?>about" target="_blank">Learn more...</a></div>
	</div>
	<ul id="fbmenu">
		<li><a href="#welcome" class="current">Welcome</a></li>
		<li><a href="#signInForm">Log in</a></li>
		<li style="border: none;"><a href="#signUpForm">Sign up</a></li>
	</ul>
	<div id="fbcontentContainer">
		<div id="welcome">
			<iframe id="video" style="display: block;" src="http://www.youtube.com/embed/5ceozhmVzoE?autohide=1&rel=0" frameborder="0" allowfullscreen></iframe>
			<iframe class="videoLike" style="display: block; width: 630px; height: 75px;" src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpanramic&amp;send=false&amp;layout=standard&amp;width=650&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80&amp;appId=258940897480780" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:650px; height:80px;" allowTransparency="true"></iframe>
		</div>
		<?php get_signIn_form(); ?>
		<?php get_signUp_form(); ?>
	</div>
<!-- #container-->	
</div>
</body>
</html>