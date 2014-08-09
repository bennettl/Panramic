<?php
require_once('connect.php');

$type 	      = $_GET['t'];
$groupId      = intval($_GET['g']);
$fallYear     =  substr(mysqli_real_escape_string($dbc,trim($_GET['y'])),0,4);
$springYear   = substr(mysqli_real_escape_string($dbc,trim($_GET['y'])),4,4);
$fallMonth    = array('August'=>'08', 'September'=>'09', 'October'=>'10', 'November'=>'11', 'December'=>'12');
$springMonth  = array('January'=>'01', 'February'=>'02' ,'March'=>'03', 'April'=>'04', 'May'=>'05', 'June'=>'06', 'July'=>'07');

echo $fallYear;
// If user is not administrator of group, then exit
$select     = "SELECT 1 FROM group_members WHERE user_id = '$userId' AND group_id = '$groupId' AND member_status = 'admin'";
$result     = mysqli_query($dbc,$select);
if (mysqli_num_rows($result) == 0){
	exit;
}
// Select events base on group_id, month, and year
$select 	= "SELECT group_name FROM groups WHERE group_id = '$groupId'";
$result		= mysqli_query($dbc,$select);
$group 		= mysqli_fetch_assoc($result);
$groupName  = $group['group_name'];
 
// Depending on the file type requested, generate the appropriate file
if (isset($_GET['download'])){
	if ($type == 'word'){
		createWord();
	} else if ($type == 'excel'){
		createExcel();
	}
}

// This function outputs word header files and html with paragraphcs filled with events
function createWord(){
	global $groupId, $groupName, $fallYear, $springYear, $fallMonth, $springMonth;
	
	$fileName = $fallYear.'_'.$springYear.'_calendar.doc';
	header('Content-type: application/vnd.ms-word');
	header('Content-Disposition: attachment; Filename='.$fileName);
	
	echo'
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
	</head>
	<body>
		<p style="font-size: 20px;"><b><u>'.$groupName.' '.$fallYear.'-'.$springYear.' Calendar</u></b></p>';
		// Loop through every month in fall semester
		foreach($fallMonth as $monthStr => $monthNum){
			wordList($fallYear,$monthStr,$monthNum);
		}
		// Loop through every month in spring semester
		foreach($springMonth as $monthStr => $monthNum){
			wordList($springYear,$monthStr,$monthNum);
		}
		echo'
		</body>
		</html>';
}

// This function outputs a list of events
function wordList($year,$monthStr,$monthNum){
	global $dbc, $groupId;
	// Select events base on group_id, month, and year
	$select = " SELECT e.*
				FROM groups AS g
				INNER JOIN events AS e
				ON (g.group_id = e.group_id AND DATE_FORMAT(e.start_date, '%m') = '$monthNum' AND DATE_FORMAT(e.start_date,'%Y') = '$year')
				WHERE g.group_id = '$groupId'
				ORDER BY e.start_date";
	$result = mysqli_query($dbc,$select);
	
	// If there are events within this month, echo the month
	if (mysqli_num_rows($result) > 0){
		echo '<br /><p style="font-size: 15px;"><b><u>'.$monthStr.'</u></b></p><br/>';
	
		// Display individual event information
		while ($event = mysqli_fetch_assoc($result)){
			$name 			= $event['event_name'];
			$location 		= $event['event_location'];
			$description 	= nl2br(htmlentities($event['event_description']));
			$startDate 		= date('F j',strtotime($event['start_date']));
			$endDate 		= date('F j',strtotime($event['end_date']));
			$endDate		= ($startDate == $endDate) ? ' ' : " - ".$endDate;
			$startTime 		= date('g:i a',strtotime($event['start_time']));
			$endTime 		= date('g:i a',strtotime($event['end_time']));
			$endTime		= ($startTime == $endTime) ? ' ' : " - ".$endTime;
			$time			= $startDate.$endDate." | ".$startTime.$endTime;
			echo'
			<p  style="font-size: 12px;">
				<b>Name: </b>'.$name.'</b><br />
				<b>Time: </b>'.$time.'<br />
				<b>Location: </b>'.$location.'<br />
				<b>Description: </b>'.$description.'
			</p> <br />';
		}
	}
}

