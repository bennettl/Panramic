// All functions relevant to administrators and officers are placed here
// All functions relevant to #groupBuffer are placed here
function groupBuffer(){
	$("#groupBuffer .mediumList li").click(function(){
		var groupId  = $(this).attr("value");
		var tabId    = $("#sidenavTop a.current").attr("href").substring(1);
		var info  = {template: true, filebase: 'home', file: "home." + tabId + ".php", "groupId": groupId};
		$("#loading").show();
		$("#contentContainer").load(templateFile,info,function(){
			$("#loading").hide();
			window[tabId]();
		}).hide().fadeIn(500);
	});
}

// All functions relevant to postEvent are here
function postEvent(){
	$(".guestListContainer > div:not(':first')").hide();
	$("[name='rsvpDate'],[name='startDate'], [name='endDate']").live('focus',function(){ $(this).datepicker();});
	textExpand("[name='description']");
	textCounter("[name='description']");
	toggleText(".fbText", "Paste Facebook Event Url");
	
	// Depending on which #pushContainer a is clicked, toggle the appropriate input and style
	$(".pushStatus").toggle(
		function(){
			var href = $(this).attr("href");
			$(href).show();
			$(this).addClass("current");
			return false; },
		function(){
			var href = $(this).attr("href");
			$(href).hide();
			$(this).removeClass("current");
			return false;
	});
	
	// Check the status of facebook and google if user wants to push them
	checkfbChange();
	checkGoogleChange();
	
	// When user pastes .fbText, request the event information from facebook
	$(".fbText").keyup(function(){
		var fburl    = $(".fbText").val();
		fburl 		 = fburl.substr(0,fburl.length - 1);
		var andLoc   = fburl.indexOf("/?");
		var eidLoc	 = fburl.indexOf("events/") + 7;
		var eventId  = (andLoc == -1) ? fburl.substr(eidLoc) : fburl.substr(eidLoc, andLoc - eidLoc);
		// Validate that it is a facebook url
		if (fburl.indexOf("www.facebook.com/") > -1){
			// Initialize fb object and send a post request
			FB.init({appId:'258940897480780', status: true, cookie: true, xfbml: true});
			FB.api('/' + eventId, function(data){
				var name        = data.name;
				var location    = data.location;
				var description = data.description;
				var startdate   = data.start_time.substr(5,2) + '/' + data.start_time.substr(8,2) + '/' + data.start_time.substr(0,4);
				var enddate     = data.end_time.substr(5,2) + '/' + data.end_time.substr(8,2) + '/' + data.end_time.substr(0,4);
				var starttime   = data.start_time.substr(11);
				var endtime     = data.end_time.substr(11);
				var imagePath   = 'http://graph.facebook.com/'+ eventId +'/picture?type=large';
				// Fill out form and reset .fbText
				$(".fbText").blur();
				$("[name='name']").val(name);
				$("#endTimeBtn").click();
				$("[name='startDate']").val(startdate);
				$("[name='startTime'] [value='" + starttime +"']").attr("selected","selected");
				$("[name='endDate']").val(enddate);
				$("[name='endTime'] [value='" + endtime +"']").attr("selected","selected");
				$("[name='venue']").val(location);
				$("#evtImg").attr("src",imagePath);
				$("[name='description']").val(description);
				adjustHeight("[name='description']");
				$("[name='fbImg']").val('true');
				$("[name='fbid']").val(eventId);
				$(".fbText").addClass("inactiveText").val("Paste Facebook Event Url");
				textCounter("[name='description']");
			});
		}
    });
	
	// When user clicks #endTimeBtn, show #endDateRow and insert startDateVal on it
	$("#endTimeBtn").click(function(){
		var startDateVal = $("[name='startDate']").val();
		$("#endDateRow").show();
		$("[name='endDate']").val(startDateVal);
		$(this).remove();
		return false;
	});
	
	// When user clicks #rsvpBtn, show #rsvpDate row
	$("#rsvpBtn").click(function(){
		$("#rsvpDateRow").show();
		$(this).remove();
		return false;
	});
	
	// When user clicks previewBtn, check to see if the field is empty before moving input and submitting the form
	$("#previewBtn").click(function(){
		var fileVal   = $("#postEventForm .inputFile").val();
		errorMsg  = '';
		// If validateFile has a return value, then there is an errorMsg
		if (validateFile(fileVal)){
			errorMsg = validateFile(fileVal);
		}
		if (fileVal == ""){
			errorMsg = "Please upload an image to preview";
		}
	
		if (errorMsg == ""){
			$(".fileContainer .inputFile").appendTo($("#previewForm"));
			$(".fileContainer .noti").show();
			$("#previewForm").submit();
		} else{
			$("#eventNoti").text(errorMsg).show();
		}
        return false;
    });
	
	// When user clicks cancel btn, remove the path and restore the group image
	$(".cancelBtn").click(function() {
		var fileVal  = $(".fileContainer .inputFile").val();
		var groupId  = $("[name='groupId']").val();
		var groupImg = "images/groups/m" + groupId + ".jpg";
		$("[name='fbImg']").val("false");
		$("#evtImg").attr("src",groupImg);
    });
	fileInput();

	//  When #previewForm is subbmited, depending on whether the response has .jpg or not, perform the appropriate action
	$("#previewForm").submit(function(){
		$("#previewFrame").load(function(){
			var response = $("#previewFrame").contents().find("div#previewMsg").text();
			var file     = $("#previewForm .inputFile");
			
			$(".fileContainer .noti").hide();
			$(".fileContainer .fileText").after(file);
			if (response.indexOf(".jpg") > -1){
				// Get the relative image directory
				var num  = response.indexOf("/images");
				response = response.substring(num);
				$("#evtImg").attr("src",response);
				$("#eventNoti").hide();
			} else{
				$("#eventNoti").text(response).show();
			}
		});
	});
	// When #postEventForm is submitted, validate then wait for the response on the iframe and  display the appropriate response on #formRest. If is successful, reset the form
	$("#postEventForm").submit(function(){
		var pushFb     = $("[name='pushFb']").is(':checked');
		var fbstatus   = ($("[name='fbstatus']").val() == "true") ? true : false;
		var pushGoogle = $("[name='pushGoogle']").is(':checked');
		var gstatus    = ($("[name='gstatus']").val() == "true") ? true : false;
		var fileVal    = $("#postEventForm .inputFile").val();
		var pageTitle  = ($(".pageHd").text().indexOf("Events") > -1) ? "Settings" : "Manage Group";
		errorMsg       = '';
		// If everyWeek isset, then check to see if the month is valid
		if ($("input[name='everyWeek']:checked").length == 1){
			var startMonth = parseInt($("input[name='startDate']").val().split("/")[0],10);
			if (startMonth < 8){
				errorMsg = "Please select a month between August and December";
			}
			if ($("input[name='startDate']").val().length == 0){
				errorMsg = "Please select a start date";
			}
		}
		
		// Validation for empty fields, max length of name, if the startDate is empty, and if there are any guests
		var requireArray = new Array('name','venue','description');
		emptyValidation(requireArray,"event");

		if ($("input[name='name']").val().length > 50){
			errorMsg = "Event name can't be longer than 50 characters";
		}
		if ($("[name='description']").val().length > 2000){
			errorMsg = "Description is too long";
		}
		if ($("input[name='startDate']").val().length == 0){
			errorMsg = "Please select a start date";
		}
		if (parseInt($("[name='rsvpTime']").val()) != -1 && $("input[name='rsvpDate']").val() == ""){
			errorMsg ="Please select an rsvp date";
		}
		if ($("input[name='everyWeek']").is(':checked') && $("input[name='rsvpDate']").val() != ""){
			errorMsg = "You cannot RSVP every week events";
		}
		if ($("input[name='network']:checked").length == 0){
			errorMsg = "Please select one network";
		} else if ($("input[name='field']:checked").length == 0){
			errorMsg = "Please select one field";	
		} else if  ($("input[name='network']:checked").length > 1){
			errorMsg = "Please select only one network";
		} else if ($("input[name='field']:checked").length > 1){
			errorMsg = "Please select only one field";
		}
		
		if (pushFb && !fbstatus){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Facebook ';
		}
		if (pushGoogle && (!gstatus || $("[name='gcalid']").val() == '0')){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Google Calendar ';
		}
		// If validateFile has a return value, then there is an errorMsg
		if (validateFile(fileVal)){
			errorMsg = validateFile(fileVal);
		}
		
		// If there are no errors, proceed, else hide #fbNoti and #googleNoti and show #eventNoti
		if (errorMsg == ''){
			processForm();
		} else{
			$("#fbNoti").hide();
			$("#googleNoti").hide();
			$("#eventNoti").html(errorMsg).show();
			return false;
		}
	});
}

