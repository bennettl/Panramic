// JavaScript Document
$(document).ready(function(){
	// When #connectFb request facebook permission
	$("#connectFb").click(function(){
		var clientId 	 = '258940897480780';
		var redirectUrl  = "http://www.panramic.com/fb.connect.php?";
		redirectUrl 	+= ($(this).hasClass("/steptwo")) ? "signup=true&" : '';
		FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
	
		FB.login(function(response){
			if (response.authResponse){
				// Determine if user is permission level is correct
				FB.getLoginStatus(function(response){
					if (response.status === 'connected'){
						// User is logged in and connected to fb app, so check for specific permissions
						var accessToken = response.authResponse.accessToken;
						FB.api('me/permissions?access_token=' + accessToken,function(response){
							var publishs  	= response.data[0].publish_stream;
						//	var publisha  	= response.data[0].publish_actions;
							var readStream 	= response.data[0].read_stream;
							var rsvp 	  	= response.data[0].rsvp_event;
							var readFriend 	= response.data[0].read_friendlists;
							var offline   	= response.data[0].offline_access;
							// If  permissions exist, then process form
							if (publishs && readStream && rsvp && readFriend && offline){
								redirectUrl    += "update=true";
								window.location = redirectUrl;
							} else{
								$("#errorMsg").html("To give you the best user experience, Panramic requires more Facebook permssions").show();
							}
						});
					}
				});
			} else{
				//User cancelled login or did not fully authorize
				$("#errorMsg").html("To give you the best user experience, Panramic requires more Facebook permssions").show();
			}
		}, {scope: 'publish_stream,read_stream,rsvp_event,read_friendlists,offline_access'});
		return false;
	});
	$("#footer").css("position","relative");
});
