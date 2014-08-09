<?php
require_once('connect.php');
redirect_not_staff();
global $dbc, $date, $users, $groups, $events, $eventsPush, $visits;
$timeframe = isset($_POST['timeframe']) ? htmlentities($_POST['timeframe']) : "";
?>
<div id="statsLog">
	<table id="statsContainer">
	<?php
	// Depending on which timeframe is set, perform the appropriate action
	if ($timeframe == 'week'){
		// Select everything within a week from today
		$select  = "SELECT * FROM logs WHERE date > DATE_SUB(CURDATE(),INTERVAL 8 DAY) ORDER BY date DESC";
		$result  = mysqli_query($dbc,$select);
		while ($log = mysqli_fetch_assoc($result)){
			$date 	 	 = date('F j',strtotime($log['date']));
			$users   	 = $log['users'];
			$groups  	 = $log['groups'];
			$events    	 = $log['events'];
			$eventsPush  = $log['events_push'];
			$visits  	 = $log['visits'];
			displayStats();
			
		}
	} else if ($timeframe == 'month'){
		$month = date('m', time());
		$year  = date('Y', time());
		// Start from current month then loop backwards to july
		for ($i = intval($month, 10); $i > 6; $i--){
	
			// If this is current month, select the lastest entry of the month, else select the latest date of the month. Also select the average visit percentage of the month
			if ($i == $month){
				$select = "SELECT date,users,groups,events,visits FROM logs WHERE date = DATE_SUB(CURDATE(),INTERVAL 1 DAY)
						   UNION ALL
						   SELECT date,users,groups,events,AVG(visits) FROM logs WHERE DATE_FORMAT(date,'%m') = DATE_FORMAT(CURDATE(),'%m')";
			} else {
				$select = "SELECT date,users,groups,events,visits FROM logs WHERE date = LAST_DAY('$year-$i-01')
						   UNION ALL
						   SELECT date,users,groups,events,AVG(visits) FROM logs WHERE DATE_FORMAT(date,'%c') = '$i'";
			}
			$result  	 = mysqli_query($dbc,$select);
			$log     	 = mysqli_fetch_assoc($result);
			$date 	  	 = date('F',strtotime($log['date']));
			$users   	 = $log['users'];
			$groups  	 = $log['groups'];
			$events 	 = $log['events'];
			$eventsPush  = $log['events_push'];
			$log     	 = mysqli_fetch_assoc($result);
			$visits  	 = round($log['visits']);
			displayStats();
		}
	
	} else {
		// Select the curent number of users, groups, events, and visit percentages
		$select  = "SELECT COUNT(1) FROM users UNION ALL
					SELECT COUNT(1) FROM groups WHERE group_status = 'active' UNION ALL
					SELECT COUNT(1) FROM events WHERE start_date >= CURDATE() UNION ALL
					SELECT events_push FROM logs WHERE date = CURDATE() UNION ALL
					SELECT COUNT(1) FROM user_visits WHERE last_visited = CURDATE()";
		$result  = mysqli_query($dbc,$select);
		$date = date('F j', time());
		for ($i = 0; $i < 5; $i++){
			$stats  = mysqli_fetch_assoc($result);
			switch ($i){
				case "0":
					$users = $stats['COUNT(1)'];
					break;
				case "1":
					$groups = $stats['COUNT(1)'];
					break;
				case "2":
					$events = $stats['COUNT(1)'];
					break;
				case "3":
					$eventsPush = $stats['COUNT(1)'];
					break;
				case "4":
					$visits = round((($stats['COUNT(1)'])/$users) * 100);
					break;
				default:
					break;
			}
		}
		displayStats();
	}
	?>
	</table>
	<?php
	// Display the 15 newest users
	if (empty($timeframe) || $timeframe == 'now'){
		echo'
		<table id="newUser" class="userTable">
			<tr><td class="miscTitles">Newest</td></tr>';
			$select = "SELECT u.first_name, u.last_name, h.href_name
					   FROM users AS u
					   INNER JOIN hrefs AS h
					   ON (u.user_id = h.user_id)
					   ORDER BY u.user_id DESC
					   LIMIT 15";
			$result  = mysqli_query($dbc,$select);
			while ($user = mysqli_fetch_assoc($result)){
				$fullname = htmlentities($user['first_name']. " ".$user['last_name']);
				$href	  = htmlentities($user['href_name']);
				echo '<tr><td><a href="'.$href.'">'.$fullname.'</a></td></tr>';
			}
		echo'
		</table>
		<table class="userTable" style="margin-left:25px">
			<tr><td class="miscTitles">Latest</td></tr>';
			$select = "SELECT u.first_name, u.last_name, h.href_name
					   FROM user_visits AS uv
					   INNER JOIN users AS u
					   ON (uv.user_id = u.user_id)
					   INNER JOIN hrefs AS h
					   ON (uv.user_id = h.user_id)
					   WHERE uv.last_visited = CURDATE()
					   ORDER BY u.user_id DESC
					   LIMIT 15";
			$result  = mysqli_query($dbc,$select);
			while ($user = mysqli_fetch_assoc($result)){
				$fullname  = htmlentities($user['first_name']. " ".$user['last_name']);
				$href	   = htmlentities($user['href_name']);
				echo '<tr><td><a href="'.$href.'">'.$fullname.'</a></td></tr>';
			}
		echo'
		</table>';
		
	} 
	?>
</div>
<?php
// Display the stats on a row by row basis
function displayStats(){
	global $date, $users, $groups, $events, $eventsPush, $visits;
	$eventTitle	 	 = ($events == 1) ? 'event' : 'events';
	$eventpushTitle  = ($eventsPush == 1) ? 'event push' : 'events push';
	echo'
	<tr>
		<td><span class="miscTitles">'.$date.'</span> </td>
		<td><span class="miscTitles">'.$users.'</span> users </td>
		<td><span class="miscTitles">'.$groups.'</span> groups </td>
		<td><span class="miscTitles">'.$events.'</span> '.$eventTitle.' </td>
		<td><span class="miscTitles">'.$eventsPush.'</span> '.$eventpushTitle.' </td>
		<td><span class="miscTitles">%'.$visits.'</span> daily visits</td>
	</tr>';
}
?>