// This function outputs excel header files and html layout with tables filled with events
function createExcel(){
	global $groupId, $groupName, $fallYear, $springYear, $fallMonth, $springMonth, $cellLength, $rowLength;
	
	$fileName = $fallYear.'_'.$springYear.'_calendar.xls';	
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename='.$fileName);
	
	$cellLength = 14;
	$rowLength  = $cellLength + 1;
	echo'
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
	</head>
	<body>
		<table>
			<tbody>
				<tr>
					<th colspan="'.$rowLength.'" style="font-size: 20px;"><u>'.$groupName.' '.$fallYear.'-'.$springYear.' Calendar</u></th>
				</tr>
				<tr><td colspan="'.$rowLength.'"></td></tr>
			</tbody>
		</table>';
		
		// Loop through every month in fall semester
		foreach($fallMonth as $monthStr => $monthNum){
			excelList($fallYear,$monthStr,$monthNum);
		}
		// Loop through every month in spring semester
		foreach($springMonth as $monthStr => $monthNum){
			excelList($springYear,$monthStr,$monthNum);
		}
	echo'
	</body>
	</html>';
}

// This function displays table of events
function excelList($year,$monthStr,$monthNum){
	global $dbc, $groupId, $cellLength, $rowLength;
	// Select events base on group_id, month, and year
	$select = " SELECT e.*
				FROM groups AS g
				INNER JOIN events AS e
				ON (g.group_id = e.group_id AND DATE_FORMAT(e.start_date, '%m') = '$monthNum' AND DATE_FORMAT(e.start_date,'%Y') = '$year')
				WHERE g.group_id = '$groupId'
				ORDER BY e.start_date";
	$result = mysqli_query($dbc,$select);

	// If there are events within this month, echo the month
	if (mysqli_num_rows($result) > 0){
		echo '<table style="padding:20px 0;" border="1">
			  <thead>
				  <tr><td colspan="'.$rowLength.'"></td></tr>
				  <tr><th colspan="'.$rowLength.'" style="text-align:left;font-size: 15px;"><u>'.$monthStr.'</u></th></td></tr>
				  <tr><td colspan="'.$rowLength.'"></td></tr>
			  </thead>';
	
		// Display individual event information
		while ($event = mysqli_fetch_assoc($result)){
			$name 			= $event['event_name'];
			$location 		= $event['event_location'];
			$description 	= nl2br(htmlentities($event['event_description']));
			$startDate 		= date('F j',strtotime($event['start_date']));
			$endDate 		= date('F j',strtotime($event['end_date']));
			$endDate		= ($startDate == $endDate) ? ' ' : " - ".$endDate;
			$startTime 		= date('g:i a',strtotime($event['start_time']));
			$endTime 		= date('g:i a',strtotime($event['end_time']));
			$endTime		= ($startTime == $endTime) ? ' ' : " - ".$endTime;
			$time			= $startDate.$endDate." | ".$startTime.$endTime;
			echo'
			<tbody style="margin-bottom: 20px;">
				<tr>
					<td style="text-align:left; font-size: 12px;">Name: </td>
					<td style="text-align:left; font-size: 12px;" colspan="'.$cellLength.'">'.$name.'</td>
				</tr>
				<tr>
					<td style="text-align:left; font-size: 12px;">Time: </td>
					<td style="text-align:left; font-size: 12px;" colspan="'.$cellLength.'">'.$time.'</td>
				</tr>
				<tr>
					<td style="text-align:left; font-size: 12px;">Location: </td>
					<td style="text-align:left; font-size: 12px;" colspan="'.$cellLength.'">'.$location.'</td>
				</tr>
				<tr>
					<td style="text-align:left;vertical-align:top; font-size: 12px;">Description: </td>
					<td style="text-align:left; font-size: 12px;" colspan="'.$cellLength.'">'.$description.' <br /> <br /></td>
				</tr>
			</tbody>';
		}
		echo '</table>';
	}
}
/* Headers that might be userful
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');*/
?>