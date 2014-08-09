<?php
require_once('connect.php');
require_once(CLASS_DIR.'group.php');
require_once(CLASS_DIR.'user.php');

$groupId = (isset($_POST['groupId'])) ? intval($_POST['groupId']) : false;
$group = new Group($groupId);
$admins = $group->getMembers(array('member_status'=> array('admin')));
?>
<div id="members"> 
    <div class="title">Admins</div>
    <div class="titleDivider"></div>
        <p> Admins can manage group settings, confirm members, edit member statuses, and post events</p>
        <ul class="smallList">
        <?php
        // Display all admins
        foreach ($admins as $admin){
            $user      = $admin['user'];
            $fullName  = $user->first_name.' '.$user->last_name;
            $href      = htmlentities($user->href_name);
            echo '<li><a href="'.$href.'"><img src="'.$user->thumbnail.'" alt="'.$fullName.'" /></a></li>';
        }
		?>
        </ul>
    <div class="title">Manage</div>
    <div class="titleDivider"></div>
        <p id="memberNoti" class="noti"><p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="guestSearchContainer" style="float: left;">
            <input class="inputText searchText inactiveText memberText" style="width:150px;padding:0 5px;"type="text" value="Find..."/>
            <i class="guestSubmit"></i>
        </div> 
        </form>
        <form id="updateMember">  
          <div class="guestListContainer">
                <ul class="listContainer">
                <?php  
                // Display al members
                $members = $group->getMembers(array('member_status'=> array('admin','member')));
                foreach ($members as $member){
                    $memberId  = $member['member_id'];
                    $user      = $member['user'];
                    $fullName  = $user->first_name.' '.$user->last_name;
                    $href      = htmlentities($user->href_name);
                    echo' 
                        <li class="list">
                        <input type="checkbox" name="member[]" value="'.$memberId.'" />
                        <a href="#"><img src="'.$user->thumbnail.'" /><div class="checkbox">'.$fullName.'</div></a>
                        </li> ';
                }
			  ?>
              <!-- .listContainer -->
              </ul>
          <!-- .guestListContainer -->
          </div>
            <input class="inputSubmit" type="submit" name="removeMem" value="Remove" />
            <input class="inputSubmit" type="submit" name="makeMem" value="Make Member" />
            <input class="inputSubmit" type="submit" name="makeAd" value="Make Admin"
        </form>
<!-- #members -->
</div>