// This function waits for #postEventFrame to process the form and resets the form
function processForm(){
	$("#eventNoti").text("Processing...").show();
	$("#postEventFrame").load(function(){
		var message = $("#postEventFrame").contents().find("div#msg").html();
		$("#eventNoti").html(message).show();
		if (message.indexOf("success") > -1){
			var groupId  = $("[name='groupId']").val();
			var groupImg = "images/groups/m" + groupId + ".jpg";
			$("#postEventForm .descriptionText").height("40px");
			$("#formReset").click();
			$("#fbNoti").show();
			$("#googleNoti").show();
			$(".pushdiv").hide();
			$(".pushStatus.current").removeClass("current");
			$("#tab_guestNetwork").click();
			$("#evtImg").attr("src",groupImg);
		}
	});
}

// All functions relevant to #memberReq are placed here
function memberReq(){
	listOverlay("#memberReq", "#confirmBox");

	// If there are no member requests, notify the user
	if ($("#memberReq li").length == 0){
		$("#memberReq #memberNoti").show();
	}

	// If the user clicks .confirmBtn, will replace <li> with a notification. need to append #confirmBox or else it will disappear. also call a post request
	$("#memberReq .confirmBtn").click(function(){
		var noti      = $("<li><div class='listNote'>Member Confirmed</div></li>");
		var array     = $(this).parents("li").attr('class').split(' ');
		var user_id   = array[0].substr(1);
		var group_id  = array[1].substr(1);
		var member_id = array[2].substr(1);
	
		$(this).parents("li").addClass("selected");
		$(this).parents("#confirmBox").appendTo("#memberReq").hide();
		$("#memberReq .selected").replaceWith(noti);
		$.post('ajax/ajax.memberReq.php',{'confirmMember':'true', 'user_id': user_id, 'group_id': group_id, 'member_id':member_id});
	});
	
	// If the user clicks #memberReq .deleteBox, will hide the parent elements and call a post request
	$("#memberReq .deleteBox").click(function(){
		var array     = $(this).parents("li").attr('class').split(' ');
		var user_id   = array[0].substr(1);
		var group_id  = array[1].substr(1);
		var member_id = array[2].substr(1);
		$(this).parents("li").hide();
		$(this).parent().hide();
		$.post('ajax/ajax.memberReq.php',{'ignoreMember': 'true','user_id': user_id, 'group_id': group_id, 'member_id':member_id});
	});
}

