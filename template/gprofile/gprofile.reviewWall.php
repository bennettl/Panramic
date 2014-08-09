<?php
require_once('connect.php');
if (!isset($_SESSION['user_id'])){
	$userId = 0;
}
$groupId  = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;

// Only do the following if pageNum is not set
if (!isset($_POST['pageNum'])){
	// See if user is a member or admin of group
	$select  = "SELECT member_status FROM group_members WHERE user_id = '$userId' AND group_id ='$groupId'";
	$result  = mysqli_query($dbc,$select);
	$clear   = false;
	$admin   = false;
	if (mysqli_num_rows($result) == 1){
		$member = mysqli_fetch_assoc($result);
		$status = $member['member_status'];
		if ($status == 'admin'){
			$clear = true;
			$admin = true;
		} else if ($status == 'member'){
			$clear = true;
		}
	}
	// Select the number of reviews the group has
	$select   = "SELECT COUNT(1) FROM reviews WHERE group_id ='$groupId'";
	$result   = mysqli_query($dbc,$select);
	$row      = mysqli_fetch_assoc($result);
	$tcount   = intval($row['COUNT(1)']);
	$pcount   = ceil($tcount/3);
			
	echo'
	<div id="reviewWall">';
		// If user is a member or admin of group, then he can post a review
		if ($clear){
			// The current review limit is 9
			if ($tcount < 9){
				echo'
				<div id="reviewInputContainer">
					<form id="reviewForm" action="'.$_SERVER['PHP_SELF'].'" method="post">
						<textarea class="inputText reviewText inactiveText" name="message">Write a review...</textarea>
						<input type="hidden" name="groupId" value="'.$groupId.'" />
						<input class="inputSubmit reviewSubmit" type="submit" name="submit" value="Post" />
				   </form>
				   <div class="reviewNub"></div>
				</div>';
			} else if ($admin){
				echo '<div class="noti pageNoti">In for new reviews to be submitted, please remove old ones</div>';
			}
		}
		// If there are no reviews, notify user
		if ($tcount == 0){
			echo'<div class="noti pageNoti">There are currently no reviews</div>';
		}
		// If page count is greater than 1, display the pageList
		if ($pcount > 1){
			echo'
			<ul id="pageList">';
			for ($i = 1; $i < ($pcount + 1); $i++){
				if ($i == 1){
					echo '<li><a href="#'.$i.'_'.$groupId.'" class="current">'.$i.'</a></li>';
				} else {
					echo '<li><a href="#'.$i.'_'.$groupId.'">'.$i.'</a></li>';
				}
			}
			echo'
			</ul>';
		}
}
	echo'
	<ul class="msgFeedContainer">';
	// Select all the reviews relevant to the group. Connect with users to get author information. Order by pvote, then review_timestamp
	$select = "SELECT r.*, u.first_name, u.last_name, h.href_name
			   FROM reviews AS r
			   INNER JOIN users AS u
			   ON (r.user_id = u.user_id) 
			   INNER JOIN hrefs AS h
			   ON (r.user_id = h.user_id)
			   WHERE r.group_id = '$groupId'
			   ORDER BY pvote DESC, review_timestamp DESC ";
	
	// Depending on which page isset, we will modify the LIMIT accordingly, the default is 0,3
	if (isset($_POST['pageNum'])){
		$start   = (intval($_POST['pageNum']) - 1) * 3;
		$select .= "LIMIT $start,3";
	} else{
		$select .= "LIMIT 0,3";
	}
	
	$result = mysqli_query($dbc,$select) or die("Cant find review");
		
	while ($review = mysqli_fetch_assoc($result)){
		$authorId	= intval($review['user_id']);
		$author 	= htmlentities($review['first_name']." ".$review['last_name']);
		$href		= htmlentities($review['href_name']);
		$thumbnail 	= "images/users/ut".$authorId.".jpg";
		$msgId		= intval($review['review_id']);
		$msg		= htmlentities($review['review']);
		$pvote		= intval($review['pvote']);
		$tvote		= intval($review['tvote']);
		$timestamp  = date('U', strtotime($review['review_timestamp']));
		echo'
		<li class="msgFeed" value="'.$msgId.'">';
			// If user is author of this reply, then he can delete it
			if ($userId == $authorId || $admin){
				echo'<span class="delete"></span>';
			}
		echo'
		<div class="msgMainContainer">
			<div class="msgImg"> <a href="'.$href.'"><img src="'.$thumbnail.'" /></a></div>
			<div class="msgContent">
				<a href="'.$href.'" class="msgAuthor">'.$author.'</a>
				<div class="msgTime '.$timestamp.'"></div>
				<div class="msgBody">'.$msg.'</div> 
			 <!-- .msgContent -->
			 </div>

		  <div class="reviewOptions">
				<div class="reviewCount"><span class="pVote">'.$pvote.'</span> out of <span class="tVote">'.$tvote.'</span> people found this review helpful</div>';
				// Check to see if the user/visitor has already voted on this review, if the user hasn"t then we will display .reviewVote
				$select  = "SELECT user_vote_id FROM user_votes WHERE (user_id ='$userId' AND review_id = '$msgId')";
				$result1 = mysqli_query($dbc,$select);
		
				if (mysqli_num_rows($result1) == 0){
					echo'
					<div class="reviewVote">
						<span>Was This Review Helpful?</span>
						<a class="voteBtn voteYes" href="#">Yes</a>
						<span> | </span>
						<a class="voteBtn voteNo" href="#">No</a>
					</div>';
				}
		 echo'
		 </div>
		 <a href="#" class="moreInfo">more...</a>
		 <!-- .msgMainContainer -->
		 </div>
		 <ul class="msgReplyContainer">';

		// Between each review, find all review_replies associated with it. Connect with users to get author information. Order by review_reply_timestamp
		$select = "SELECT rr.review_reply_id, rr.user_id, rr.review_reply, rr.review_reply_timestamp, u.first_name, u.last_name, h.href_name
				   FROM review_replies AS rr
				   INNER JOIN users AS u
				   ON (rr.user_id = u.user_id)
				   INNER JOIN hrefs AS h
				   ON (rr.user_id = h.user_id)
				   WHERE rr.review_id = '$msgId'
				   ORDER BY rr.review_reply_timestamp";
		$result1 = mysqli_query($dbc,$select) or die("Cant find reply");
		
		while ($reply = mysqli_fetch_assoc($result1)){
			$authorId	= intval($reply['user_id']);
			$author 	= htmlentities($reply['first_name']." ".$reply['last_name']);
			$href		= htmlentities($reply['href_name']);
			$thumbnail 	= "images/users/ut".$authorId.".jpg";
			$replyId	= intval($reply['review_reply_id']);
			$replyMsg	= htmlentities($reply['review_reply']);
			$timestamp  = date('U', strtotime($reply['review_reply_timestamp']));
			
			echo'
			<li class="msgReply" value="'.$replyId.'">';
			// If user is author of this reply, then he can delete it
			if ($userId == $authorId){
				echo'<span class="delete"></span>';
			}
			echo'
				<div class="msgImg"><a href="'.$href.'"><img src="'.$thumbnail.'"/></a></div>
				<div class="msgContent"><a href="'.$href.'" class="msgAuthor" style="float:none;">'.$author.'</a>
				<span class="msgBody">'.$replyMsg.'</span> 
				<div class="msgTime '.$timestamp.'"></div>
				</div>
			</li>';
		}
		  echo'
		  </ul>
		  <div class="commentContainer">
			<form class="commentForm" action="'.$_SERVER["PHP_SELF"].'" method="post">
				  <textarea class="inputText commentText" name="reply"></textarea>
				  <input type="hidden" name="msgId" value="'.$msgId.'" />
				  <input class="inputSubmit commentSubmit" type="submit" name="submit" value="Reply" />
			 </form>
		  <!-- .commentContainer -->   
		  </div>    
		<!-- msgFeed -->
		</li>';
	}
	echo'
	<!-- msgFeedContainer -->
	</ul>';
	// Only do the folowing if pagenum is not set
	if (!isset($_POST['pageNum'])){
			// This gives a quick snapshot of user info for javascript to access
			$select 	= "SELECT u.first_name, u.last_name, h.href_name
						   FROM users AS u
						   INNER JOIN hrefs AS h
						   ON (u.user_id = h.user_id)
						   WHERE u.user_id ='$userId'";
			$result 	= mysqli_query($dbc,$select);
			$user   	= mysqli_fetch_assoc($result);
			$fullName	= htmlentities($user['first_name']." ".$user['last_name']);
			$href 	    = htmlentities($user['href_name']);
			
			echo'
			<form id="quickInfo" action="'.$_SERVER['PHP_SELF'].'" method="post">
				<input type="hidden" name="fullName" value="'.$fullName.'" />
				<input type="hidden" name="username" value="'.$href.'" />
				<input type="hidden" name="ui" value="'.$userId.'" />
			</form>
		<!-- #reviewWall -->
		</div>';
	}
?>