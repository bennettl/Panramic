<?php
require_once('connect.php');
redirect_logged_out_users();

// When newMsgForm isset, then insert the message, then set two references to the message
if (isset($_POST['newMsgForm'])){
	$msgTo		 = intval($_POST['msgToVal']);
	$content     = mysqli_real_escape_string($dbc,trim($_POST['msgContent']));
	$newContent  = preg_replace('/\s+/',"",$_POST['msgContent']);
	$subject 	 = mysqli_real_escape_string($dbc,trim($_POST['msgSubject']));
	$errorMsg    = "";
	
	if ($msgTo == 0){
		$errorMsg = "Invalid recipient";
	}
	if (empty($newContent)){
		$errorMsg = "Please write a message";
	}
	
	// Check to see if recipient has reach the inbox limit
	$select  = "SELECT COUNT(1) FROM message_statuses WHERE user_id ='$msgTo' AND message_status='normal'";
	$result  = mysqli_query($dbc,$select);
	$row     = mysqli_fetch_assoc($result);
	$count   = intval($row['COUNT(1)']);
	if ($count > 39){
		$errorMsg = "Recipient's inbox is currently full";
	}
	
	if (empty($errorMsg)) { 
		$insert  = "INSERT INTO messages (author_id, message_subject, message,last_entry) VALUES ('$current_user->id','$subject','$content', NOW())";
		mysqli_query($dbc, $insert);
		$msgId   = mysqli_insert_id($dbc);
		$insert  = "INSERT INTO message_statuses (user_id, message_id, message_status, message_count) VALUES ('$current_user->id', '$msgId', 'hidden', '0')";
		mysqli_query($dbc,$insert);
		$insert  = "INSERT INTO message_statuses (user_id, message_id, message_status, message_count) VALUES ('$msgTo', '$msgId', 'normal','1')";
	 	mysqli_query($dbc,$insert);
	}
}

// If removeMsg isset, then update the message_status to hidden. If both rows are hidden, delete everything related to the message
if (isset($_POST['removeMsg'])){
	$msgId  = intval($_POST['msgId']);
	print_r($_POST);
	if (!empty($msgId)){
		$update = "UPDATE message_statuses SET message_status = 'hidden' WHERE (user_id ='$current_user->id' AND message_id ='$msgId')";
		mysqli_query($dbc,$update);
		$select = "SELECT 1 FROM message_statuses WHERE message_id ='$msgId' AND message_status = 'hidden'";
		$result = mysqli_query($dbc,$select);
		if (mysqli_num_rows($result) == 2){
			$delete = "DELETE m,mr,ms,r
					   FROM messages AS m
					   LEFT JOIN message_replies AS mr
					   ON (m.message_id = mr.message_id)
					   INNER JOIN message_statuses AS ms
					   ON (m.message_id = ms.message_id)
					   LEFT JOIN reports AS r
					   ON (m.message_id = r.message_id)
					   WHERE m.message_id = '$msgId'";
			mysqli_query($dbc,$delete);
		}
	}
}

// If reportMsg isset, then update the message_status to hidden. If both rows are hidden, delete everything related to the message. Also insert a row in reports if there isn't one
if (isset($_POST['reportMsg'])){
	$msgId   = intval($_POST['msgId']);
	$comment = mysqli_real_escape_string($dbc,trim($_POST['comment']));
	if (!empty($msgId)){
		$update = "UPDATE message_statuses SET message_status = 'hidden' WHERE (user_id ='$current_user->id' AND message_id ='$msgId')";
		mysqli_query($dbc,$update);
		$select  = "SELECT 1 FROM reports WHERE message_id ='$msgId'";
		$result  = mysqli_query($dbc,$select);
		if (mysqli_num_rows($result) == 0){
			$insert = "INSERT INTO reports (user_id, event_id, message_id, review_id, report_comment) VALUES ('$current_user->id','0','$msgId', '0', '$comment')";
			mysqli_query($dbc,$insert);
		}
	}
}

// If removeMsgReply isset, then delete the reply in user_replies 
if (isset($_POST['removeMsgReply'])){
	$msgReplyId  = intval($_POST['msgReplyId']);
	if (!empty($msgReplyId)){
		$delete 	 = "DELETE FROM message_replies WHERE (message_reply_id ='$msgReplyId' AND author_id ='$current_user->id')";
		mysqli_query($dbc,$delete);
	}
}

// If inboxReply isset, then insert in a new row in message_replies, update last_entry timestamp and notify the other user
if (isset($_POST['inboxReply'])){
	$msgId	= intval($_POST['msgId']);
	$reply	= mysqli_real_escape_string($dbc,trim($_POST['reply']));
	
	if (!empty($msgId)){
		$insert = "INSERT INTO message_replies (message_id, author_id, message_reply) VALUES ('$msgId','$current_user->id','$reply')";
		mysqli_query($dbc,$insert);
		
		// Update the latest_entry for message and the message count
		$update = "UPDATE messages AS m
				   INNER JOIN message_statuses AS ms
				   ON (m.message_id = ms.message_id)
				   SET m.last_entry = NOW(), ms.message_count = ms.message_count + 1, ms.message_status = 'normal'
				   WHERE m.message_id = '$msgId' AND ms.user_id != '$current_user->id'";
		mysqli_query($dbc,$update);
	}
}
?>