// Functions relevant to #groupMange are placed here
function groupManage(){
	$("[name='rsvpDate'], [name='startDate'], [name='endDate']").live('focus',(function(){ $(this).datepicker(); }));
	
	var currentTab = $("#groupManage #tabTop a.current");
	loadgmContainer(currentTab);
	
	// When user clicks #groupManage #sidenvvTop <a>, all <div> will be hidden except for the <div> with the id that maches the <a> id attribute
	$("#groupManage #tabTop a").click(function(){
		var currentTab = $(this);
		$("#groupManage #groupContainer > div").hide();
		$("#loading").show();
		loadgmContainer(currentTab);
	});
}

// All relevant functions to aboutus are placed here
function about(){
	// Initially checks both facebook and google status, then places a .change event handler
	checkfbChange();
	checkGoogleChange();
	adjustHeight("#aboutUsForm [name='description']");
	textExpand("[name='description']");
	if ($("#aboutUsForm [name='description']").val() == ""){
		$("#aboutUsForm [name='description']").height("40px");
	}
	fileInput();
	
	// Handles calendar widget
	togglePushSite();
	toggleColorbox();
	calenderStyle();
	$("[name='pushSite']").change(function(){
		togglePushSite();
	});
	$("[name='calStyle']").change(function(){
		toggleColorbox();
		calenderStyle();
	});
	// This handles the colorboxes for widget
	handleColorPicker("bgColor", "#widgetContainer","background");
	handleColorPicker("hdBg", "#widgetContainer .tableHd","background");
	handleColorPicker("hdText", "#widgetContainer .tableHd","color");
	handleColorPicker("labelColor", "#widgetContainer .feedInfo th, #widgetContainer .listName","color");
	handleColorPicker("textColor", "#widgetContainer .feedName, #widgetContainer .feedInfo td, #widgetContainer .feedCount td, #widgetContainer .feedCount th","color");
	handleColorPicker("borderColor", "#widgetContainer .feedSideDivider","background");

	// When #aboutUsForm is submitted, send a post request and display the appropriate response on #aboutNoti
	$("#groupManage #aboutUsForm").submit(function(){
			
		var errorMsg = '';
		// Validate url input if it exists
		if ($("input[name='url']").length){
			var fieldArray = new Array('name','url','venue','locality','postal');
		} else{
			var fieldArray = new Array('name','venue','locality','postal');
		}
	
		// If validateEmail has a return value, then there is an errorMsg
		if (validateField(fieldArray)){
			errorMsg = validateField(fieldArray);
		}
		
		// If validateEmail has a return value, then there is an errorMsg
		if (validateEmail($("[name='email']"))){
			errorMsg = validateEmail($("[name='email']"));
		}
		
		if (errorMsg == ""){
			var groupInfo = $(this).serialize()  + "&aboutUsUpdate=true";
			$.post('ajax/ajax.groupManage.php',groupInfo,function(data){
				var response = data.response;
				$("#aboutNoti").text(response).show();
			},'json');
		} else{
			$("#aboutNoti").text(errorMsg).show();
		}
		return false;
	});
	
	// When #availCheck is click, send a post resquest and then display the appropriate response on #availRe
	$("#groupManage #availCheck").click(function(){
		var url     = $(this).siblings(".inputText").val();
		var groupId = $("input[name='groupId']").val();
		$.post('ajax/ajax.groupManage.php',{"availCheckGroup": "true", "url": url, "groupId": groupId}, function(data){
			var response = data.response;
			var success  = data.success;
			if (success == "yes"){
				$("#availRe").css("color","#69AC66");
			} else if (success == "no"){
				$("#availRe").css("color","#C24545");
			} else if (success == "same"){
				$("#availRe").css("color","#333333");
			}
			$("#availRe").html("<span style='color:#555555;'> | </span>" + response);
		},'json');
		return false;
	});
	
	// When #pushForm is submitted, send a post request and display the appropriate response on #pushNoti
	$("#pushForm").submit(function(){
		var groupInfo = $(this).serialize()  + "&pushUpdate=true";
		$.post('ajax/ajax.groupManage.php',groupInfo);
		$("#pushNoti").text("Push settings updated").show();
		return false;
	});

	// When #pushForm is submitted, send a post request and display the appropriate response on #pushNoti
	$("#pullForm").submit(function(){
		var groupInfo = $(this).serialize()  + "&uploadCal=true";
		$("#pullNoti").text("Processing...").show();
		$.post('ajax/ajax.groupManage.php',groupInfo,function(data){
			var message = data.message;
			$("#pullNoti").html(message);
		},'json');
		return false;
	});
	
	// When this input is focused, it will be blured
	$("#pullForm [name='email']").focus(function(){
		$(this).blur();
	});
	
	// When groupPhotoForm is submitted, wait for #photoFrame to load up and then display the the appropriate response on #updateNoti 
	$("#groupPhotoForm").submit(function(){
		errorMsg = '';
		// Validation check on file extensions of uploaded files
		$(".inputFile").each(function(){
			var fileVal = $(this).val();
			// If validateFile has a return value, then there is an errorMsg
			if (validateFile(fileVal)){
				errorMsg = validateFile(fileVal);
			}
		});

		if (errorMsg == ''){
			$("#updateNoti").text("Uploading...").show();
			$("#photoFrame").load(function(){
				var groupId  = $("input[name='groupId']").val();
				var rand 	 = new Date().getTime();
				var message  = $("#photoFrame").contents().find("div#frameMsg").text();
				$("#updateNoti").text(message).show();
				var success = ($("#updateNoti").text().indexOf("success") > -1) ? true : false;
				// Loop through each file and refresh image source for uploaded files
				$(".inputFile").each(function() {
					if ($(this).val() != ""){
						var imgType = $(this).attr("name");
						
						if (imgType == "m"){
							var imgSrc = "url(images/groups/" + imgType + groupId + ".jpg?" + rand + ") no-repeat 50% 30%";
							$("#profileImg").css("background",imgSrc);
						} else{
							var imgSrc = "images/groups/" + imgType + groupId + ".jpg?" + rand;
							$("#" + imgType).attr("src",imgSrc);
						}
						if (success){
							$(this).val("");
							$(this).prev(".fileText").val("");
						}
					}
				});
				return false;
			});
		} else{
			$("#updateNoti").text(errorMsg).show();
			return false;
		}
	});
}

