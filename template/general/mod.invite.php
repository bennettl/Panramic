<?php require_once('connect.php'); ?>
<div id="inviteMod" class="mod">
    <div class="modHd">Tell your friends</div>
        <span class="delete"></span>
    	<div class="modContainer">
        <form id="evtInvForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="guestSearchContainer">
            <input class="inputText searchText inactiveText guestText" type="text" value="Find..."/>
            <i class="guestSubmit"></i>
        </div>
        <div class="guestListContainer">
        	<div>
           		<ul class="listContainer">
                <?php
                // Loop through the user friends and make a list
                $select = "SELECT u.user_id, u.first_name, u.last_name
                           FROM user_friends AS uf
                           INNER JOIN users AS u
                           ON (uf.friend_id = u.user_id)
                           WHERE uf.user_id = '$userId' AND uf.friend_status = 'friend'";
                $result = mysqli_query($dbc,$select) or die("cant select");
                
                // Network category is the header
                while ($friend = mysqli_fetch_array($result)){
                	$friendId	= intval($friend['user_id']);
                    $fullName   = htmlentities($friend['first_name']." ".$friend['last_name']);
                    $thumbnail  = "images/users/ut".$friendId.".jpg";
                    echo' 
                    <li class="list">
                        <input type="checkbox" name="friend[]" value="'.$friendId.'" />
                        <a href="#"><img src="'.$thumbnail.'" /><div class="checkbox">'.$fullName.'</div></a>
                    </li> ';
                }	
			    ?>
			</ul>
		</div>
    <!-- .guestListContainer -->
    </div>
        <input type="hidden" name="eventId" />
        <input class="inputSubmit" type="submit" name="inviteSubmit" value="Invite" />
    </form>
    <!-- .modContainer -->
    </div>
<!-- .inviteMod -->
</div>