<?php 
require_once('connect.php');
header("Content-type: text/css"); 
$groupId = intval($_GET['g']);

// If group id is not empty, select the style from group_calendar
if (!empty($groupId)){
	$select  = "SELECT * FROM group_calendar WHERE group_id = '$groupId'";
	$result  = mysqli_query($dbc,$select);
	$row     = mysqli_fetch_assoc($result);
	$style   = $row['style'];
} else {
	$style   = "light";
}

if ($style == "light"){
	$bgColor 	  = '#FFFFFF';
	$hdBg 		  = '#D3DFE8';
	$hdText 	  = '#727272';
	$borderColor  = '#DEDEDE';
	$labelColor   = '#616161';
	$textColor 	  = '#333333';
} else if ($style == "dark"){
	$bgColor	  = '#333333';
	$hdBg 		  = '#727272';
	$hdText		  = '#F4F3F3';
	$borderColor  = '#DEDEDE';
	$labelColor   = '#EEEDED';
	$textColor    = '#EEEDED';
} else{
	$bgColor 	  = 'transparent';
	$hdBg 		  = '#'.$row['header_bg'];
	$hdText 	  = '#'.$row['header_text'];
	$borderColor  = '#'.$row['border'];
	$labelColor   = '#'.$row['label'];
	$textColor 	  = '#'.$row['text'];
}

echo
'@charset "UTF-8";
/* CSS Document */

body{
	background:'.$bgColor.';
}
.tableHd{
	background:'.$hdBg.';
	color:'.$hdText.';
}
.feed, .feedImg, .mediumList img, .miniList i{
	border-color: '.$bgColor.';
}
.feed, .feedSideDivider, .pageHd{
	border-color:'.$borderColor.';
}
#evtWall > table{
	margin: 0;
}
#miniLogo{
	margin-bottom: 20px;
	float: right;
}
.pageHd{
	clear: right;
}
#pushContainer .current, .feedInfo th, .mediumList .listName, .pageHd, #tabTop > li > a, .noti, .label, #previewBtn, .cancelBtn, .guestTab > li > a:hover, .guestTab > li > a.current, .guestHd, #groupPhotoForm th{
	color: '.$labelColor.';
}
td, label, span, #contentContainer p, #contentContainer a, .feedName, .feedInfo td, .feed a, li, .feedCount, .guestTab > li > a, .listContainer .list .checkbox, #eventNote, #events #evtTimeline a:hover, #events #evtTimeline a.current, .title, .colorText{
	color:'.$textColor.';
}
.guestSearchContainer{
	background: #FFFFFF;
}
.ui-datepicker-month, .ui-datepicker-year{
	color: #FFFFFF;
}
.feedDescription a{
	color: #6E99B4;
}
#leftContainer{
	padding: 0;
}';

?>