// All relevant functions to events are placed here
function events(){
	listOverlay("#events", "#deleteList");
	fileInput();
	checkfbChange();
	checkGoogleChange();
	// Notify the user if there are no events
	if ($("#evtList li").length == 0){
		$("#events .pageNoti").show();
	}
	// Depending on which #evtTimeline a the the user clicks, will send the corresponding request and repalce the #evtList
	$("#evtTimeline a").click(function(){
		var timeline  = $(this).attr("id").substr(4);
		var groupId   = $(this).attr("href").substr(1);
		var feedType  = $(this).parents("#timeline").next("#feedList").attr("class");
		var info      = {template : true, filebase: 'home', file: 'home.gm.events.php', 'groupId':groupId, 'month':timeline};
		
		$("#eventNoti").hide();
		$("#notiMod").hide();
		$("#events #formContainer").hide();
		$("#loading").show();
		$("#deleteList").appendTo("#events");
		$.post(templateFile,info,function(data){
			var newFeedList = data;
			$("#evtList").replaceWith(newFeedList);
			$("#loading").hide();
			checkfbChange();
			checkGoogleChange();
			$("#evtList").hide().fadeIn(500);
			// Notify the user if there are no events
			if ($("#evtList li").length == 0){
				$("#events .pageNoti").fadeIn(500);
			} else{
				$("#events .pageNoti").hide();
			}
		},'html');
	});
	navCurrent("#evtTimeline", "#evtTimeline a");
	
	// When #events .editEvtBtn is clicked, clone the corresponding .formList and place it in #formContainer
	$("#events .miniEvt").live('click',function(){
		var formList = $(this).next(".formList").clone(true,true);
		$("#deleteList").hide();
		$("#eventNoti").hide();
		$("#notiMod").hide();
		$("#formContainer").show();
		$("#formContainer > li").replaceWith(formList);
		formList.fadeIn(500);
		adjustHeight("#formContainer [name='description']");
		
		// Deal with cross browser css issues for hidden input file
		var right = ($.browser.mozilla) ? "90px" : "25px";
		$(".fileContainer .inputFile").css("right",right);
	  
		textExpand("[name='description']");
		textCounter("[name='description']");
		return false;
	});
	
	// If .dislike.sideOptions is clicked, then  show #notiMod as a confirmation module. add .selected as a hook to know which .event the user is referring to. If it is an iframe, lower the noti 
	$("#events .deleteBox").live('click',function(evt){
		var pushFb      = $("[name='pushFb']:eq(0)").is(':checked');
		var fbstatus    = ($("[name='fbstatus']:eq(0)").val() == "true") ? true : false;
		var pushGoogle  = $("[name='pushGoogle']:eq(0)").is(':checked');
		var gstatus     = ($("[name='gstatus']:eq(0)").val() == "true") ? true : false;
		var pageTitle   = ($(".pageHd").text().indexOf("Events") > -1) ? "Settings" : "Manage Group";
		errorMsg        = '';
		evt.stopPropagation();

		if (pushFb && !fbstatus){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Facebook ';
		}
		if (pushGoogle && (!gstatus || $("[name='gcalid']:eq(0)").val() == '0')){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Google Calendar ';
		}
		
		// If there are no errors, proceed
		if (errorMsg == ''){
			var iframe 	= ($(".pageHd").text().indexOf("Events") > -1) ? true : false;
			var yPos    = $(window).scrollTop();
			yPos	   += (iframe) ? 450 : 50;
			var noti    = "Are you sure you want to remove this event?";
			
			$(this).parents(".miniEvt").addClass("selected");
			$("#notiText").text(noti);
			$("#notiMod").addClass("removeEvt").css({"top": yPos + "px"}).show();
		} else{
			$("#eventNoti").text(errorMsg).show();
		}
	});
	
	// Mini tooltip when user hovers over #tProfile img
	$(".evtForm .miniList i").live('mouseover',function(){
		var listVal = $(this).attr("class");
		var xPos    = $(this).position().left;
		var yPos    = $(this).position().top;
		$("#sideTip").appendTo("#leftContainer").css({top: yPos + 2 + "px", left: xPos + 33 + "px", display:"block"}).text(listVal);
	});
	$(".evtForm .miniList i").live('mouseout',function(){
		$("#sideTip").appendTo("#container").hide();
	});
	
	// When #groupManage .evtForm is subbmited, validate it, if it is validated, wait for evtFormFrame to load up and display the appropriate response
	$("#groupManage .evtForm").live('submit',function(){
		var evtForm       = $(this);
		var evtName       = evtForm.find("input[name='name']").val();
		var fileVal       = evtForm.find("input[name='evtImg']").val();
		var pushFb        = evtForm.find("[name='pushFb']").is(':checked');
		var fbstatus      = (evtForm.find("[name='fbstatus']").val() == "true") ? true : false;
		var pushGoogle    = $("[name='pushGoogle']").is(':checked');
		var gstatus       = (evtForm.find("[name='gstatus']").val() == "true") ? true : false;
		var requireArray  = new Array('name','venue','description');
		var pageTitle 	  = ($(".pageHd").text().indexOf("Events") > -1) ? "Settings" : "Manage Group";
		errorMsg          = '';
	
		// Validate base on empty fields, length, start date, and file extensions on uploading file
		for (var i = 0; i < requireArray.length; i++){
			var requireField = evtForm.find("[name='"+ requireArray[i] +"']").val().replace(/\s/g,"");
			if (requireField == ""){
				errorMsg = "Please enter your event " + requireArray[i];
			}
		}
		if (evtName.length > 50){
			errorMsg = "Event name can't be longer than 50 characters";
		}
		if (evtForm.find("[name='description']").val().length > 2000){
			errorMsg = "Description is too long";
		}
		if (parseInt(evtForm.find("[name='rsvpTime']").val()) != -1 && evtForm.find("input[name='rsvpDate']").val() == ""){
			errorMsg ="Please select an rsvp date";
		}
		if (evtForm.find("input[name='startDate']").val() == ""){
			errorMsg = "Please select a start date";
		}
		if (pushFb && !fbstatus){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Facebook ';
		}
		if (pushGoogle && (!gstatus || $("[name='gcalid']").val() == '0')){
			errorMsg = 'Please go to "'+ pageTitle +'" to connect or select not to push to Google Calendar ';
		}
		
		// If validateFile has a return value, then there is an errorMsg
		if (validateFile(fileVal)){
			errorMsg = validateFile(fileVal);
		}
		
		//if there are no erros proceed, else display error message
		if (errorMsg == ""){
			$("#eventNoti").text("Processing...").show();
			$("#evtFormFrame").load(function(){
				var eventId  	= $("#formContainer [name='eventId']").val();
				var fbid        = $("#evtFormFrame").contents().find("div#fbid").text();
				var gid         = $("#evtFormFrame").contents().find("div#gid").text();
				var pushFb		= $("[name='pushFb']").is(':checked');
				var pushGoogle  = $("[name='pushGoogle']").is(':checked')
				var message  	= $("#evtFormFrame").contents().find("div#frameMsg").text();
			
				// Modify mesage accordingly, if response has the word success in it
				if (message.indexOf("success") > -1){
					if (pushFb && pushGoogle){
						message += "and pushed to Facebook and Google Calendar";
					} else if (pushFb){
						message += "and pushed to Facebook";
					} else if (pushGoogle){
						message += "and pushed to Google Calendar";
					}
				}
				
				// Update both gid and fbid of normal and hidden form so user can update the correct events
				$("#formContainer [name='fbid']").val(fbid);
				$("#formContainer [name='gid']").val(gid);
				
				$("#eventNoti").text(message).show();
				$(".miniEvt[value='" + eventId + "'] .listName").text(evtName);

				// Refresh the image source for current form, the list, and hidden form if user uploaded one
				if ($("#formContainer [name='evtImg']").val() != ""){
					var rand 	 = new Date().getTime();
					var imgSrc   = "images/events/e" + eventId + ".jpg?" + rand;

					$("#formContainer .miniEvtImg").attr("src",imgSrc);	
					$(".miniEvt[value='" + eventId + "'] img").attr("src",imgSrc)
				}
				var evtForm  = $("#formContainer > li").clone(true,true).hide();
				// Reset the description height for the adjustHeight function
				$("li.miniEvt[value='" + eventId + "']").next("li.formList").replaceWith(evtForm);
				$("li.miniEvt[value='" + eventId + "']").next("li.formList").find("[name='description']").css("height","40px");
				// Empty out .inputFile and .fileText
				$(".inputFile").val("");
				$(".fileText").val("");
				return false;
			});
		} else{
			$("#eventNoti").text(errorMsg).show();
			return false;	
		}
	});
	
	$("#groupManage #recordForm").submit(function(){
        var year  = $("[name='y']").val();
        var type  = $("[name='t']").val();
		errorMsg = '';
		if (year == ''){
			errorMsg = "Please select a year";
		}
		if (type == ''){
			errorMsg = "Please select a file type";
		}
		// If there are no errors, proceed with the get request
		if (errorMsg == ""){
			$("#recordNoti").text(errorMsg).hide();		
		} else{
			$("#recordNoti").text(errorMsg).show();
			return false;	
		}
    });
}
	
