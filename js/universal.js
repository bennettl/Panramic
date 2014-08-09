// JavaScript Document
// Declare global variables
var templateFile = 'template.php';

$(document).ready(function(){
	hd();
	feed();
	rightContainer();
	mod();
	guestListContainer();
	navCurrent("#feedList","#feedList .delete");
	navCurrent("#tabTop","#tabTop a");
	navCurrent("#pageList","#pageList a");
	deleteTip();
	//navCurrent("#reviewWall","#reviewWall .delete");
	//navCurrent("#inbox","#inbox .delete");
	// Prevents hitting enter from submitting form
	$("input:not([name='s'])").live('keydown',function(evt){
		if (evt.which == 13) evt.preventDefault();
	});
});

/* --- Functions List --- */
// Toggles .delete and appends #deleteTip to the <li> selected to be deleted. Note: Replies can only be removed;
function deleteTip(){
	$(".delete").live('click',function(){
		if ($("#deleteTip").css("display") == "none"){
			var listParent   = $(this).parent("li");
			var xPos 	     = $(this).position().left;
			var yPos 		 = $(this).position().top;
			$("#deleteTip").css({top: yPos + 12 + "px", left: xPos - 61 + "px"}).appendTo(listParent).show();
		} else if ($("#deleteTip").css("display") == "block"){
			$(this).removeClass("current");
			$("#deleteTip").appendTo("#leftContainer").hide();
		}
		// If its a message reply, hide the reportBtn
		if ($(this).parents(".msgReply").length == 1){
			$("#reportBtn").hide();
		} else{
			$("#reportBtn").show();
		}
		// If its is tab_everyFeed or tab_friendFeed, hide the removeBtn
		if ($("#sidenavTop #tab_everyFeed.current").length == 1 || $("#sidenavTop #tab_friendFeed.current").length == 1){
			$("#removeBtn").hide();
		} else{
			$("#removeBtn").show();
		}
		$("#friendTip").hide();
	});
}
// The navLink is the link that will be clicked by user and added .current. There will be only one .current within its parent. Also, check to see if there is a sideNoti and remove it if there is
function navCurrent(parent,navLink){
	$(navLink).live('click',function(){
		if ($(navLink + ".current .sideNoti").length == 1){
			$(navLink + ".current .sideNoti").remove();
		}
		$(parent + " .current").removeClass("current");
		$(this).addClass("current");
		return false;
	});
};
	
// Hides inputSubmit and fades it in when inputText is focused. When user clicks inputSubmit, it fades out.
function fadeInSubmit(inputText,inputSubmit){
	$(inputSubmit).hide();
	$(inputText).live('focus',function(){
		$(this).siblings(inputSubmit).fadeIn(300);
	});
	$(inputSubmit).live('click',function(){
		$(this).fadeOut(300);
	});
};

// Textarea exapnder. Takes into consideration the newLines and adjusts height accordingly
function textExpand(element){
	$(element).each(function(){
		var originalHeight = $(this).height();
		var originalNL     = ($(this).val().match(/\n/g) == null) ? 0 : $(this).val().match(/\n/g).length;

		$(this).keyup(function(){
			var newNL      = ($(this).val().match(/\n/g) == null) ? 0 : $(this).val().match(/\n/g).length;
		 	var diffNL     = newNL - originalNL;
			var heightAdd  = (newNL - originalNL == 0) ? 0 :  diffNL * 20;
			var newHeight  = originalHeight + heightAdd;
			$(this).height(newHeight)
		});
	})
}

// If the natural height of the .feedDescription is larger than the defineHeight, then place a limit on the height toggle the description. Also, do not add padding to hidden .feed
function feedToggle(){
	// Deal with cross browser css issues
	var defineHeight = ($.browser.mozilla) ? "36px" : "39px";
	$(".feedDescription").each(function(){
		var feed          = $(this).parents("li.feed");
		var normalHeight  = parseInt($(this).css("height","auto").height());
		var moreInfo      = $(this).parents(".feedInfo").find(".moreInfo");
		$(this).height(defineHeight);
		if (moreInfo.length == 0){
			if (normalHeight > parseInt(defineHeight)){
				$(this).parents(".feedInfo").css('cursor','pointer').append("<tr><td colspan='2'><a href='#' class='moreInfo'>more...</a></td></tr>");

			} else if (feed.css("display") == "list-item"){
				$(this).css("padding","0 0 15px 0");
			}
		}
	});
	
	//  .feedDescription toggles when user clicks .moreInfo
	$(".feedInfo .moreInfo,").click(function(){
		if ($(this).text() == "more..."){
			$(this).parents(".feedInfo").find(".feedDescription").css("height","auto");
			$(this).text("hide");
		} else if ($(this).text() == "hide"){
			$(this).parents(".feedInfo").find(".feedDescription").css("height",defineHeight);
			$(this).text("more...");
		}
		$("#friendTip").hide();
		return false;
	});
	//  .feedDescription toggles when user clicks .moreInfo
	$(".feedInfo,").click(function(){
		var moreInfo = $(this).find('.moreInfo');
		if (moreInfo.text() == "more..."){
			$(this).find(".feedDescription").css("height","auto");
			moreInfo.text("hide");
		} else if (moreInfo.text() == "hide"){
			$(this).find(".feedDescription").css("height",defineHeight);
			moreInfo.text("more...");
		}
		$("#friendTip").hide();
	});
}

