<?php
require_once('connect.php');
$groupId = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
?>
<div id="userWall">
	<div id="fuserContainer" class="searchContainer">
		<form action="'<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input id="fuserText" class="inputText searchText inactiveText" type="text" name="fuser" value="Find..."/>
			<i id="fuserSubmit" class="searchSubmit"></i>
		</form>
	 </div>
	 <ul class="mediumList">
	<?php
	// Find all the group's members and display them
	$select = "SELECT u.user_id, u.first_name, u.last_name, h.href_name
			   FROM group_members AS gm
			   INNER JOIN users AS u
			   ON (gm.user_id = u.user_id)
			   INNER JOIN hrefs AS h
			   ON (gm.user_id = h.user_id)
			   WHERE gm.group_id = '$groupId' AND (gm.member_status ='member' OR gm.member_status ='admin')";
	$result = mysqli_query($dbc,$select);
	
	while ($member = mysqli_fetch_assoc($result)){
		$memberId   = intval($member['user_id']);
		$fullName 	= htmlentities($member['first_name']." ".$member['last_name']);
		$href		= htmlentities($member['href_name']);
		$thumbnail	= "images/users/ut".$memberId.".jpg";
		echo '<li><a href="'.$href.'"><img src="'.$thumbnail.'" alt="'.$fullName.'" /></a><a href="'.$href.'" class="listName">'.$fullName.'</a></li>';
	}
	?>
	</ul>
<!-- #userWall -->
</div>