// All relevant functions to members are here
function member(){
	// When user hovers over #members .smallList img, display a mini tooltip
	$("#members .smallList img").hover(
		function(){
			var listVal = $(this).attr("alt");
			var xPos    = $(this).position().left;
			var yPos    = $(this).position().top;
			$("#sideTip").appendTo("#leftContainer").css({top: yPos + 2 + "px", left: xPos + 36 + "px"}).text(listVal).show(); },
		function(){
			$("#sideTip").appendTo("#container").hide();
	});
	
	
	// On each keyyp for .memberText,  all <li> will be refresh and it will find any matches between the #guestText value and the <li> value and hide <li> if there is no match
	$(".memberText").keyup(function(){
		$("#members .guestListContainer *").show();
		var searchVal = $(this).val().replace(/\s/g,"");
		
		if (searchVal != ""){
			searchVal = $(this).val().toLowerCase();
			// If there is no match, hide the <li>
			$("#members .guestListContainer").find(".checkbox").each(function() {
				if ($(this).text().toLowerCase().indexOf(searchVal) == -1){
					$(this).parents("li").hide();
				}
			});
		}
	});	
	// Toggles focus/blur for .memberText
	toggleText(".memberText", "Find...");
	
	// Depending on which is submitted, will send the corresponding request and message
	$("#updateMember [name='removeMem']").click(function(){ updateMember("removeMem","Members removed"); });
	$("#updateMember [name='makeMem']").click(function(){   updateMember("makeMem","Statuses updated"); });
	$("#updateMember [name='makeAd']").click(function(){ 	updateMember("makeAd","Statuses updated"); });
	$("#updateMember").submit(function(){ return false; })
	// This passes the member_id and updateType for a post requests and notifies the user with a message
	function updateMember(updateType, updateMessage){
		var groupId    = $("#tab_member").attr("href").substring(1);
		var updateInfo = $("#updateMember").serialize() + '&' + updateType + '=true&groupId=' + groupId;
		$("#memberNoti").text(updateMessage).show();
		$("#updateMember input:checked").removeAttr("checked");
		$.post('ajax/ajax.groupManage.php',updateInfo);
		return false;
	}
}

