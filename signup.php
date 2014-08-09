<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
$content = '<meta property="og:type" content="website" />
            <meta property="og:title" content="Panramic" />
            <meta property="og:url" content="'.MAIN_URL.'" />
            <meta property="og:image" content="'.MAIN_URL.'css/images/icons/p.png" />
            <meta property="og:description" content="See every concert, performance, comedy show, and networking opportunity on campus and your city" />
            <meta name="Description" content="See every concert, performance, comedy show, and networking opportunity on campus and your city">
            <link rel="stylesheet" type="text/css" href="css/signup.css" />
            <script type="text/javascript" src="js/signup.js"></script>';
get_header(array('content' => $content)); 
?>

<div id="fb-root"></div>
<div id="container">
    <div class="leftContainer">
		<iframe id="video" src="http://www.youtube.com/embed/5ceozhmVzoE?autohide=1&rel=0" frameborder="0" allowfullscreen></iframe>
    <iframe class="videoLike" src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpanramic&amp;send=false&amp;layout=standard&amp;width=650&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80&amp;appId=258940897480780" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:650px; height:80px;" allowTransparency="true"></iframe></div>           
    <div class="rightContainer">
	    <div id="signUpText">See every event around you</div>
		  <p id="learnText"><a href="about">Learn more...</a></p>
      <?php get_signUp_form(); ?>     
   <!-- .rightContainer -->   
  </div>
  <!-- #container --> 
</div>
<?php get_footer(); ?>