<?php 
require_once('template.php');
initialize(); 
global $current_user; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Panramic</title>
	<script type="text/javascript" src="/js/min/jquery-1.4.4.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){ 
			timer();
			setInterval(timer, 1000 * 60);	
		});
		timer();
		function timer(){
			console.log("im called");
			var today      = new Date();
			// Change date here
			var targetDate = new Date(today.getFullYear(), today.getMonth(), 27, 0, 0, 0, 0);
			var diffDate   = new Date(targetDate - today);
			var day        = diffDate.getDate();
			var hour       = diffDate.getHours();
			var minute     = diffDate.getMinutes();
			if (day > 1){
				day = day + " Days";
			} else if (day == 0){
				day = '';
			} else{
				day = " 1 Day";
			}
			if (minute > 1){
				minute = minute + " Minutes";
			} else if (minute == 0){
				minute = '';
			} else{
				minute = " 1 Minute";
			}
			if (hour > 1){
				hour = hour + " Hours";
			} else if (hour == 0){
				hour = '';
			} else{
				hour = " 1 Hour";
			}
			$("#hour").text(hour);
			$("#date").text(day);
			$("#minute").text(minute);
		}
	</script>
	<?php 
	if ($current_user->status != 'staff'){ get_ga_tracking(); } // Google analytics tracking script
	?>
</head>
<body style="background: url('css/images/background/lightblue.jpg')">
	<div style="position: relative; width: 1149px; height: 546px; margin: 20px auto;">
		<img src="css/images/icons/maintenance.png" style="display: block" />
		<div id="timerContainer" style="position: absolute; left: 274px; top: 212px; font-size: 14px; color: #FFFFFF;">
			<span class="int" id="date"></span> 
			<span class="int" id="hour"></span> 
			<span class="int" id="minute"></span>
		</div>
	</div>
</body>
</html>