// This function loads the appropriate groupMange container depending on the current tab
function loadgmContainer(tab){
	var tabId    = tab.attr("id").substring(4);
	var groupId  = tab.attr("href").substring(1);
	var info     = {template: true, filebase: 'home', "groupId": groupId, file : "home.gm." + tabId + ".php"};

	switch(tabId){
		case "about":
			$("#groupContainer").load(templateFile, info, function(){
				$("#loading").hide();
				$(this).hide().fadeIn(500);
				about();
			});
			break;
		case "postevents":
			info['file'] = 'home.postEvent.php';
			info['if']	 = true;
			$("#groupContainer").load(templateFile, info, function(){
				$("#loading").hide();
				$(this).hide().fadeIn(500);
				window.postEvent();
			});
			break;
		case "events":
			$("#groupContainer").load(templateFile, info, function(){
				$("#loading").hide();
				$(this).hide().fadeIn(500);
				events();
			});
			break;
		case "member":
			$("#groupContainer").load(templateFile, info, function(){
				$("#loading").hide();
				$(this).hide().fadeIn(500);
				member();
			});
			break;
		default:
			break;
	}
}

// This function toggles the display of #pushSite
function togglePushSite(){
	var pushSite = $("[name='pushSite']").is(":checked");
	if (pushSite){
		$("#pushSite").slideDown(1000);
	} else if ($("#pushSite").css("display") == "block"){
		$("#pushSite").slideUp(1000);
	}
}
// This function toggles the display of #colorContainer
function toggleColorbox(){
	var style = $("[name='calStyle']:checked").val();
	if (style == 'custom'){
		$("#colorContainer").slideDown(500);
	} else{
		$("#colorContainer").slideUp(500);
	}
}