// Toggles display for .delete when user hovers over parent 
function deleteHover(parent){
	$(parent).live('mouseenter',function(){
		var deleteBtn = $(this).children(".delete");
		if (deleteBtn.length == 1){
			deleteBtn.show();
		}
	});
	$(parent).live('mouseleave',function(){
		var deleteBtn = $(this).children(".delete");
		var current   = $(this).children(".delete.current");
		if (deleteBtn.length == 1 && current.length == 0){
			deleteBtn.hide();
		}
	});
}

// Validation on email
function validateEmail(email){
	var reg     = /^([A-Za-z0-9_\-\.]){3,}\@([A-Za-z0-9_\-\.]){3,}\.([A-Za-z]{2,4})$/;
	var address = email.val();
	if (reg.test(address) == false){
		errorMsg = "Invalid email address";
		return errorMsg;
	}
}

// Validation on fields
function validateField(fieldArray){
	var reg = /^([A-Za-z0-9_\-\.\,\/\'\s]){1,}$/;
	for (var i =0; i < fieldArray.length; i++){
		var field = $("input[name='"+ fieldArray[i] +"']").val().replace(/\s/g,"");
		if (reg.test(field) == false){
			switch(fieldArray[i]){
				case "firstname":
					errorMsg = "Invalid first name";
					break;
				case "lastname":
					errorMsg = "Invalid last name";
					break;
				default:
					errorMsg = "Invalid " + fieldArray[i];
					break;
			}
			return errorMsg;
		}
	}
}
// If the uploaded file does not match these file extensions, then there is an errorMsg
function validateFile(fileVal){
	if (fileVal != "" && fileVal.indexOf(".jpg") == -1 && fileVal.indexOf(".gif") == -1 && fileVal.indexOf(".png") == -1){
		errorMsg = "Image must be jpg, png, or gif format";
	}
	return errorMsg;
}
// This function handles the submit and cancel functions of .fileContainer
function fileInput(){
	// Deal with cross browser css issues for hidden input file
	var right = ($.browser.mozilla) ? "90px" : "25px";
	$(".fileContainer .inputFile").css("right",right);
	
	// When user clicks .fileText, change the value of the actual file accordingly
	$(".fileText").live('focus',function(){
		$(this).blur();
	}); 
	// When user clicks .fileSubmit, change the value of the actual file accordingly
	$(".inputFile").live('click',function(){
		var fileElement = $(this);
		processVal(fileElement);
	});
	// When user clicks cancelBtn, empty the value of both .inputFile and .fileText
	$(".cancelBtn").live('click',function(){
		var file = $(this).parents(".fileContainer").find(".inputFile");
		var text = $(this).parents(".fileContainer").find(".fileText");
		if (file.val() != ""){
			file.val("");
			text.val("");
		}
		return false;	
	});
	
	// This function clicks the inputFile and waits for a change event to insert the value in .fileText
	function processVal(fileElement){
		var inputFile = fileElement.parents(".fileContainer").find(".inputFile");
		// Deals with cross browswer issues with ie on jquery .change()
		if ($.browser.msie){
			 setTimeout(function(){
				changeVal(inputFile);
        	},0);
		} else {
			inputFile.change(function(){
				changeVal(inputFile);
			});
		}
	}
	// This function copies the value of inputFile and pastes it into .fileText
	function changeVal(inputFile){
		var fileVal = inputFile.val();
		// Handles fakepath of fileVal for chrome browswer
		var filePos = fileVal.indexOf("C:\\fakepath\\");
		if (filePos > -1){
			fileVal = fileVal.substr(12);
		}
		inputFile.parent(".fileContainer").find(".fileText").val(fileVal);
	}
}
// This function takes care of when users click add/join on profiles
function addBtn(){
	// When the addBtn is click, then send either a friend or member post request
	$("#addBtn").click(function(){
		if ($(this).text() == 'Join'){
			var groupId  = $("#groupInfo input[name='groupId']").val();
			var noti     = $("<span style='font-weight:bold;'>Member Request Sent</span>");
			$.post('ajax/ajax.add.php',{"memberRequest": "true", "groupId": groupId})
		}
		// Depending on the length of the name, modify the padding accordingly
		if ($("#profileName").text().length < 30){
			$(this).removeAttr("id").css({"padding":"30px 0 0 0", "float": "left"}).html(noti);
		} else{
			$(this).removeAttr("id").css({"padding":"0 0 0 6px", "float": "left"}).html(noti);
		}
	});
}
// Handles page list for #inbox,#feedContainer ,and #reviewWall
function pageList(){
	if ($("#feedContainer").length == 1){
		// Depending on which #pageList a the the user clicks, send the corresponding request and repalce the .msgFeedContainer
		$("#feedContainer #pageList a").click(function(){
			var pageNum   = $(this).attr("href").substr(1);
			var info 	  = ($("#foodOn.current").length == 1) ? "freeFood=true&" : "";
			info 		 += "myCal=true&pageNum=" + pageNum;
			// If the friendTip and delete tip isn't append to #leftContainer when the new page loads, then it will be lost
			$("#friendTip").appendTo("#leftContainer").hide();
			$("#deleteTip").appendTo("#leftContainer").hide();
			$("#loading").show();
			$.post('home.userFeed.php',info,function(data){
				var newFeedList = $(data).find("#feedList");
				$("#loading").hide();
				$("#feedList").replaceWith(newFeedList);
				$("#feedList").hide().fadeIn(500);
				feedToggle();
				filterPersist();
				feedNoti();
			},'html');
		});
	} else if ($("#reviewWall").length == 1){
		// Depending on which #pageList a the the user clicks, send the corresponding request and repalce the .msgFeedContainer
		$("#reviewWall #pageList a").click(function(){
			var ahref     = $(this).attr("href").split("_");
			var pageNum   = ahref[0].substr(1);
			var groupId   = ahref[1];
			$("#loading").show();
			$("#deleteTip").appendTo("#leftContainer");
			$.post('gprofile.reviewWall.php',{"pageNum":pageNum,"groupId":groupId},function(data){
				var newList = data;
				$(".msgFeedContainer").replaceWith(newList);
				$("#loading").hide();
				$(".msgFeedContainer").hide().fadeIn(500);
				reviewWall();
			},'html');
		});
	}
}

// Toggles focus/blur for textinput and replaces it with textVal and inactiveText
function toggleText(textInput, textVal){
	$(textInput).focus(function(){
		if ($(this).val() == textVal){
			$(this).val("").removeClass("inactiveText");
		}
	});
	$(textInput).blur(function(){
		if ($(this).val() == ""){
			$(this).val(textVal).addClass("inactiveText");
		}
	});
}

// All functions relevant to hd are placed here
function hd(){
	// If .searchText isn't empty, then apply the class to it
	if ($("#hd .searchText").val() != ""){
		$("#hd .searchContainer").addClass("active");
		$("#hd .searchSubmit").addClass("mglasslight");
	}
	// Toggles css for #searchContainer
	$("#hd .searchText").focus(function(){
		$("#hd .searchContainer").addClass("active");
		$("#hd .searchSubmit").addClass("mglasslight");
	});
	$("#hd .searchText").blur(function(){
		var textVal = $(this).val().replace(/\s/g,"");
		if (textVal == ""){
			$("#hd .searchContainer").removeClass("active");
			$("#hd .searchSubmit").removeClass("mglasslight");
		}
	});
	
	// Toggles #dropdown and css for #account
	$("#menu #account").toggle(
		function(){
			$(this).addClass("accountActive");
			$("#dropdown").slideDown(300);
			// Deal with cross browser css issues
			if ($.browser.webkit){
				$("#dropdown").css("left","237px");
			} },
		function(){
			$(this).removeClass("accountActive");
			$("#dropdown").slideUp(300);
	});
	
	
	// Toggles #signInForm
	$("#signIn").toggle(
		function(){
			$(this).addClass("signInA");
			$("#signInForm").show()
			// If email is already entered, focus on the password
			if ($("#signInForm [name='siEmail']").val().length > 0){
				$("#signInForm [name='siPassword']").focus();
			} else{
				$("#signInForm [name='siEmail']").focus();
			}
			return false; },
		function(){
			$(this).removeClass("signInA");
			$("#signInForm").hide();
			return false;
	});
	
	// When user hits enter on password field, it will submit the form
	$("#signInForm [name='siPassword']").keydown(function(evt){
		if (evt.which == 13){
			$("#signInForm").submit();
		}
	});
	
	// When signInform is submitted send a post request. If it's sucessful, redirect user, else display error message
	$("#signInForm").submit(function(){
		var info = $(this).serialize() + "&signIn=true";
		var email = $("#signInForm [name='siEmail']").val();
		var password = $("#signInForm [name='siPassword']").val();
		var errorMsg  = '';
		
		$("#signInError").hide();
		
		// Validation
		if (email == ''){
			errorMsg = "Please enter your email";
		}
		if (password == ''){
			errorMsg = "Please enter your password";
		}
		
		if (errorMsg == ''){
			signIn(info);
		} else{
			$("#signInError").text(errorMsg).show();
		}
		return false;
	});
	
	// When signInFb is clicked send a post request. If it's sucessful, redirect user, else display error message
	$("#signInFb").click(function(){
		var info      = "&fbsignIn=true";
		var clientId  = '258940897480780';
		var errorMsg  = '';
	
		$("#signInError").hide();
		// If there are any error messages, notifiy the user
		if (errorMsg == ''){
	
			// Initialize FB object
			FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
		
			// Prompt the user to log into facebook. Else sign the user in with fb id
			FB.login(function(response){
				if (response.authResponse){
					// Determine if user is permission level is correct
					FB.getLoginStatus(function(response){
						if (response.status === 'connected'){
							// User is logged in and connected to fb app, so store fbid and check for specific permissions
							var fbid = response.authResponse.userID;
							var accessToken = response.authResponse.accessToken;
							FB.api('me/permissions?access_token=' + accessToken,function(response){
								var publishs  	= response.data[0].publish_stream;
							//	var publisha  	= response.data[0].publish_actions;
								var readStream 	= response.data[0].read_stream;
								var rsvp 	  	= response.data[0].rsvp_event;
								var readFriend 	= response.data[0].read_friendlists;
								var offline   	= response.data[0].offline_access;
								// If  permissions exist, then add fbid to forminfo and send the request
								if (publishs && readStream && readFriend && offline){
									info += "&fbid=" + fbid;
									signIn(info);
								} else{
									$("#signInError").html("To give you the best user experience, Panramic <br /> requires certain Facebook permssions");
									$("#signInError").show();
								}
							});
						}
					});
				}
			}, {scope: ',publish_stream,read_stream,rsvp_event,read_friendlists,offline_access'});
		} else{
			$("#signInError").text(errorMsg).show();
		}
	});
}
		
// This function sends a post request to ajax.sign and response accordingly
function signIn(info){	  
  $.post('ajax/ajax.signin.php',info, function(data){
	  if (data.success == 'yes'){
		  window.location = data.message;
	  } else if (data.success == 'no'){
		  $("#signInError").html(data.message).show();
	  }
  },'json');
}

// All functions relevant to feed are placed here
function feed(){
	// If user clicks .feed, hide #friendTip
	$(".feed").live('click',function(){
		$("#friendTip").hide();
	});
	// If user leaves .feed and .feedSideTip exists, then remove the class, else hide #friendTip if it exists
	$(".feed").live('mouseleave',function(){
		if ($(".feedSideTip").length > 0){
			$(".feedSideTip").removeClass("feedSideTip");
		} else if ($("#friendTip").length == 1){
			$("#friendTip").hide();
		}
	});
	// If .feedDate a is clicked, simply toggle the display of the rsvpDate and startDate
	$(".feedDate a").live('click',function(){
		$(this).addClass("hidden");
		$(this).siblings("a.hidden").removeClass("hidden");
		return false;
	});
	// Toggles title attr of sideOptionTip when .sideOptions are hovered over
	$(".sideOptions").live('mouseover',function(){
		var optionTitle  = $(this).attr("title");
		var xPos 		 = $(this).position().left;
		var yPos 		 = $(this).position().top;
		var thisParent 	 = $(this).parents(".feedSide");
		$("#sideOptionTip").css({top: yPos - 29 + "px", left: xPos - 9 + "px"}).appendTo(thisParent).text(optionTitle).show();
		$(this).removeAttr("title");
	});
	$(".sideOptions").live('mouseout',function(){
		var originalTitle = $("#sideOptionTip").text();
		$("#sideOptionTip").hide().appendTo("#leftContainer");
		$(this).attr("title", originalTitle);
	});
		
	// When the user clicks .like.sideOptions, send the post request to increment the like and atttend count
	$(".like.sideOptions, .dislike.sideOptions").live('click',function(){
		// If user is not logged in, prompt them to. a is cookie only given to logged in users
		if (document.cookie.indexOf("e=") == -1){
			var yPos  = $(window).scrollTop() + 100;
			$("#loginMod").css({"top": yPos + "px"}).show();
			return false;
		}
		var like	  = $(this).hasClass("like");
		var eventId   = $(this).parents(".feed").attr("value");
		var fbEvent	  = ($(this).parents(".feed").attr("class").indexOf("fbEvent") > -1) ? true : false;
		var rsvp	  = ($("#fbPermission [name='rsvp']").val() == 'true') ? true : false;
		var classStr  = $(this).parents(".feed").attr("class");
		var info	  = (like) ? {"attendEvent": "true", "eventId": eventId,"fbEvent": fbEvent, "rsvp": rsvp} :  {"removeEvent": "true", "eventId": eventId};
		var noti;
		// Depending if the event is connected with facebook and user has allowed rsvp, display the apprpriate notification
		if (fbEvent && rsvp){
			noti = 'Event rsvped on Facebook and moved to your calendar';
		} else if (fbEvent && !rsvp){
			noti = 'Event moved to your calendar. To add Facebook rsvp feature, please go to your Panramic account settings';
		} else{
			noti = 'Event moved to your calendar';
		}
			noti = (like) ? noti : 'Event has been removed';

		$(this).parents(".feed").addClass("feedSelected")
		$("#sideOptionTip").appendTo("#leftContainer").hide();
		
		// If a mediumList is involved, then, hiding the corresponding .like and .dislike of .feed and empty #friendText if its eventInv
		if ($(".mediumList").length  > 0){
			if ($("#eventInv").length == 1){
				$("#friendText").text("");
			}
			$(".mediumList li.feed[value='" + eventId + "']").find(".like").css("visibility","hidden");
			$(".mediumList li.feed[value='" + eventId + "']").find(".dislike").css("visibility","hidden");
		}
		$(".feedSelected").replaceWith("<li class='notification'>"+ noti +"</li>"); 
		

		$.post("ajax/ajax.feed.php",info); 
	});
	
	// When .invite.sideOptions is click, this opens an facebook dialogue for app request
	$(".invite.sideOptions").live('click',function(){
		var name	     = $(this).parents("li").find(".feedName").text();
		var location	 = $(this).parents("li").find(".feedLocation").text();
		var clientId 	 = '258940897480780';
		FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
		FB.ui({method:'apprequests',message:'Check out ' + name + 'at ' + location});
	});
	
	// If the user clicks #dislikeYes, use .select as the hook to get both the eventId and groupId of the .feed, follow by a notification and a post request. This includes removing an event for groups
	$("#confirmNoti").live('click',function(){
		if ($(this).parent("#notiMod.dislike").length == 1){
			var groupId  = $(".dislike.sideOptions.selected").parents(".feed").find(".groupName").attr("value");
			var noti     = $("<li class='notification'>Group Blocked</li>");

			$(this).parent("#notiMod").removeClass("dislike");
			$(".dislike.sideOptions.selected").parents(".feed").replaceWith(noti);
			$.post("ajax/ajax.feed.php",{"dislikeGroup": "true","groupId": groupId});
		} else if ($(this).parent("#notiMod.removeEvt").length == 1){
			var array       = $(".miniEvt.selected").attr('class').split(' ');
			var eventId     = array[1].substr(1);
			var groupId     = array[2].substr(1);
			var fbstatus    = ($("[name='fbstatus']:eq(0)").val() == "true") ? true : false;
			var pushFb      = $("[name='pushFb']").is(':checked');
			var gstatus     = ($("[name='gstatus']:eq(0)").val() == "true") ? true : false;
			var pushGoogle  = $("[name='pushGoogle']").is(':checked');
			var info        = {"removeEvt": true, "eventId": eventId, 'groupId': groupId , "fbstatus" : fbstatus, "gstatus" : gstatus};
			var noti        = $("<li><div class='listNote'>Event has been removed</div></li>");		
			info.pushFb     = (pushFb) ? true : false;
			info.pushGoogle = (pushGoogle) ? true : false;
			
			$(this).parent("#notiMod").removeClass("removeEvt");
			$("#formContainer li").hide();
			$("#eventNoti").hide();
			$("#deleteList").appendTo("#leftContainer").hide();
			$(".miniEvt.selected").replaceWith(noti);
			$.post('ajax/ajax.groupManage.php', info);
		}
		$(this).parent().hide();
	});
	
	// If the user clicks #notiMod .delete, then remove .selected as the hook. This includes removing an event for groups
	$("#notiMod .delete").live('click', function(){
		if ($(this).parent("#notiMod.dislike").length == 1){
			$(this).parent("#notiMod").removeClass("dislike");
			$(".dislike.sideOptions.selected").removeClass("selected");
		} else if ($(this).parent("#notiMod.removeEvt").length == 1){
			$(this).parent("#notiMod").removeClass("removeEvt");
			$(".miniEvt.selected").removeClass("selected");
		}
	});	
	
	// When #evtInvForm is submitted, remove all checked attribute, empty the event input, and send a post request
	$("#evtInvForm").live('submit',function(){
		var formInfo = $(this).serialize() + "&evtInvForm=true";
		$.post('ajax/ajax.feed.php',formInfo);
		$("#evtInvForm input:checked").removeAttr("checked");
		$("#evtInvForm input[name='event']").val("");
		$("#inviteMod").hide();
		return false;
	});

	// If the user clicks #feeList, #removeBtn, notify the user the event removed and then send a post request
	$("#feedList #removeBtn").live('click',function(){
		var eventId  = $(this).parents(".feed").attr("value");
		var noti     = $("<li class='notification'>Event removed</li>");
		var classStr  = $(this).parents(".feed").attr("class");
		
		$(this).parents(".feed").hide().after(noti);
		$.post("ajax/ajax.feed.php", {"removeEvent":"true", "eventId": eventId});
		return false;
	});
	
	// If the user clicks #feeList, #reportBtn, display reportForm,pass the event Id, hook on to the current feed with a class, and clean up deleteTip and .delete.current
	$("#feedList #reportBtn").live('click',function(){
		if ($(".feedReport").length == 0){
			var eventId  = $(this).parents(".feed").attr("value");
			var yPos     = $(window).scrollTop() + 50;
			$("#reportMod").css("top", yPos + "px").show();
			$(this).parents(".feed").addClass("feedReport");
			$("#reportForm").find("[name='eventId']").val(eventId);
			$("#deleteTip").appendTo("#leftContainer").hide();
			$(".delete.current").removeClass("current");
		}
		return false;
	});
	
	/* --- #friendTip ---*/
	attendTip(".feedUserAttend",".feedUserInfo");
	attendTip(".feedFriendAttend",".feedFriendInfo");
	
	// When user hovers over .feedFriendAttend, a #friendTip is shown and filled with <li> from .feedFriendInfo. If there is no friends or when user leaves #friendTip, it is hidden
	function attendTip(attendHover, attendInfo){
		$(attendHover).live('mouseenter',function(){
			var xPos 		= $(this).position().left;
			var yPos		= $(this).position().top;
			var feedSide 	= $(this).parents(".feedSide");
			var friendList  = $(this).siblings(attendInfo).find("li");
			
			if (friendList.length > 0){
				var newList = friendList.clone(true,true);
				$("#friendTip").appendTo(feedSide).css({top: yPos + 13 + "px", left: xPos - 68 + "px"}).html(newList).show();
			} else{
				$("#friendTip").hide();
			}
		});
	}
	// When user mouseleaves #friendTip, show #friendTip if .feedSideTip exists. Function deleteHover() removes .feedSideTip
	$("#friendTip").live('mouseleave',function(){
		$(this).hide();
		if ($(".feedSideTip").length == 1){
			$(this).show();
		}
		$("#sideTip").hide();
	});
	
	// Mini tooltip when user hovers over #friendTip img. When user leaves #friendTip img, add feedSideTip, which will be the hook to determine display of #friendTip
	$("#friendTip img").live('mouseenter',function(){
		var listVal  = $(this).attr("alt");
		var xPos 	 = $(this).offset().left;
		var yPos 	 = $(this).offset().top;
		$("#sideTip").appendTo("body").css({top: yPos + 1 + "px", left: xPos + 34 + "px"}).text(listVal).show();

	});
	$("#friendTip img").live('mouseleave',function(){
		$("#sideTip").addClass("feedSideTip").appendTo("#container").hide();
	});
}

// All functions relevant to guestListContainer are placed here
function guestListContainer(){
	navCurrent(".guestTab", ".guestTab li a");
	
	// Toggles class and value for #guestText
	$(".guestText").live('focus',function(){
		if ($(this).val("Find...")){
			$(this).val("").removeClass("inactiveText");
		}
	});
	$(".guestText").live('blur',function(){
		if ($(this).val() == ""){
			$(this).val("Find...").addClass("inactiveText");
		}
	})
	
	// When user clicks #guestTab <a>, all <div> be hidden except for the <div> with the id that maches the <a> href attribute	If the guestDiv is field, update the field count
	$(".guestTab a").live('click',function(){
		var guestDiv = $(this).attr("id").substring(4);
		$(".guestListContainer > div").hide();
		$(".guestListContainer div." + guestDiv).fadeIn(500)
		$(".guestListContainer div." + guestDiv + " *").show();
		
		if ($("#tab_postEvent.current").length == 1 && guestDiv.indexOf("guestField") > -1){
			var hiddenList = $(".guestNetwork :checked").parent(".list").find("div.hidden");
			
			// If there is no networks selected, then set all guestCount to zero
			if (hiddenList.length == 0){
				$(".guestField").find(".guestCount").text("(0)")
			} else{
				// Loop through each hidden div and set the corresponding field count
				hiddenList.each(function(){
					var listStr  = $(this).attr("class").substring(12);
					var pos		 = listStr.indexOf(" ");
					var fieldNum = listStr.substring(0, pos);
					var count    = listStr.substring(pos + 1);
					$(".guestField [value='"+ fieldNum +"']").siblings("a").find(".guestCount").text('(' + count +')');
           		});
			}
		}
		return false;
	});
	
	// Toggle css background of #guestListContainer .list
	$(".guestListContainer .list").live('mouseover',function(){
		$(this).css("background", "#EEF3F4");
		$(this).find(".checkbox").css("color","#333333");
	})
	$(".guestListContainer .list").live('mouseout',function(){
		$(this).removeAttr("style");
		$(this).find(".checkbox").removeAttr("style");
	});
	
	// When user clicks <a> of the list, the corresponding checkbox be clicked.
	$(".guestListContainer .list a").live('click',function(){
		$(this).siblings("input[type='checkbox']").click();
		return false;
	});
	
	// On each keyyp for #guestText,  all <li> will be refresh and it will find any matches between the #guestText value and the <li> value and hide <li> if there is no match
	$(".guestText").live('keyup',function(){
		$(".guestListContainer > div:visible *").show();
		var searchVal = $(this).val().replace(/\s/g,"");
		
		if (searchVal != ""){
			searchVal = $(this).val().toLowerCase();
			
			// If there is no match, hide the <li>
			$(".guestListContainer > div:visible").find(".checkbox").each(function() {
				if ($(this).text().toLowerCase().indexOf(searchVal) == -1){
					$(this).parents("li.list").hide();
				}
			});
			// If .listContainer has no children elements, then hide the .listContainer and the corresponding <div> title
			$(".guestListContainer > div:visible .listContainer").each(function() {
				if ($(this).children(":visible").length == 0){
					$(this).prev("div").hide();
					$(this).hide();
				}
			});
		}
	});
	
}

// All functions relevant to evtWall are place here
function evtWall(){
	// Show the first feed if there is one
	if ($("#evtWall .mediumList > li").length > 0){
		var eventFeed = $(".mediumList li:first").next(".feed").clone(true,true);
		$(".evtContainer:first").html(eventFeed).children().show();
		feedToggle();
	}
	// Whenever a user clicks on #eventInv .mediumList li that is not .feed , will clone the next <li> and display it on top with a fade in
	$("#evtWall .mediumList > li:not('.feed')").click(function(){
		var eventFeed = $(this).next("li.feed").clone(true,true);
		$(this).parent(".mediumList").siblings(".evtContainer").html(eventFeed);
		$("#evtWall .evtContainer li").fadeIn(500);
		feedToggle();
	});
}

// All functions relevant to userWall are placed here
function userWall(){
	// Toggles class and value for inactiveText
	toggleText("#fuserText", "Find...");
	
	// On each keyyp for #fuserText, all <li> and #moreUserBtn will be refresh and it will find any matches between the #fuserText value and the .listName value and hide <li> if there is no match
	$("#fuserText").keyup(function(){
		$("#userWall li:has('.listName'), #moreUserBtn").show();
		var textVal = $(this).val().replace(/\s/g,"");
		if (textVal != ""){
			textVal = $(this).val().toLowerCase();
			$("#userWall .listName").each(function() {
				var listVal = $(this).text().toLowerCase();
				if (listVal.indexOf(textVal) == -1){
					$(this).parent("li").hide();
					$("#moreUserBtn").hide();
				}
			});
		}
	});
	
	// Hides all users after 50
	$("#userWall .mediumList li:gt(49)").hide();
	
	// If the user list is greater than 49, then append #moreUserBtn
	if ($("#userWall .mediumList li").length > 49){
		$("<li><a id='moreUserBtn' href='#'>Show All...</a></li>").appendTo("#userWall .mediumList");
	}
	
	// When user clicks #moreUserBtn, all users will be displayed
	$("#moreUserBtn").live('click',function(){
		$(this).hide();
		$("#userWall .mediumList li").show();
		return false;
	});
}

/// All functions relevant to rightContainer are placed here
function rightContainer(){
	// When user hovers over #rightContainer img, a mini tooltip occurs
	$("#rightContainer i, #rightContainer img, #signupGroup img").hover(
		function(){
			var xPos	= $(this).offset().left;
			var yPos 	= $(this).offset().top;
			var listVal = ($(this).attr("class")) ? $(this).attr("class") : $(this).attr("alt");
			
			if ($(this).parents("ul.smallList").length == 1){
				$("#sideTip").appendTo("body").css({top: yPos + 1 + "px", left: xPos + 36 + "px"}).text(listVal).show();
			} else{
				$("#sideTip").appendTo("body").css({top: yPos + 1 + "px", left: xPos + 34 + "px"}).text(listVal).show();} },
		function(){
			$("#sideTip").appendTo("#container").hide();
	});
}

// All functions relevant to mods are placed here
function mod(){
	reportMod();
	feedbackMod();
	
	// All functions relevant to reportMod are placed here
	function reportMod(){
		// Toggles #reportMod textarea value and class
		$("#reportMod [name='comment']").focus(function(){
			var textVal = $(this).val();
			if (textVal.indexOf("Please tell us what the problem is") > -1){
				$(this).removeClass("inactiveText");
				$(this).val("");
			}
		});
		$("#reportMod [name='comment']").blur(function(){
			var textVal = $(this).val();
			if (textVal == ''){
				$(this).val("Please tell us what the problem is").addClass("inactiveText");
			}
		});
		
		// When #reportForm is submitted send the appropriate request
		$("#reportForm").submit(function(){
			var formInfo = $(this).serialize();
			var textVal  = $(this).find("[name='comment']").val();
			errorMsg = '';
			
			if ((textVal == "") || (textVal.indexOf("Please tell us what the problem is") > -1)){
				errorMsg = "Please fill in a message";
			}
			
			// Depending on whether it is a feedReport or msgReport, perform the appropriate actions and post request
			if (errorMsg == ''){
				if ($(".feedReport").length == 1){
					var noti = $("<li class='notification'>Event reported</li>");
					formInfo = formInfo + "&reportEvent=true";
					$(".feedReport").replaceWith(noti);
					$("#reportForm").find("[name='eventId']").removeAttr("value");
					$.post("ajax/ajax.feed.php", formInfo);
				} else if ($(".msgReport").length == 1){
					var noti   = $("<div class='notification'>Message reported</div>");
					formInfo = formInfo + "&reportMsg=true";
					$(".msgReport").replaceWith(noti);
					$("#reportForm").find("[name='msgId']").removeAttr("value");
					$.post("ajax/ajax.inbox.php",formInfo);
				}
				$("#reportMod").find(".noti").text("Report sent. Thank you for the heads up!").show();
				$("#reportForm").hide();
			} else{
				$("#reportMod").find(".noti").text(errorMsg).show();
			}
			return false;
		});
		
		// When "#reportMod .delete is clicked remove the hidden attributes and reset the mod and remove the .feedReport/.msgReport hooks
		$("#reportMod .delete").click(function(){
			$("#reportMod").find(".noti").hide();
			$("#reportForm [name='comment']").val("Please tell us what the problem is").addClass("inactiveText");
			$("#reportForm").show();
			$(".feedReport").removeClass("feedReport");
			$(".msgReport").removeClass("msgReport");
			$("#reportForm").find("[name='eventId']").removeAttr("value");
			$("#reportForm").find("[name='msgId']").removeAttr("value");
		});
	}
	
	// All functions relevant to #feedBack are placed here
	function feedbackMod(){
		// When user clicks #feedbackBtn, the feedbackMod display toggles
		$("#feedbackBtn").click(function(){
			if ($("#feedbackMod").css("display") == 'none'){
				var yPos = $(window).scrollTop() + 50;
				var xPos = ($(window).width() - $("#feedbackMod").outerWidth())/2;
				$("#feedbackMod").css({"top": yPos + "px", "left": xPos + 'px'}).show(); 
			} else if ($("#feedbackMod").css("display") == 'block'){
				$("#feedbackMod").hide();
			}
		});
		
		// When user focuses on name='feedback', the val empties and inactiveText is removed
		$(".feedbackText").focus(function(){
			if ($(this).hasClass("inactiveText")){
				$(this).val("").removeClass("inactiveText");
			}
		});
		
		// When user clicks .mod .delete, it will hide its parent
		$(".mod .delete").live('click',function(){
			$("#feedBackForm").show();
			$("#feedbackNoti").hide();
			$(this).parent().hide();
		});
		// When user submits the feedback form, send the appropriate request
		$("#feedBackForm").submit(function(){
			var formInfo  = $(this).serialize() + "&feedBackForm=true";
			var textVal   = $(this).find(".feedbackText").val();
			var formText  = "Please tell us if you have any ideas, suggestions, or feedback. We love to hear from you!";
			// Don't send the form if its the same as the formText
			if (textVal != "" && textVal != formText){
				$(".feedbackText").val(formText).addClass("inactiveText");
				$(this).hide();
				$.post('ajax/ajax.mail.php',formInfo);
				$("#feedbackNoti").show();
			}
			return false;
		});
	}
}


// This function checks to see if user's fb permission and sets hidden value accordingly
function checkfbPermission(){
	var clientId 	 = '258940897480780';
	// Initialize FB object
	FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
			
	// Determine if user is logged in to facebook and level of permission
	FB.getLoginStatus(function(response){
		if (response.status === 'connected'){
			// User is logged in and connected to fb app
			var accessToken = response.authResponse.accessToken;
			// Check for specific permissions
			FB.api('me/permissions?access_token=' + accessToken,function(response){
				var publishs  	= (response.data[0].publish_stream) ? 'true': 'false';
				//var publisha  = (response.data[0].publish_actions) ? 'true': 'false';
				var readStream 	= (response.data[0].read_stream) ? 'true': 'false';
				var rsvp 	  	= (response.data[0].rsvp_event) ? 'true': 'false';
				var readFriend 	= (response.data[0].read_friendlists) ? 'true': 'false';
				var offline   	= (response.data[0].offline_access) ? 'true': 'false';
				$("[name='publishs']").val(publishs);
				$("[name='readStream']").val(readStream);
				$("[name='rsvp']").val(rsvp);
				$("[name='readFriend']").val(offline);
				$("[name='offline']").val(offline);
			});
		} else if (response.status === 'not_authorized'){
			// User is logged in to Facebook, but not connected to the app
		} else{
			// User isn't even logged in to Facebook.
		}
	});	
}