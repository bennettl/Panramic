<?php
require_once('connect.php');
redirect_not_staff();
require_once(LIBRARY_DIR.'facebook.php');
?>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
                  profile: http://ogp.me/ns/profile#">
     <meta property="fb:app_id"               content="258940897480780"> 
     <meta property="og:type"                 content="profile"> 
	<meta property="og:url"         content="http://www.panramic.com/api/fbtest.php?">    
     <meta property="og:image"                content="http://www.panramic.com/images/events/e1.jpg">
     <meta property="og:title"                content="Name of User">
     <meta property="og:description"          content="Description of content">
     <meta property="profile:first_name"      content="First Name">
     <meta property="profile:last_name"       content="Last Name">
     <meta property="profile:gender"          content="male">
    </head>

<!--
<script type="text/javascript" src="../js/min/jquery-1.4.4.min.js"></script>
<script src="http://connect.facebook.net/en_US/all.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
   	var eventId  = '1';
	//pubAction(eventId);
	//fbInvite();
						  
	// This function publishes an action to user's feed
	function pubAction(eventId){
		var clientId 	 = '258940897480780';
		var redirectUrl  = "http://www.panramic.com/fb/fbtest.php";
	
		// Initialize FB object
		FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
	
		// Determine if user is logged in to facebook and level of permission
		FB.getLoginStatus(function(response) {
	  		if (response.status === 'connected'){
				// User is logged in and connected to fb app
				var accessToken = response.authResponse.accessToken;
				
				// Check for specific permissions
				FB.api('me/permissions?access_token=' + accessToken,function(response){
					var publish  = response.data[0].publish_actions;
					var offline  = response.data[0].offline_access;
					// If both permissions exist, then post to user feed
					if (publish && offline){
						var url 	 = '/me/panramic:find?event=http://www.panramic.com/fb/fbaction.php?eid=' + eventId;
						FB.api(url,'post',function(response){
							if (!response || response.error){
								alert('Error occured');
							} else{
								alert('Post was successful! Action ID: ' + response.id);
							}
						});
					}
				});
			} else if (response.status === 'not_authorized'){
				// User is logged in to Facebook, but not connected to the app
				var scope 		 = 'publish_actions,offline_access';
				var fblink		 = 'https://www.facebook.com/dialog/oauth?client_id=' + clientId + '&redirect_uri='+ redirectUrl +'&scope='+ scope;
				// This prompts users for authorization
				// window.open(fblink,'_top');
			} else {
				// User isn't even logged in to Facebook.
			}
		});	
    }
	
});
</script> -->
</head>
<body>
<div id="fb-root"></div>
<?php 
/*
require_once('facebook.php');
	define("CLIENT_ID", "258940897480780");
	define("SECRET", "421f3dcea30125dacd98cfaf0c4d3ab4");
	// Initialize facebook object
	$FB = new Facebook(array(
		'appId' => CLIENT_ID,
		'secret' => SECRET,
		'cookie' => true
	));
$what=	$FB ->getAccessToken();
	$params = array(
  scope => 'offline_access',
  redirect_uri => 'https://www.panramic.com/fb/fbtest.php'
);
try {
  $what = $FB->api('/me/permissions');
} catch(FacebookApiException $e) {
  $result = $e->getResult();
  if ($result.error){
	 $url = $FB -> getLoginUrl($params);
	 echo '<a href="'.$url.'">Here</a>';
	 } else{
		echo "nor errors";
		}
}

print_r($what); */
?>
</body>
</html>

<?php

// define("CLIENT_ID", "258940897480780");
// 	define("SECRET", "421f3dcea30125dacd98cfaf0c4d3ab4");
// 	define("REDIRECT_URI", "http://apps.facebook.com/panramic/");
// 	$code = $_GET['code'];

// 	$facebook = new Facebook(array(
// 		'appId' => CLIENT_ID,
// 		'secret' => SECRET,
// 		'cookie' => true
// 	));
	
// 	$params = array(
// 		'scope' => 'publish_actions',
// 		'redirect_uri' => REDIRECT_URI
// 	);
// 	$loginUrl = $facebook -> getLoginUrl($params);

// 	//$permissions = $facebook -> api('/me/permissions');
// 	$key = 'publish_stream';
// 	if (empty($code)){
// 	//	echo '<a href="'.$loginUrl.'" target="_top">Login url</a> ';
// 		$accessToken = $facebook -> getAccessToken();
// 	} else{
// 		$tokenUrl  = "https://graph.facebook.com/oauth/access_token?client_id=".CLIENT_ID."&client_secret=".SECRET."&redirect_uri=".REDIRECT_URI."&code=".$code;
// 		$params = file_get_contents($tokenUrl);
// 		$output = null;
// 		parse_str($params,$output);
// 		$accessToken = $facebook -> getAccessToken();
// 		//$accessToken = $output['access_token'];
// 		// grab data on behalf os user
// 	//	$info = $facebook -> api('/blee908/friends');
// 		// $graphUrl  = "https://graph.facebook.com/blee908/friends?access_token=".$accessToken;
// 		// $info = json_decode(file_get_contents($graphUrl));*/

?>