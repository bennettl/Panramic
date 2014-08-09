<?php
require_once('connect.php');
global $dbc, $search;
if (empty($search)){
	$search = (isset($_POST['s'])) ? mysqli_real_escape_string($dbc,trim($_POST['s'])) : false;
}
?>
<div id="peopleResult">
<div class="pageHd">People</div>
<div>You searched for <strong><?php echo $search; ?></strong></div>
    <ul class="msgFeedContainer">
    <?php
    if ($search){
        // Search for groups and limit search to 15
        $select = "SELECT u.user_id, u.first_name, u.last_name, h.href_name
                   FROM users AS u
                   INNER JOIN hrefs AS h
                   ON (u.user_id = h.user_id)
                   WHERE LOWER(CONCAT_WS(' ',first_name,last_name)) LIKE '%".$search."%'
                   LIMIT 10";
        $result = mysqli_query($dbc,$select) or die("cant select");
        
        if (mysqli_num_rows($result) > 0){
            while ($user = mysqli_fetch_assoc($result)){
				$userId 	 = intval($user['user_id']);
                $fullName 	 = htmlentities($user['first_name']." ".$user['last_name']);
                $thumbnail   = "images/users/ut".$userId.".jpg";
                $href 		 = htmlentities($user['href_name']);
                echo'						
                <li class="msgFeed">
                    <div class="msgMainContainer">
                        <div class="msgImg"> <a href="'.$href.'"><img src="'.$thumbnail.'" /></a></div>
                        <div class="msgContent"></div>
                        <a href="'.$href.'" class="msgAuthor">'.$fullName.'</a>
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
<!-- #peopleResult -->
</div>