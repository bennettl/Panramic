// JavaScript Document
function groupSel(){
/* --- Autcomplete --- */
	// On each keyup perform a search suggest
	$("#groupSel input[name='group']").keyup(function(evt){
		var inputField = $(this);
		searchSuggest(evt,inputField);
	});
	// When #groupSelForm is submitted, vaidate form, send the post request, and notify user
	$("#groupSelForm").submit(function(){
		var groupId = $("#group").val();
		var message;
		message = "Administrator priveleges granted";
		$("input[name='group']").val("");
		$("#group").val("");
		$.post("ajax/ajax.groupSel.php",{"groupSel":"true","groupId":groupId});
		$("#groupSel .noti").text(message).show();
		return false;
	});
}

// All functions relevant to #groupReq are here
function groupReq(){
	listOverlay("#groupReq", "#confirmBox");
	$(".guestListContainer > div:not(':first')").hide();

	// If there are no member requests notify the user
	if ($("#groupReq .mediumList > li").length == 0){
		$("#groupReq #groupNoti").show();
	}
	
	// When #groupEditForm is submitted, we send post reset and reset the form
	$("#groupEditForm").submit(function(){
		var formInfo = $(this).serialize() + "&editGroup=true";
		$.post("ajax/ajax.groupReq.php", formInfo);
		$("#groupEditForm #resetForm").click();
		$("#groupEditForm [name='groupId']").removeAttr("value");
		$("#groupEditForm #groupName").text("");
		return false;
	});
	
	// If the user clicks .confirmBtn, will replace <li> with a notification. need to append #confirmBox or else it will disappear and call a post request
	$("#groupReq .confirmBtn").click(function(){
		var noti    = $("<li><div class='listNote'>Group Confirmed</div></li>");
		var groupId = $(this).parents("li").attr("value");
		
		$(this).parents("li").addClass("selected");
		$(this).parents("#confirmBox").appendTo("#groupReq").hide();
		
		$("#groupReq .selected").replaceWith(noti);
		$.post('ajax/ajax.groupReq.php',{"confirmGroup":"true","groupId":groupId});
	});
	
	// If the user clicks #groupReq .deleteBox, will hide the parent elements and call a post request
	$("#groupReq .deleteBox").click(function(){
		var groupId = $(this).parents("li").attr("value");
		$(this).parents("li").hide();
		$(this).parent().hide();
		$.post('ajax/ajax.groupReq.php',{"ignoreGroup":"true","groupId":groupId});
	});
	
	// When #groupReq editBtn is clicked, we fade in #groupEditFrom and pass in the groupId to the form
	$("#groupReq .editBtn").click(function(){
		var groupId   = $(this).parents("li").attr("value");
		var groupName = $(this).siblings(".listName").text();
		$("#groupEditForm").fadeIn(500);
		$("#groupEditForm [name='groupId']").val(groupId);
		$("#groupName").text(groupName).hide().fadeIn(500);
		return false;
	});
}

