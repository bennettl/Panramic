<?php
require_once(CLASS_DIR.'event.php');
require_once(CLASS_DIR.'field.php');
require_once(CLASS_DIR.'eventformatter.php');
require_once(CLASS_DIR.'fb_api.php');
global $dbc, $current_user;

foreach ($events as $event):
	// Set the url differently depending if it is a iframe or facebook
	$urlParam     = (isset($_GET['if'])) ? '" target="_blank"' : '' ;
	$urlParam     = ($facebook) ? '?fb=true"' : '';
	$groupUrl     = '<a href="'.MAIN_URL.$event->group_href.$urlParam.'"">'.$event->group.'</a>' ;
	// info and location	
	$description  = EventFormatter::description($event);
	$venue        = $event->venue;
	$street       = trim($event->street);
	$locality     = trim($event->locality);
	$venue        = (!empty($street) || !empty($locality)) ? nl2br($venue).'<br />' : nl2br($venue);
	$street       = (!empty($street) && $street != 'N/A') ? $street.', ' : '';
	// time
	$rsvp         = ($event->rsvp_date != '0000-00-00' && (strtotime($event->rsvp_date)) >= strtotime(date('Y-m-d',time()))) ? true : false;
	$rsvpTime     = ($rsvp && ($event->rsvp_time != '-00:00:01')) ? '<span class="dateDivider">|</span>'.EventFormatter::time($event->rsvp_time) : '';
	$rsvpDate     = ($rsvp) ? 'RSVP by '.EventFormatter::date($event->rsvp_date) : '';
	$startTime    = '<span class="dateDivider">|</span>'.EventFormatter::time($event->start_time);
	$startDate    = EventFormatter::date($event->start_date);
	$endDate      = ($event->start_date == $event->end_date) ?  '' : ' - '.EventFormatter::date($event->end_date);
	$endTime      = ($event->end_time == '-00:00:01') ? '' : ' - '.EventFormatter::time($event->end_time);
	$endTime      = ($event->start_time == $event->end_time) ? '' : $endTime;
	$feedDate     = ($rsvp) ? "<a href='#'>".$rsvpDate.$rsvpTime."</a><a href='#' class='hidden'>".$startDate.$endDate.$startTime.$endTime."</a>" 
	: $startDate.$endDate.$startTime.$endTime;
	$eventTense   = (strtotime(date('Y-m-d',time())) <= strtotime($event->start_date)) ? 'future' : 'past';		
	// Side option
	$statusOption = (!empty($current_user->id) && $event->userStatus($current_user) == 'attend') ? 'Attending' : 'Attend'; // Determines if user can attend an event
	$likeText     = ($event->like == 1) ? "Like" : "Likes"; 	// Depending on the like count, modify the like text
	$attendText   = ($eventTense == 'past') ? 'Attended' : 'Attending';
	// Class
	$eventClass   = "feed field".$event->field_id.' network'.$event->network_id.' group'.$event->group_id;
	$eventClass   .= (isset($event->fb_id)) ? ' fbEvent' : '';

	// If the type is mediumList, then display it
	if ($type == 'mediumList'){
		echo '<li value="'.$event->id.'"><img src="'.$event->thumbnail.'" /><div class="listName">'.$event->name.'</div></li>';
	}

	// If there is no facebook id, go with regualr events attendance. If there is, grab event attendance from facebook page. Set the appropriate flag for $fbAttend
	if (!isset($event->fb_id)){
		$FB 		   = FB_API::initialize();
		// Select uid, name from users in event members
		$selectUser    = 'SELECT uid, name FROM user WHERE uid IN( ';
		$selectEvent   = "SELECT uid FROM event_member WHERE eid = '".$event->fb_id."' AND rsvp_status = 'attending')"; 
		$fqlQuery      = $selectUser.$selectEvent;
		$fb_attendees  = $FB->api(array('method' => 'fql.query','query' =>$fqlQuery));
		$event->attend = count($fb_attendees);
	}

	// If this is an eventInv, then we find out the friends information
	// if (isset($eventInv)){
	// 	$friendName = htmlentities($event['first_name']." ".$event['last_name']);
	// 	$friendHref = htmlentities($event['friend_href']);
	// }
	?>
	
	<li value="<?php echo $event->id; ?>" class="<?php echo $eventClass; ?>" unselectable="on">
		<a href="<?php echo $event->href_name;?>" target="_blank"><img class="feedImg" src="<?php echo $event->thumbnail; ?>" unselectable="on" /></a>
		<a class="feedName" href="<?php echo $event->href_name;?>"><?php echo $event->name; ?></a>
		<table class="feedInfo">
			<tr>
				<th colspan="2" class="feedDate" unselectable="on"><?php echo $feedDate; ?></th>
			</tr>
			<tr>
				<th unselectable="on">Field:</th>
				<td unselectable="on"><?php echo Field::get_name($event->field_id); ?></td>
				</tr>
			<tr>
				<th>Where:</th>
				<td class="feedLocation" unselectable="on"><?php echo $venue.$street.$event->locality; ?></td>
			</tr>
			<?php $x = $event->group_id; ?>
			<?php if (!isset($event->group_id)):  //Only display the host if its not empty  ?>
			<tr>
				<th unselectable="on">Hosted By:</th>
				<td class="groupName" value="<?php echo $event->group_id; ?>" unselectable="on"><?php echo $groupUrl; ?></td>
			</tr>
			<?php endif; ?>
			<?php if (!isset($event->description)): // Only display the description if its not empty  ?>
			<tr> 
				<th unselectable="on">Description:</th>
				<td unselectable="on"> <div class="feedDescription"><?php echo $description; ?></div></td>
			</tr>
			<?php endif; ?>
		<!--feedInfo -->
		</table>
		<div class="feedSide">
			<div class="feedOptions" <?php echo ($eventTense == 'future' && $type != 'page') ? 'style="border-right: 1px solid #E0E0E0;"' : '';// place border if there are feed side options or its not event page?>>
			<?php if ($eventTense == 'future'): // Display the sideoptions if the event is not over ?>
				<div class="invite sideOptions" title="Invite"></div>
				<?php
				// Display if user isnt alreadyattending event and for dislike, if it isnt in iframe
				echo ($statusOption != "Attending" && !isset($_GET['if'])) ? '<div class="dislike sideOptions" title="Dislike"></div>' : ''; 
				echo ($statusOption != "Attending") ? '<div class="like sideOptions" title="'.$statusOption.'"></div>' : '';
				?>
			<?php endif; ?>
			</div>

			<?php if ($type != 'page'): // Display the share options if it isnt an event page ?>
			<div class="addthis_toolbox addthis_default_style">
			    <a class="addthis_button_facebook_like" addthis:title="<?php echo $event->name; ?>" addthis:url="<?php echo $event->href_name; ?>" style="padding: 0;"></a>
			 	<a class="addthis_button_tweet" tw:count="horizontal" addthis:title="<?php echo $event->name; ?>" addthis:url="<?php echo $event->href_name; ?>" style="padding: 0;"></a>
			<!-- AddThis Button END -->
			</div>
			<?php endif; ?>

			<?php
			/*
			<table class="feedCount"> 
				<tr> 
					<td class="feedLike"><?php echo $event->like; ?></td>
					<th class="feedLikeText"><?php echo $likeText; ?></th>
				</tr>
				<tr class="feedUserInfo">
					<td>
					<?php
					//  If its connected to a facebook event, display the facebook users. Display 10 random people that are attending this event.
					if (!isset($event->fb_id)){
						//shuffle($FBeventAttend['data']);
						$count = ($event->attend > 10) ? 10 : $event->attend;
						for ($i = 0; $i < $count; $i++){							
							$fullName	= $FBeventAttend[$i]['name'];
							$userFbId	= $FBeventAttend[$i]['uid'];
							$thumbnail	= 'http://graph.facebook.com/'.$userFbId.'/picture?type=square';
							$href		= 'http://www.facebook.com/'.$userFbId;
							echo '<li><a href="'.$href.'" target="_blank"><img src="'.$thumbnail.'" alt="'.$fullName.'" /></a></li>';
						}
					} else {
						$attendees = $event->getAttendees();
						foreach($attendees as $user){
							$fullName	= htmlentities($user->first_name.' '.$user->last_name);
							$thumbnail	= 'images/users/ut'.intval($user->id).'.jpg';
							$href		=  MAIN_URL.htmlentities($user->href_name).$urlParam;
							echo '<li><a href="'.$href.'"><img src="'.$thumbnail.'" alt="'.$fullName.'" /></a></li>';
						}
					}
					?>
					</td>
				</tr>
				<tr class="feedUserAttend"> 
					<td class="feedAttend"><?php echo $event->attend; ?></td>
					<th><?php echo $attendText; ?></th>
				</tr>
				<?php if (isset($_SESSION['user_id'])): // Only search for friends if session user_id isset ?>
					<tr class="feedFriendInfo">
						<td>
						<?php
						// If there is no facebook id, then display original friends, else display facebook friends
						if (isset($event->fb_id)){
							$friendsAttend  = '0';
						} else{
						/*
							// Select uid, name from users in event members that matches with user's friends in user's friend list
							$selectUser 	   = 'SELECT uid, name FROM user WHERE uid IN( ';
							$selectEvent	   = "SELECT uid FROM event_member WHERE eid = '".$event->fb_id."' AND rsvp_status = 'attending' AND uid IN( "; 
							$selectFriend	   = 'SELECT uid FROM friendlist_member WHERE flid IN( '; 
							$selectFriendList  = 'SELECT flid FROM friendlist WHERE owner=me())))';
							$fqlQuery 		   = $selectUser.$selectEvent.$selectFriend.$selectFriendList;
							try{
								$fbFriendArray	   = $FB->api(array('method' => 'fql.query','query' =>$fqlQuery));
								$friendsAttend	   = count($fbFriendArray);
								$count 			   = ($friendsAttend > 10) ? 10 : $friendsAttend;
							
								for ($i = 0; $i < $count; $i++){			
									$fullName	= $fbFriendArray[$i]['name'];
									$userFbId	= $fbFriendArray[$i]['uid'];
									$thumbnail	= 'http://graph.facebook.com/'.$userFbId.'/picture?type=square';
									$href		= 'http://www.facebook.com/'.$userFbId;
									echo '<li><a href="'.$href.'" target="_blank"><img src="'.$thumbnail.'" alt="'.$fullName.'" /></a></li>';
								}
							} catch(FacebookApiException $e){
								// Query will fail if there is no access friendlist permission
								$friendsAttend  = '0';
							}
							$friendsAttend = 0; 
						}
					$friendText = ($friendsAttend == 1) ? "Friend ".$attendText : "Friends ".$attendText;
						?>
						</td>
					</tr>
					<tr class="feedFriendAttend">
						<td><?php echo $friendsAttend; ?></td>
						<th><?php echo $friendText; ?></th>
					</tr>
				<?php endif; ?>
			</table> */
			?>
		<!-- .feedSide -->
		</div>
		<?php
		// If type is a feed, then the user can delete it 
		if ($type == 'feed'){ echo '<span class="delete"></span>'; }
		// // If this is an event invite, then we display the friends information
		// if (isset($eventInv)){
		// 	echo'<span class="friendName" style="display:none;">'.$friendName.'</span>
		// 		 <span class="friendHref" style="display:none;">'.$friendHref.'</span>';
		// }
		?>
	<!-- .feed -->   
	</li>
<?php endforeach; ?>