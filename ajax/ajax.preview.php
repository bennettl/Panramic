<?php
require_once('connect.php');
redirect_logged_out_users();
require_once(CLASS_DIR.'event.php');

// When user clicks 'previewSubmit' upload and resize img to preview folder, then display the preview
if (isset($_FILES['evtImg'])){
	$groupId         = intval($_POST['groupId']);
	$event           = new Event();
	$event->size     = $_FILES['evtImg']['size'];
	$event->img_type = $_FILES['evtImg']['type'];
	$event->tmp_path = $_FILES['evtImg']['tmp_name'];
	$previewpath     = IMG_DIR.'events/preview/p'.$groupId.time().'.jpg';
	$args            = array('type' => 'preview', 'previewPath' => $previewpath);

	if ($event->size > 0 && $event->validImage()){
		$event->handleImage($args);
		$message = $previewpath;
	} else{
		$message = $errorMsg;
	}
	echo'<div id="previewMsg">'.$message.'</div>';
}
?>