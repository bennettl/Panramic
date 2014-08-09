// JavaScript Document
$(document).ready(function() {
	tabTop();
	main();
});

/* --- Function List --- */
// All functions related to tabTop are placed here
function tabTop() {
	// When user clicks #sidenvvTop <a>, all <div> will be hidden except for the <div> with the id that maches the <a> id attribute
	$("#tabTop a").click(function() {
		var tabId = $(this).attr("id").substring(4);
		var info  =  {'template': true, 'file':  "account." + tabId + ".php", 'filebase' : 'account'};
		console.log(templateFile);
		$("#settingsContainer").hide();
		$("#loading").show();
		$("#settingsContainer").load(templateFile,info,function() {
			$("#loading").hide();
			$(this).hide().fadeIn(500);
			window[tabId]();
		});
	});
}

/* --- #main --- */
// All functions relevant to main are placed here
function main() {
	// When #availCheck is click, send a post request and display the appropriate response
	$("#availCheck").click(function() {
		var username = $(this).siblings(".inputText").val();
		var info = {"availCheck": "true", "username": username};
		$.post('ajax/ajax.account.php', info , function(data) {
			var success = data.success;
			var response = data.response;
			if (success == "yes") {
				$("#availRe").css("color", "#69AC66");
			} else if (success == "no") {
				$("#availRe").css("color", "#C24545");
			} else if (success == "same") {
				$("#availRe").css("color", "#333333");
			}
			$("#availRe").html("<span style='color: #555555;'> | </span>" + response);
		}, 'json');
		return false;
	});
	fileInput();
	// When #generalForm is submitted, wait for generalFrame to finish loading and display the appropriate response
	$("#generalForm").submit(function() {
		var fileVal = $("[name='profileImg']").val();
		// Validate username input if it exists
		if ($("input[name='username']").length == 1) {
			var fieldArray = new Array('firstname', 'lastname', 'username', 'hometown');
		} else {
			var fieldArray = new Array('firstname', 'lastname', 'hometown');
		}
		errorMsg = '';

		// If validateEmail has a return value, then there is an errorMsg
		if (validateField(fieldArray)) {
			errorMsg = validateField(fieldArray);
		}
		// If validateEmail has a return value, then there is an errorMsg
		if (validateEmail($("[name='email']"))) {
			errorMsg = validateEmail($("[name='email']"));
		}
		// If validateFile has a return value, then there is an errorMsg
		if (validateFile(fileVal)) {
			errorMsg = validateFile(fileVal);
		}

		if (errorMsg == "") {
			$("#generalNoti").text("Updating...").show();
			$("#generalFrame").load(function() {
				var message = $("#generalFrame").contents().find("div#msg").text();
				$("#generalNoti").text(message).show();
				// Refresh the image source if user uploaded one
				if ($("[name='profileImg']").val() != "") {
					var strStart = parseInt($("#profileImg").attr("style").indexOf("users/p")) + 7;
					var strEnd = $("#profileImg").attr("style").indexOf(".jpg");
					var userId = $("#profileImg").attr("style").substring(strStart, strEnd);
					var rand = new Date().getTime();
					var imgSrc = "url(images/users/p" + userId + ".jpg?" + rand + ") no-repeat 50% 30%";
					$("#profileImg").css("background", imgSrc);
					$(".fileContainer .inputFile").val("");
					$(".fileContainer .fileText").val("");
				}
				return false;
			});
		} else {
			$("#generalNoti").text(errorMsg).show();
			return false;
		}
	});

	// When user submits #passwordForm, check to see if the newPass is empty, if it is not, then send a post request and prepend the response to #passForm tr
	$("#passForm").submit(function() {
		var formInfo = $(this).serialize() + "&updatePass=true";
		var oldPass = $("[name='oldPass']").val().replace(/\s/g, "");
		var newPass = $("[name='newPass']").val().replace(/\s/g, "");
		var newPassV = $("[name='newPassV']").val().replace(/\s/g, "");
		var errorMsg = '';

		// Error checking
		if (newPass != newPassV) {
			errorMsg = "New passwords don't match";
		}
		if (newPass == "") {
			errorMsg = "Please enter your new password";
		}
		if (oldPass == "") {
			errorMsg = "Please enter your old password";
		}

		if (errorMsg == '') {
			$.post('ajax/ajax.account.php', formInfo, function(data) {
				var message = data.response;
				if (message.indexOf("success") > -1) {
					$("[name='oldPass']").val("");
					$("[name='newPass']").val("");
					$("[name='newPassV']").val("");
				}
				$("#passNoti").text(message).show();
			}, 'json');
		} else {
			$("#passNoti").text(errorMsg).show();;
		}
		return false;
	});

	formProcess("Look");
	formProcess("Privacy");
	formProcess("Noti");
	checkfbChange();
}

// When user submits the form, show the coressponding noti and send the corresponding post request
function formProcess(formTypeUpper) {
	var formTypeLower = formTypeUpper.toLowerCase();
	$("#" + formTypeLower + "Form").submit(function() {
		var formInfo = $(this).serialize() + "&update" + formTypeUpper + "=true";
		$("#" + formTypeLower + "Noti").show();
		$.post('ajax/ajax.account.php', formInfo);
		return false;
	});
}

