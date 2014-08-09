<?php
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.

if (isset($_POST['email'])){
	$email  =  mysqli_real_escape_string($dbc,trim($_POST['email']));
	$select = "SELECT 1 FROM email WHERE email = '$email'"; 
	$result = mysqli_query($dbc,$select);
	if (!mysqli_num_rows($result)){
		$insert = "INSERT INTO email (email) VALUES ('$email')";
		mysqli_query($dbc,$insert);
	}
}
$content = '
	<link rel="stylesheet" type="text/css" href="css/misc.css" />
	<link rel="stylesheet" type="text/css" href="css/construction.css" />
	<style type="text/css">
		body{
			background:#3D3E42;
		}
	</style>
	<script type="text/javascript"> 
		$(document).ready(function(){ 
			var targetDate  = new Date(2012, 07, 20, 0, 0, 0, 0);
			var today 		= new Date();
			var diffDate    = new Date(targetDate - today);
			var month 		= diffDate.getMonth();
			var hour 		= diffDate.getHours();
			var day 		= diffDate.getDate();
			var monthStr 	= (month > 1) ? "Months" : "Month"; 
			var hourStr 	= (hour > 1) ? "Days" : "Day"; 
			var dayStr 		= (day > 1) ? "Hours" : "Hour"; 
			$("#month").text(month);
			$("#hour").text(hour);
			$("#date").text(day);
			
			$(".inputText").focus(function() {
			    if ($(this).val() == "Email"){
					$(this).val("");
					$(this).css("color","#333333");	
				} 
	        });
			
			$(".inputText").blur(function(){
	            if (!$(this).val()){
					$(this).css("color","#898989");	
					$(this).val("Email");			
				}
	        });
			//$("#footer").css("position","relative");
	    });
	</script>';
get_header(array('noHeader' => true, 'content' => $content));
?>
<div id="container" style="padding: 0 0 15px;">
	<div class="groupDiv">
			<img id="logoConstruction" src="/css/images/dark.png" />
			<div id="timerContainer">
				<div>
				<!-- <span class="int" id="month">03</span> <span class="string">Months</span> -->
				<span class="int" id="date">02</span> <span class="string">Days</span>
				<span class="int" id="hour">01</span> <span class="string">Hours</span>
				</div>
			</div>
			<iframe id="video" src="http://www.youtube.com/embed/5ceozhmVzoE?autohide=1&rel=0" frameborder="0" allowfullscreen></iframe>
<iframe class="videoLike" style="display: block; width: 100%; margin: 5px 0; color: white; height: 60px;" src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpanramic&amp;send=false&amp;layout=standard&amp;width=650&amp;show_faces=true&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=80&amp;appId=258940897480780" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:650px; height:50px;" allowTransparency="true"></iframe></div>           
		<div class="miscDescription">
			<p> Panramic will be undergoing some major changes over the summer. Seeing every event around you will be effortless.</p>
			<p>If you are not already signed up and would like to be updated about our progress, feel free to sign up to our email list.</p>
			</div>
			<form id="emailForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input class="inputText" type="text" name="email" value="Email"/>
				<input class="inputSubmit" type="submit" value="Enter"/>
			</form>
	</div>
   	<div id="sideOptionTip"></div>
<!-- #container -->
</div>
</body>
</html>