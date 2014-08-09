<?php
require_once('connect.php');
redirect_logged_out_users();

// If review isset, insert a row in reviews
if (isset($_POST['review'])){
	$groupId = intval($_POST['groupId']);
	$msg	 = mysqli_real_escape_string($dbc,trim($_POST['message']));
	if ($groupId != 0 && !empty($msg)){
		$insert  = "INSERT INTO reviews (group_id,user_id,review,pvote,tvote) VALUES ('$groupId','$user_id', '$msg', '0', '0')";
		mysqli_query($dbc,$insert);
	}
}

// If reviewReply isset, then insert a row in review_replies
if (isset($_POST['reviewReply'])){
	$msgId	= intval($_POST['msgId']);
	$reply	= mysqli_real_escape_string($dbc,trim($_POST['reply']));
	if ($msgId != 0 && !empty($reply)){
		$insert = "INSERT INTO review_replies (review_id, user_id, review_reply) VALUES ('$msgId','$user_id','$reply')";
		mysqli_query($dbc,$insert);
	}
}

// If pVote, then insert a row in user_votes and increment both the positive and total vote.
if (isset($_POST['pVote'])){
	$reviewId  = intval($_POST['reviewId']);	
	$insert    = "INSERT INTO user_votes (user_id, review_id) VALUES ('$user_id', '$reviewId')";
	mysqli_query($dbc,$insert);
	$update    = "UPDATE reviews SET pvote = pvote + 1, tvote = tvote + 1 WHERE review_id = '$reviewId'";
	mysqli_query($dbc,$update);
}
// If tVote isset, then insert a row in user_votes and increment the total vote.
if (isset($_POST['tVote'])){
	$reviewId = intval($_POST['reviewId']);
	$insert   = "INSERT INTO user_votes (user_id, review_id) VALUES ('$user_id', '$reviewId')";
	mysqli_query($dbc, $insert);
	$update   = "UPDATE reviews SET tvote = tvote +1 WHERE review_id = '$reviewId'";
	mysqli_query($dbc,$update);
}

// If removeReview isset, then remove the review
if (isset($_POST['removeReview'])){
	$reviewId = intval($_POST['reviewId']);
	$delete   = "DELETE r,rr,uv, re
			     FROM reviews AS r
			     LEFT JOIN review_replies AS rr
			     ON (r.review_id = rr.review_id)
			     LEFT JOIN user_votes AS uv
			     ON (r.review_id = uv.review_id)
			     LEFT JOIN reports AS re
			     ON (r.review_id = re.review_id)
			     WHERE r.review_id = '$reviewId'";
	mysqli_query($dbc,$delete);
}

// If reportReview isset, insert a row in reports if there isn't one
if (isset($_POST['reportReview'])){
	$reviewId  = intval($_POST['reviewId']);
	$select    = "SELECT report_id FROM reports WHERE review_id ='$reviewId'";
	$result    = mysqli_query($dbc,$select);
	if (mysqli_num_rows($result) == 0){
		$insert = "INSERT INTO reports (user_id, event_id, message_id, review_id) VALUES ('$user_id','0','0','$reviewId')";
		mysqli_query($dbc,$insert);
	}
}

// If removeReviewReply isset, remove the review reply
if (isset($_POST['removeReviewReply'])){
	$replyId = intval($_POST['replyId']);
	$delete  = "DELETE FROM review_replies WHERE review_reply_id = '$replyId'";
	mysqli_query($dbc,$delete);
}
?>