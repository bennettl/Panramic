<?php // Generates the time option fields ?>
<?php echo $time; ?>
<option value="-1"> Select Time</option>
<?php
// These loops creates the timer and if the post startime matches any of these time, it will be selected. We also make sure its zero filled and switch the am/pm
for ($count = 1; $count < 12; $count ++){
	$timeVal = ($count + 12).':00:00';
	if ($time == $timeVal){ echo '<option selected="selected" value="'.$timeVal.'">'.$count.':00 pm</option>'; } 
		else{ echo '<option value="'.$timeVal.'">'.$count.':00 pm</option>'; }
	
	$timeVal = ($count + 12).':30:00';
	if ($time == $timeVal){ echo '<option selected="selected" value="'.$timeVal.'">'.$count.':30 pm</option>'; } 
		else{ echo '<option value="'.$timeVal.'">'.$count.':30 pm</option>'; }				 
}                    

if ($time == '00:00:00'){echo '<option selected="selected" value="00:00:00">12:00 am</option>'; } 
	else{ echo '<option value="00:00:00">12:00 am</option>'; }
	  
if ($time == '00:30:00'){ echo '<option selected="selected" value="00:30:00">12:30 am</option>';  }
	else{ echo '<option value="00:30:00">12:30 am</option>';}

 for ($count = 1; $count < 13; $count ++){
	$timeVal = ($count < 10) ? '0'.($count).':00:00': ($count).':00:00';
	$timeEnd = ($count < 12) ? "am" : "pm";

	if ($time == $timeVal){ echo '<option selected="selected" value="'.$timeVal.'">'.$count.':00 '.$timeEnd.'</option>'; } 
		else{ echo '<option  value="'.$timeVal.'">'.$count.':00 '.$timeEnd.'</option>'; }
	
	$timeVal = ($count < 10) ? '0'.($count).':30:00': ($count).':30:00';
	if ($time == $timeVal){ echo '<option selected="selected" value="'.$timeVal.'">'.$count.':30 '.$timeEnd.'</option>';}
		else{ echo '<option value="'.$timeVal.'">'.$count.':30 '.$timeEnd.'</option>';}				 
}      
?>