// This function modifies the css property of calendar according to the style set
function calenderStyle(){
	var style = $("[name='calStyle']:checked").val();
	switch (style){
		case "light":
			var colorSet = {bgColor:"FFFFFF", hdBg:"D3DFE8", hdText:"727272", borderColor:"DEDEDE", labelColor:"616161", textColor:"333333"}
			break;
		case "dark":
			var colorSet = {bgColor:"333333", hdBg:"727272",hdText:"F4F3F3",borderColor:"DEDEDE",labelColor:"EEEDED", textColor:"EEEDED"}
			break;
		case "custom":
			var colorSet = { bgColor:$("[name ='bgColor']").val(), hdBg:$("[name ='hdBg']").val(), hdText: $("[name ='hdText']").val(), 
							 borderColor: $("[name ='borderColor']").val(), labelColor:$("[name ='labelColor']").val(),
							 textColor: $("[name ='textColor']").val()}
			break;
		default:
			break;
	}
	// Modify css properties accordingly
	$("#groupManage #widgetContainer").css("background",'#' + colorSet.bgColor);
	$("#groupManage .mediumList img, #groupManage .feedImg").css("borderColor", '#' + colorSet.bgColor);
	$("#groupManage .feed").css("borderColor",'#' + colorSet.borderColor);
	$("#groupManage .feedSideDivider").css("background","#" + colorSet.borderColor);
			
	$("#groupManage .tableHd").css("background", "#" + colorSet.hdBg);
	$("#groupManage .tableHd").css("color", '#' + colorSet.hdText);
	$("#groupManage .feedInfo th,#groupManage  .listName").css("color", '#' + colorSet.labelColor);
	$("#groupManage .feedName,#groupManage .feedInfo td, .feedCount td, .feedCount th").css("color",'#' + colorSet.textColor);				
}
// This function connects colorBox, colorpicker, hidden input, and the css property of the widget
function handleColorPicker(boxName, widgetPart, cssProperty){
	$('#' + boxName).ColorPicker({
			onBeforeShow: function () {
			var boxColor = $(this).css("backgroundColor");
			boxColor = convertHex(boxColor);
			$(this).ColorPickerSetColor(boxColor);
		},
			onShow: function (colorpick) {
			$(colorpick).fadeIn(500);
			return false;
		},
		onHide: function (colorpick) {
			$(colorpick).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			var color = '#' + hex;
			$('#' + boxName).css('background',color);
			$(widgetPart).css(cssProperty, color);
			// If boxName is background or borderColor, then change additional css properties
			if (boxName == 'bgColor'){
				$("#widgetContainer .mediumList img, #widgetContainer .feedImg").css("borderColor",color);
			} else if (boxName == 'borderColor'){
				$("#widgetContainer .feed").css("borderColor",color);		
			}
			$("[name = '"+ boxName +"']").val(hex);
		}
	});
}
// This function converts rgb(x,y,z) to a hexcolor
function convertHex(colorval) {
	var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	delete(parts[0]);
	for (var i = 1; i <= 3; ++i) {
		parts[i] = parseInt(parts[i]).toString(16);
		if (parts[i].length == 1) parts[i] = '0' + parts[i];
	}
	var color = '#' + parts.join('');
	return color;
}
		
