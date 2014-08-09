<?php
require_once('connect.php');
redirect_logged_out_users();
$search = (isset($_POST['s'])) ? mysqli_real_escape_string($dbc,trim($_POST['s'])) : false;
?>
<div id="groupResult">
  <div class="pageHd">Groups</div>
  <div>You searched for <strong><?php echo $search; ?></strong></div>
  <ul class="msgFeedContainer">
  <?php
  if ($search){
	  // Search for groups and limit search to 15
	  $select = "SELECT g.group_id, g.group_name, h.href_name
				 FROM groups AS g
				 INNER JOIN hrefs AS h
				 ON (g.group_id = h.group_id)
				 WHERE g.group_status = 'active' AND g.group_name LIKE '%".$search."%'
				 LIMIT 10";
	  $result = mysqli_query($dbc,$select) or die("Cant select");
	  
	  if (mysqli_num_rows($result) > 0){
		  while ($group = mysqli_fetch_assoc($result)){
			  $groupId     = intval($group['group_id']);
			  $groupName   = htmlentities($group['group_name']);
			  $thumbnail   = "images/groups/gt".$groupId.".jpg";
			  $href 	   = htmlentities($group['href_name']);
			  echo'						
			  <li class="msgFeed">
				  <div class="msgMainContainer">
					  <div class="msgImg"> <a href="'.$href.'"><img src="'.$thumbnail.'" /></a></div>
					  <div class="msgContent"></div>
					  <a href="'.$href.'" class="msgAuthor">'.$groupName.'</a>
				  </div>
			  </li>';
		  }
	  } else{
		  echo '<li class="resultNoti"><div>No matches found, please try again.</div></li>';
	  }
  } else{
	  echo '<li class="resultNoti"><div>No matches found, please try again.</div></li>';
  }
  ?>
  </ul>
<!-- #groupResult -->
</div>