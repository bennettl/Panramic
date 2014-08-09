// JavaScript Document
$(document).ready(function(){
navCurrent("#btnContainer","#btnContainer .videoBtn");
// When .videoBtn is clicked, display the approriate video
$(".videoBtn").click(function(){
	var btnType = $(this).attr("id");
	if (btnType == "promoBtn" && $("#promoBtn.current").length == 0){
		$("#video").attr("src","http://www.youtube.com/embed/5ceozhmVzoE?autohide=1&rel=0");
		
	} else if (btnType == "groupBtn" && $("#groupBtn.current").length == 0) {
		$("#video").attr("src","http://www.youtube.com/embed/TO4VEGP4NRY?autohide=1&rel=0");
	}
});

// When #signUpForm is submitted, validate the form
$("#signUpForm").submit(function(){
	var errorMsg  = "";
	var fullname  = $("[name='fullname']").val();
	var pos1      = fullname.indexOf(" ");
	var firstname = fullname.substring(0,pos1);
	var lastname  = fullname.substr(pos1+ 1);
	var birthday  = $("[name='month']").val() +'/' + $("[name='date']").val() +'/' + $("[name='year']").val();
	var info      = "&firstname=" + firstname + "&lastname=" + lastname + "&birthday=" + birthday + "&signUp=true";
	// Create a maximum length for each field
	var maxLengths = {'fullname': 60, 'email': 50, 'password': 15};
	for (field in maxLengths){
		if (parseInt($("input[name='"+ field +"']").val().length) > maxLengths[field]){
			switch(field){
				case "firstname":
					errorMsg = "Full Name is too long";
					break;
				case "email":
					errorMsg = "Invalid email address";
					break;
				case "password":
					errorMsg = "Password can't be greater than " + maxLengths[field] + " characters";
					break;
				default:
					break;
			}
		}
	}
	
	// If validateEmail has a return value, then there is an errorMsg
	var fieldArray = new Array('fullname','password');
	if (validateField(fieldArray)){
		errorMsg = validateField(fieldArray);
	}
	
	if (fullname.indexOf(" ") == -1){
		errorMsg = "Please enter your full name";
	}
	// If validateEmail has a return value, then there is an errorMsg
	if (validateEmail($("[name='email']"))){
		errorMsg = validateEmail($("[name='email']"));
	}
	
	// Create a set length (and invalid value of -1 and 0 for intval) for these fields
	var setLengths = {'month': 2,'date' : 2,'year': 4};
	for (field in setLengths){
		if (parseInt($("[name='"+ field +"']").val().length) != setLengths[field] || parseInt($("[name='"+ field +"']").val()) == -1){
			errorMsg = "Please enter your birthday";
		}
	}
	
	// There are only two possible values for sex
	if ($("[name='gender']").val() != "male" && $("[name='gender']").val() != "female"){
		errorMsg = "Please select your sex";
	}
	
	// If there are any error messages, notifiy the user
	if (errorMsg == ''){
		var formInfo = $(this).serialize() + info;
		
		// Depending on whether or not the signup is sucessful, perform the appropriate action
		$.post('ajax/ajax.signup.php',formInfo, function(data){
			var success = data.success;
			var url     = 'http://www.panramic.com/fb.connect.php?signup=true';
			if (success == "yes"){
				window.open(url,'_parent');
			} else{
				errorMsg = data.response;
				$("#errorMsg").text(errorMsg);
				$("#errorContainer").show();
			}
		},'json');
	} else{
		$("#errorMsg").text(errorMsg);
		$("#errorContainer").show();
	}

	return false;

});

// When #signUpForm is submitted, validate the form
$("#signUpFb").click(function(){
	var clientId    = '258940897480780';
	var redirectUrl = "http://www.panramic.com/test.php";
	var info        = {"signUp": true};

	FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});

	FB.login(function(response){
		if (response.authResponse){
			// Determine if user is permission level is correct
			FB.getLoginStatus(function(response){
				if (response.status === 'connected'){
					// User is logged in and connected to fb app, so check for specific permissions
					var accessToken = response.authResponse.accessToken;
					FB.api('me/permissions?access_token=' + accessToken,function(response){
						var email        = response.data[0].email;
						var birthday     = response.data[0].user_birthday;
						var publishs     = response.data[0].publish_stream;
						//	var publisha = response.data[0].publish_actions;
						var readStream   = response.data[0].read_stream;
						var rsvp         = response.data[0].rsvp_event;
						var readFriend   = response.data[0].read_friendlists;
						var offline      = response.data[0].offline_access;
						// If  permissions exist, then process form
						if (email && birthday && publishs && readStream && rsvp && readFriend && offline){
							procesSignup(info);
						} else{
							$("#errorMsg2").html("To give you the best user experience, <br /> Panramic requires certain Facebook permssions").show();
						}
					});
				}
			});
		} else{
			//User cancelled login or did not fully authorize
			$("#errorMsg2").html("To give you the best user experience, <br /> Panramic requires certain Facebook permssions").show();
		}
	}, {scope: 'email,user_birthday,publish_stream,read_stream,rsvp_event,read_friendlists,offline_access'});
	return false;
});

// This function takes info from facebook, sends a post request to process the form, then handles depending if it is sucessful or not
function procesSignup(info){
	FB.api('/me', function(response){
		info.fbid      = response.id;
		info.firstname = response.first_name;
		info.lastname  = response.last_name;
		info.gender    = response.gender;
		info.email     = response.email;
		info.birthday  = response.birthday;
		$.post('ajax/ajax.signup.php',info, function(data){
			var success = data.success;
			var url     = 'http://www.panramic.com/steptwo';
			if (success == "yes"){
				window.open(url,'_parent');
			} else{
				errorMsg = data.response;
				$("#errorMsg2").text(errorMsg).show();
			}
		},'json');
	});
}
	
});