// All functions relevant to #eventReq are here
function eventReq(){
	$("[name='startDate'], [name='endDate']").live('focus',function(){ $(this).datepicker(); });

	listOverlay("#eventReq", "#deleteList");
	// Notify the user if there are no events
	if ($("#evtList li").length == 0){
		$("#eventReq .pageNoti").show();
	}
	
	// When #eventReq .editEvtBtn is clicked, clone the corresponding .formList and place it in #formContainer. Insert the guest list container into cell and show it
	$("#eventReq .miniEvt").live('click',function(){
		var guestContainer = $("#guestContainer");
		var formList       = $(this).next(".formList").clone(true,true);
		formList.find(".guestContainerCell").html(guestContainer);
		$("#deleteList").hide();
		$("#eventNoti").hide();
		$("#formContainer").show();
		$("#formContainer > li").replaceWith(formList);
		formList.fadeIn(500);
		guestContainer.show();
		adjustHeight("#formContainer [name='description']");
		textExpand("[name='description']");
		textCounter("[name='description']");
		$("#formContainer .guestListContainer > div:not(':first')").hide();
		return false;
	});
	
	// If #eventReq .deleteBox is clicked, then will show #notiMod as a confirmation module. add .selected as a hook to know which .event the user is referring to
	$("#eventReq .deleteBox").live('click',function(evt){
		var yPos  = $(window).scrollTop() + 50;
		var noti  = "Are you sure you want to remove this event?";
		
		evt.stopPropagation();
		$(this).parents(".miniEvt").addClass("selected");
		$("#notiText").text(noti);
		$("#notiMod").addClass("removeEvt").css({"top": yPos + "px"}).show();
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
	
	// If confirmEvent is clicked, then we send the appropriate request
	$("#formContainer [name='confirmEvent']").live('click',function(){
		var noti    = $("<li><div class='listNote'>Event Confirmed</div></li>");
		var eventId = parseInt($("#formContainer ,evtForm").find("input[name='eventId']").val());
		var info    = {"confirmEvent":"true","eventId":eventId};
		$.post('ajax/ajax.eventReq.php', info);
		$(".mediumList li[value='" + eventId + "']").replaceWith(noti);
		$("#formContainer").hide();
		return false;
	});
	
	// If updateEvent is clicked, then we send the appropriate request
	$("#formContainer [name='updateEventReq']").live('click',function(){
		var evtForm       = $("#formContainer .evtForm");
		var evtName       = evtForm.find("input[name='name']").val();
		var requireArray  = new Array('name','venue','description');
		errorMsg          = '';
	
		// Validate base on empty fields, length, ongoing/start date, and file extensions on uploading file
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
		if (parseInt($("[name='rsvpTime']").val()) != -1 && $("input[name='rsvpDate']").val() == ""){
			errorMsg ="Please select an rsvp date";
		}
		if (evtForm.find("input[name='ongoing']:checked").val() == "no" && evtForm.find("input[name='startDate']").val() == ""){
			errorMsg = "Please select a start date";
		}
		
		//if there are no erros proceed, else display error message
		if (errorMsg == ""){
			var formInfo = evtForm.serialize() + "&updateEventReq=true"
			$("#eventNoti").text("Processing...").show();
			$.post('ajax/ajax.eventReq.php',formInfo,function(data){
				var response = data.response;
				$("#eventNoti").text(response).show();
				if (response.indexOf("success") > -1){
					$(".miniEvt[value='" + eventId + "'] .listName").text(evtName);
					var evtForm  = $("#formContainer .formList").clone(true,true).hide();
					// Reset the description height for the adjustHeight function
					$(".mediumList li[value='" + eventId + "']").next("li.formList").replaceWith(evtForm);
					$(".mediumList li[value='" + eventId + "']").next("li.formList").find("[name='description']").css("height","40px");
				}
			},'json');
			return false;
		} else{
			$("#eventNoti").text(errorMsg).show();
			return false;	
		}
	});
}

// All functions related to masse are placed here
function masse(){
	textExpand("[name='message']");
	// When #newMsgForm is submitted, check to see if msgTo and msgContent is empty, if not, send a post request.
	$("#masseForm").submit(function(){
		errorMsg = '';
		var requireArray = new Array('subject','message','password');
		emptyValidation(requireArray,"none");
		
		if (errorMsg == ""){
			var msgInfo = $(this).serialize()  + "&masse=true";
			$("#masseNoti").text("Processing...").show();
			$.post('ajax/ajax.masse.php',msgInfo,function(data){
				var response = data.response;
				$("#masseNoti").text(response).show();
				if (response.indexOf("success") > -1){
					$("#masseReset").click();
				}
			},'json');
		} else{
			$("#masseNoti").text(errorMsg).show();
		}
		return false;
	});
}

// All functions related to report are placed here
function report(){
	// If there are no new reports notify the user
	$(".reportSection").each(function() {
		if ($(this).find("li").length == 0){
			$(this).find(".pageNoti").show();
		}
    });
	
	// When user clicks .commentSubmit, see if the message value is empty, if it isnt, then both create a template and send a post request
	$("#report .reportForm").submit(function(){
		var formType   = $(this).attr("name");
		var formInfo   = $(this).serialize() + "&" + formType + "=true";
		var noti       = $("<li class='notification'>Report handled</li>");
		var errorMsg   = '';
		
		// Validation
		var allBoxes   = $(this).children(":checked").length;
		var ignoreBox  = $(this).children("[name='ignore']:checked").length;
		var removeBox  = $(this).children("[name='remove']:checked").length;
		var warnBox    = $(this).children("[name='warn']:checked").length;
		var banBox     = $(this).children("[name='ban']:checked").length;
		
		if (allBoxes == 0){
			errorMsg = "Please select an action";
		}
		if (ignoreBox == 1 && allBoxes > 1){
			errorMsg = "In order to ignore, you must uncheck other fields";
		}
		if (removeBox == 0 && (warnBox == 1 || banBox == 1)){
			errorMsg = "Please check remove as well";
		}
		if (removeBox == 1 && warnBox == 0 && banBox == 0){
			errorMsg = "Please check warn or ban";
		}
		if (warnBox == 1 && banBox == 1){
			errorMsg = "Can't check both warn and ban";
		}
		
		if (errorMsg == ''){
			$.post('ajax/ajax.report.php',formInfo);
			$(this).parents("li.report").replaceWith(noti);
		} else{
			$(this).next(".noti").text(errorMsg).show();
		}
		return false;
	});
}