// This function initally calls checkfbStatus if pushFb is checked, then attaches a .change handler for pushFb
function checkfbChange(){
	if ($("[name='pushFb']").is(':checked')){
		checkfbStatus();
	}
	$("[name='pushFb']").change(function(){
		if ($(this).is(':checked')){
			checkfbStatus();
		} else{
			$("#fbNoti").hide();
		}
	});
}
	
// This function checks to see if user's fb status is in correct state to post events
function checkfbStatus(){
	var clientId 	 = '258940897480780';
	var redirectUrl  = "http://www.panramic.com/";
	var scope 		 = 'create_event,offline_access';
	var authUrl		 = "https://www.facebook.com/dialog/oauth?client_id="+ clientId +"&redirect_uri="+ redirectUrl +"&scope=" + scope;
	var errorColor	 = convertHex($(".pageHd").css("color"));
	// Notify user that of processing state
	$("#fbNoti").text("Checking...").show();

	// Initialize FB object
	FB.init({appId: clientId, status: true, cookie: true, xfbml: true, oauth: true});
			
	// Determine if user is logged in to facebook and level of permission
	FB.getLoginStatus(function(response){
		var pageTitle = ($(".pageHd").text().indexOf("Events") > -1) ? "Settings" : "Manage Group";	
		if (response.status === 'connected'){
			// User is logged in and connected to fb app
			var accessToken = response.authResponse.accessToken;
			// Check for specific permissions
			FB.api('me/permissions?access_token=' + accessToken,function(response){
				var cevent   = response.data[0].create_event;
				var offline  = response.data[0].offline_access;
				// If both permissions exist, then post to event on facebook
				if (cevent && offline){
					$("[name='fbstatus']").val("true");
					var message = "Connected with Facebook";
					$("#fbNoti").html(message);
				} else{
					errorMsg  =  ($("#pushForm").length == 1 ) ? 'Click <a href="'+ authUrl +'" target="_parent"><u>here</u></a> to connect with Facebook':
																 'Please go to "'+ pageTitle +'" to connect with Facebook';
					$("[name='fbstatus']").val("false");
					$("#fbNoti").html(errorMsg).css("color",errorColor);
				}
			});
		} else if (response.status === 'not_authorized'){
			// User is logged in to Facebook, but not connected to the app
			errorMsg  = ($("#pushForm").length == 1 ) ? 'Click <a href="'+ authUrl +'" target="_parent"><u>here</u></a> to connect with Facebook':
													    'Please go to "'+ pageTitle +'" to connect with Facebook'; 
			$("[name='fbstatus']").val("false");
			$("#fbNoti").html(errorMsg).css("color",errorColor);
		} else{
			// User isn't even logged in to Facebook.
			errorMsg =  'You must be <a href="'+ authUrl +'" target="_parent"><u>logged into</u></a> Facebook to connect with Facebook';
			$("[name='fbstatus']").val("false");
			$("#fbNoti").html(errorMsg).css("color",errorColor);
		}
	});	
}

// This function initally calls checkGoogleStatus if pushFb is checked, then attaches a .change handler for pushFb
function checkGoogleChange(){
	if ($("[name='pushGoogle']").is(':checked')){
		checkGoogleStatus();
	}
	$("[name='pushGoogle']").change(function(){
		if ($(this).is(':checked')){
			checkGoogleStatus();
		} else{
			$("#googleNoti").hide();
		}
	});
}

// This function checks to see if user's google google calendar is connected
function checkGoogleStatus(){
	var email  	    = $("[name='email']").val();
	var gcalid 	    = $("[name='gcalid']").val();
	var pageTitle   = ($(".pageHd").text().indexOf("Events") > -1) ? "Settings" : "Manage Group";	
	var errorColor	= convertHex($(".pageHd").css("color"));
	var info   	    = {"checkGCal": "true", "email": email, "gcalid": gcalid, "pageTitle": pageTitle};

	// Notify user that of processing state
	$("#googleNoti").text("Checking...").show();
	
	// This will add the informtion that it pushForm exists so we do not need to direct user to Manage Group
	if ($("#pushForm").length == 1 ){
		info.pushForm = true;
	}
	// If the response is successful, change the gStatus, else change the errorColor
	$.post('ajax/api.google.php',info ,function(data){
		var message = data.message;
		var success = data.success;
		if (success){
			$("[name='gstatus']").val("true");
		} else{
			$("#googleNoti").css("color",errorColor);
		}
		$("#googleNoti").html(message);
	},'json');
}