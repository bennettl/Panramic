<?php 
require_once('template.php');
initialize(); // Sets up db connection, user session, etc.
redirect_logged_out_users();
?>
<div id="groupReq" class="pageLayout">
    <div class="pageHd">Group Request</div>
	 <form id="groupEditForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
     <table>
		 <tr>
         	 <th class="label" style="vertical-align:top;"><label for="evtGuest" id="evtGuest">Guests:</label></th>
             <td><?php get_form_guestlist(); ?> </td>
        </tr>
		<tr>
		 	<th class="label">Group Name:</th>
			<td id="groupName"></td>
		</tr>
        <tr>
        	<td colspan="2">
			<input type="hidden" name="groupId" />
			<input id ="resetForm" type="reset" style="display: none;" />
			<input class="inputSubmit" style="padding: 3px 5px;" type="submit" name="groupSubmit" value="Submit" /></td>
        </tr>
    </table>
    </form>
    <ul class="mediumList">
    	<?php
		// Select all rows from groups where the status is 'pending'
		$select = "SELECT g.group_id, g.group_name, h.href_name
				   FROM groups AS g
				   INNER JOIN hrefs AS h
				   ON (g.group_id = h.group_id)
				   WHERE g.group_status = 'pending'";
		$result = mysqli_query($dbc,$select) or die("cant select");
		
		while ($group = mysqli_fetch_assoc($result)){
			$groupId    = intval($group['group_id']);
			$name       = htmlentities($group['group_name']);
			$groupHref  = htmlentities($group['href_name']);
			$thumbnail  = "images/groups/gt".$groupId.".jpg";
			
			echo'
			<li value="'.$groupId.'">
				<img src="'.$thumbnail.'" />
				<div class="listName"><a href ="'.$groupHref.'">'.$name.'</a></div>
				<a href="#" class="editBtn">Edit</a>
			</li>';
		}
	?>
    </ul>
    <p id="groupNoti" class="noti pageNoti">There are currently no group requests</p>
    <div id="confirmBox"><button class="confirmBtn">Confirm</button><i class="deleteBox"></i></div>    
<!-- #friendReq -->    
</div>