// This function initally calls checkfbStatus if pushFb is checked, then attaches a .change handler for pushFb
function checkfbChange() {
	if ($("[name='pushFb']:checked").val() == 'yes') {
		checkfbStatus();
	}
	$("[name='pushFb']").change(function() {
		if ($(this).val() == 'yes') {
			checkfbStatus();
		} else {
			$("#fbNoti").hide();
		}
	});
}

// This function checks to see if user's fb status is in correct state to post events
function checkfbStatus() {
	var clientId    = '258940897480780';
	var redirectUrl = "http://www.panramic.com/";
	var scope       = 'publish_stream,rsvp_event,read_stream,offline_access';
	var url         = "http://www.panramic.com/fb.connect.php";

	// Notify user that of processing state
	$("#fbNoti").text("Checking...").css("color", "#AAAAAA").show();

	// Initialize FB object
	FB.init({
		appId: clientId,
		status: true,
		cookie: true,
		xfbml: true,
		oauth: true
	});

	// Determine if user is logged in to facebook and level of permission
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			// User is logged in and connected to fb app
			var accessToken = response.authResponse.accessToken;
			// Check for specific permissions
			FB.api('me/permissions?access_token=' + accessToken, function(response) {
				var publishs = response.data[0].publish_stream;
				//	var publisha  = response.data[0].publish_actions;
				var rsvp = response.data[0].rsvp_event;
				var read = response.data[0].read_stream;
				var offline = response.data[0].offline_access;
				// If  permissions exist, then process form
				if (publishs && read && rsvp && offline) {
					message = 'Connected with Facebook';
					$("#fbNoti").html(message);
				} else {
					message = 'Click <a href="' + url + '" target="_blank"><u>here</u></a> to connect with Facebook';
					$("#fbNoti").html(message).css("color", "#333333");
				}
			});
		} else if (response.status === 'not_authorized') {
			// User is logged in to Facebook, but not connected to the app
			message = 'Click <a href="' + url + '" target="_blank"><u>here</u></a> to connect with Facebook';
			$("#fbNoti").html(message).css("color", "#333333");
		} else {
			// User isn't even logged in to Facebook.
			message = 'Click <a href="' + url + '" target="_blank"><u>here</u></a> to connect with Facebook';
			$("#fbNoti").html(message).css("color", "#333333");
		}
	});
}


// All functions relevant to network are placed here
function network() {
	// When user submits #changeMajor, send a post request, remove the current class, and then show #groupNoti. Also, the <input> are cleaned up after submission.
	$("#changeNet").submit(function() {
		var formInfo = $(this).serialize() + "&changeNet=true";
		$("#network #networkNoti").text("Network has been updated").show();
		$.post('ajax/ajax.account.php', formInfo);
		return false;
	});
}

// All functions fields to network are placed here
function field() {
	fieldInterface();
	// When user submits #field #addForm, show #fieldNoti, clean up the form, and send a post request
	$("#field #addField").submit(function() {
		// Check to see if user selected any fields before sending post request
		if ($("#addField input").length > 1) {
			var formInfo = $(this).serialize() + "&addField=true";
			var notiText = ($("#fieldContainer i.current").length > 1) ? "Fields Added" : "Field Added";
			$("#add .fieldNoti").text(notiText).show();
			$(this).html("<input type='submit' class='inputSubmit settingsSubmit' name='addField' value='Add' />");
			$("#fieldContainer i.current").removeClass("current");
			$.post('ajax/ajax.account.php', formInfo);
		} else {
			$("#add .fieldNoti").text("Please select a field").show();
		}
		return false;
	});


	// When user submits #field #removeForm, show #fieldNoti, clean up the form, and send a post request
	$("#field #removeField").submit(function() {
		// Check to see if user selected any fields before sending post request
		if ($("#removeField input").length > 1) {
			var formInfo = $(this).serialize() + "&removeField=true";
			var notiText = ($("#remove .mediumList li.current").length > 1) ? "Fields Removed" : "Field Removed";
			$("#remove .fieldNoti").text(notiText).show();
			$("#remove :checked").parents("tr").remove();
			$.post('ajax/ajax.account.php', formInfo);
		} else {
			$("#remove .fieldNoti").text("Please select a field").show();
		}
		return false;
	});
}

// All functions relevant to group are placed here


function group() {
	// Toggles border and hidden input of groups
	$("#group li").toggle(

	function() {
		var groupId = $(this).attr("value");
		$(this).addClass("current")
		$("<input type='hidden' name='group[]' value='" + groupId + "'/>").appendTo("#unblockForm");
	}, function() {
		var groupId = $(this).attr("value");
		$(this).removeClass("current");
		$("#unblockForm input[value='" + groupId + "']").remove();
	});

	// When user submits #group #unblockForm, show #groupNoti, hide the <li>, reset form, and send a post request
	$("#group #unblockForm").submit(function() {
		// Check to see if user selected any fields before sending post request
		if ($("#unblockForm input").length > 1) {
			var formInfo = $(this).serialize() + "&unblockGroup=true";
			var notiText = ($("#group .mediumList li.current").length > 1) ? "Groups unblocked" : "Group Unblocked";
			$("#groupNoti").text(notiText).show();
			$(this).siblings(".mediumList").children(".current").hide();
			$(this).html("<input type='submit' class='inputSubmit settingsSubmit' name='unblock' value='Unblock' />");
			$.post('ajax/ajax.account.php', formInfo);
		} else {
			$("#groupNoti").text("Please select a group").show();
		}
		return false;
	});
}