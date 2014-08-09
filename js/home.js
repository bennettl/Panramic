// JavaScript Document
$(document).ready(function(){
	sideNav();
	// Load the userfeed, grab info from facebook
	//$("#loading").show();
	feedContainer();
	infiniteScroll();
	checkfbPermission();
});

/* --- Functions List --- */
// Creates an overlay that appends to <li> when user mouses over the list and hides both the overlay and <li> when uesr clicks .deletebox
function listOverlay(parent,overlayName){
	$(parent + " .mediumList li img").live('mouseenter',function(){
		var currentList = $(this).parent();
		var xPos		= $(this).position().left;
		var yPos= $(this).position().top;
		$(overlayName).css({top: yPos, left: xPos, display: "block"}).appendTo(currentList);
	});
	$(overlayName).live('mouseleave',function(){
		$(this).hide();
	});
}

// If there are no events shown, notifiy the user, depending on whether the filtering system is on/off, we will display the appropriate message
function feedNoti(){
	if ($("#feedList > li:visible").length > 0){
		$("#feedNoti").hide();
	} else if ($("#filterOn.current").length == 1){
		$("#feedNoti").text("There are currently no matched events").fadeIn(500);
	} else {
		$("#feedNoti").text("There are currently no new events").fadeIn(500);
	}
}

// Check to see if there are filterboxes and hide the corresponding #feeList <li>
function filterPersist(){
	if ($("#filterOn.current").length == 1){
		$("#feedList > li").hide();
		$("#sidenavBottom .miniList > li").has("div.filterBox:not(:visible)").each(function(){
			var listType  = $(this).parents("ul").attr("id");
			var id        = parseInt($(this).attr("value"), 8);
			switch(listType){
				case "networkList":
					$("#feedList > li.network" + id).show();
					break;
				case "fieldList":
					$("#feedList > li.field" + id).show();
					break;
				case "groupList":
					$("#feedList > li.group" + id).show();
					break;
				default:
					break;
			}
		});
	}
}
// Checks to see which require fields are empty and display an error message accordingly
function emptyValidation(requireArray, formType){
	for (var i=0; i < requireArray.length ; i++){
		var requireField = requireArray[i];
		var fieldVal     = $("[name='" + requireField + "']").val().replace(/\s/g,"");
		if (fieldVal == ""){
			switch(formType){
				case "event":
					errorMsg = "Please enter the event " + requireField;
					break;
				case "group":
					errorMsg = "Please enter your group " + requireField;
					break;
				default:
					errorMsg = "Please fill in the require fields";
					break;
			}
		}
	}
}
// All functions relevant to #feedContainer are placed here
function feedContainer(){
	navCurrent("#timeline", "#timeline a");
	feedToggle();
	filterPersist();
	feedNoti();
	deleteHover(".feed");
	// Depending on which #timeline <a> and the feedType the user clicks, send the corresponding request, readjust all description height, and perform a fadeIn transition
	$("#timeline a").click(function(){
		var timeline     = $(this).attr("id");
		var feedType     = $("#feedList").attr("class");
		var info         = {template: true, filebase: 'home', file: 'home.'+ feedType +'.php'};
		// Check if user is in facebook app and pass that as a parameter
		info['facebook'] = ($(this).attr("href").indexOf("facebook") > -1) ? true : false;
		info[timeline]   = true;
		if ($("#foodOn.current").length == 1){
			info['freefood'] = true;
		}
		
		// If the friendTip and delete tip isn't append to #leftContainer when the new page loads, then it will be lost
		$("#friendTip").appendTo("#leftContainer").hide();
		$("#deleteTip").appendTo("#leftContainer").hide();
		$("#loading").show();

		$.post(templateFile,info,function(data){
			var newFeedList = $(data).find("#feedList");
			$("#loading").hide();
			$("#pageList").remove();
			$("#feedList").replaceWith(newFeedList);
			$("#feedList").hide().fadeIn(500);
			feedToggle();
			filterPersist();
			feedNoti();

			window.addthis.toolbox(".addthis_toolbox"); // refresh addthis toolbox
			// Handle pageList for myCal
			if (timeline == "myCal"){
				var newPageList = $(data).find("#pageList");
				newPageList.insertBefore("#feedList");
				$("#pageList").hide().fadeIn(500);
				pageList();
			}
		},'html');
	});
}
// Find the difference between macCount and textLength and perform the appropriate action
function textCounter(element){
	var maxCount = 2000;
	var textLength = $(element).val().length;
	var textDiff = maxCount - textLength;
	$(element).parents("table").find(".tcCount").text(textDiff);

	$(element).keydown(function(evt){
		var keyType = evt.which;
		var textLength = $(element).val().length;
		var textDiff = maxCount - textLength;
		if ((textLength >= maxCount) && (keyType != 8)){
			return false;
		}
		// Update the tcCount for each keypress
		$(element).parents("table").find(".tcCount").text(textDiff);
	});
}
// This adjusts the height of #formContainer .descriptionText base on character length
function adjustHeight(textbox){
	var textarea = $(textbox);
	var textHeight = textarea.scrollTop("9999").scrollTop() + 60;
	textarea.css("height",textHeight);
}
// Automcplete function. Depending on which keystrokes user enters, it will perform the corresponding function
function searchSuggest(evt,inputField){
	switch(evt.which){
		case 38:
			moveSelect(-1);
			break;
		case 40:
			moveSelect(1);
			break;
		case 13:
			enterSelect();
			break;
		case 8:
			deleteSelect();
			break;
		default:
			processKey();
			break;
	}
	// Clicking an <li> is the same as hitting enter. They both call the enterSelect()
	$("#searchList li").live('click',function(){
		enterSelect();
	});

	// When user mouses over the list, remove previous searchSelect class and add the class to the <li> being mouseover
	$("#searchList li").live('mouseover',function(){
		$(this).siblings("[class='searchSelect']").removeClass("searchSelect");
		$(this).addClass("searchSelect");
	});
	
	// Loop through <li> in searchList, if there is an indexOf == -1 with searchVal, then hide the <li>. Add .searchSelect to first visible <li>
	function processKey(){
		var allLists  = $("#searchList li");
		var searchVal = inputField.val().toLowerCase();

		// Show all #searchList <li> each keyup
		$("#searchList, #searchList li").show();
		$("#searchList li.searchSelect").removeClass();
		
		allLists.each(function(){
			if ($(this).text().toLowerCase().indexOf(searchVal) == -1){
				$(this).hide();
			}
		});
		// Position the searchList base on othe top and left offset of the inputField
		var inputTopPos  = parseInt(inputField.position().top, 8);
		var inputLeftPos = Math.ceil(parseInt(inputField.position().left,8));

		$("#searchList").css({'top': inputTopPos + 26 + "px", 'left': inputLeftPos + "px"});
		$("#searchList li:visible:first").addClass("searchSelect");
	}
	
	// Get the current number of the <li> with .searchSelect and increment/decrement and apply .searchSelect to the new <li>. Note: Only deal with visible elements
	function moveSelect(unitNum){
		var allLists = $("#searchList li:visible");

		allLists.each(function(index){
			if ($(this).hasClass("searchSelect")){
				$(this).removeClass();
				currentCount = index + unitNum;
			}
		});
		
		switch(currentCount){
			case -1:
				currentCount = parseInt(allLists.length,8) -1;
				$("#searchList li:visible:last").addClass("searchSelect");
				break;
			case parseInt(allLists.length,8):
				currentCount = 0;
				$("#searchList li:visible:first").addClass("searchSelect");
				break;
			default:
				$("#searchList li:visible:eq(" + currentCount + ")").addClass("searchSelect");
		}
	}
	
	// Find the <li> with className of .searchSelect. Then place its text and value. Note: Only deal with visible <li>
	function enterSelect(evt){
		var allLists = $("#searchList li:visible");
		allLists.each(function(){
			if ($(this).hasClass("searchSelect")){
				inputField.val($(this).text());
				$("#" + inputField.attr("name")).val($(this).attr("value"));
				$("#searchList").hide();
			}
		});
	}
	
	// If user hits delete and nothing is on the field, then remove the hidden input's value, else proces the key
	function deleteSelect(){
		if (inputField.val() == ""){
			$("#" + inputField.attr("name")).removeAttr("value");
			$("#searchList").hide();
		} else{
			processKey();
		}
	}
}

// Vars for infinite scroll
var scrollFlag = true;
var pageNum    = 1;

// When user scrolls to bottom, make an ajax request
function infiniteScroll(){
	if ($("#feedList li").length === 0){
		scrollFlag = false;
	}
	// Hide show more if there is less than 10 feed
	if ($(".feed").length > 9){
		$("#showMore").show();
	}
	$(window).scroll(function(){
		// Once user reaches the bottom
		if (scrollFlag && $(window).scrollTop() >= $(document).height() - $(window).height() - 10){
			var tabId  = $("#sidenavTop .current").attr("id").substring(4);
			
			if (tabId == 'userFeed' || tabId == 'friendFeed' || tabId == 'everyFeed'){
				pageNum++;
				if (pageNum > 3){
					scrollFlag = false;
					$("#showMore").hide();
					return;
				}
				var info   = {template: true, filebase: 'home', file : 'home.' + tabId + '.php', 'pageNum': pageNum};
				$.post(templateFile,info,function(data){
					// If there is data, append it, else hide showMore and set scrollFlag to false
					if (data != ''){
						$("#feedList").append(data);
						$("#feedList").append($("#showMore"));
						feedContainer();
						window.addthis.toolbox(".addthis_toolbox"); // refresh addthis toolbox
					} else {
						scrollFlag = false;
						$("#showMore").hide();
					}
				});
			}
		}
	});
}

// All relevant functions to groupSubmit are here
function groupSubmit(){
	$(".guestListContainer > div:not(':first')").hide();
	textExpand("[name='description']");
	fileInput();
	$(".list").eq(1).click(function() {
        if (!$(this).find(":checkbox").attr("checked")){
			$("[name='venue']").val("USC");
			$("[name='street']").val("N/A");
			$("[name='locality']").val("Los Angeles");
			$("[name='postal']").val("90089");
		} else{
			$("[name='venue']").val("");
			$("[name='street']").val("");
			$("[name='locality']").val("");
			$("[name='postal']").val("");
		}
    });
	

	// When #groupSubmitForm is submitted, validate then send a post request and notification
	$("#groupSubmitForm").submit(function(){	
		var requireArray  = new Array('name','venue','locality','postal','description');
		var fileVal       = $("#groupSubmitForm .inputFile").val();
		var fileText      = $("#groupSubmitForm .fileText").val();
		errorMsg          = '';
		emptyValidation(requireArray,"group");
		
		// Validation check
		if ($("input[name='name']").val().length > 40){
			errorMsg = "Group name can't be longer than 40 characters";
		}
		
		if ($("input[name='network']:checked").length == 0){
			errorMsg = "Please select one network your group belongs to";
		} else if ($("input[name='field']:checked").length == 0){
			errorMsg = "Please select one field your group belongs to";		
		} else if  ($("input[name='network']:checked").length > 1){
			errorMsg = "Please select only one network your group belongs to";
		} else if ($("input[name='field']:checked").length > 1){
			errorMsg = "Please select only one field your group belongs to";		
		}
		
		// If validateEmail has a return value, then there is an errorMsg
		if (validateEmail($("[name='email']"))){
			errorMsg = validateEmail($("[name='email']"));
		}
		// If validateFile has a return value, then there is an errorMsg
		if (validateFile(fileVal)){
			errorMsg = validateFile(fileVal);
		}
		if (fileText == ""){
			errorMsg = "Please select your main group photo";
		}
		
		// If there are no errors, process the form, else display the errorMsg
		if (errorMsg == ""){
			$("#groupSubmitFrame").load(function(){
				var success = $("#groupSubmitFrame").contents().find("div#success").text();
				var message = $("#groupSubmitFrame").contents().find("div#message").text();
				$("#groupSubmitNoti").text(message).show();
				
				if (success == "yes"){
					$("#groupSubmitForm").hide();
					$("#groupSubmitNoti2").text("Thank you for submitting your group! We will confirm your submission shortly.");
				}
			});
		} else{
			$("#groupSubmitNoti").text(errorMsg).show();
			return false;
		}
	});
}
				
// All functions related to sideNav are placed here
function sideNav(){
	/* --- #sidenavTop --- */
	navCurrent("#sidenavTop", "#sidenavTop li a");

	// When user clicks #sidenvvTop <a>, all <div> will be hidden except for the <div> with the id that maches the <a> href attribute
	$("#sidenavTop a").click(function(){
		var tabId  = $(this).attr("id").substring(4);
		var info   = {template: true, filebase: 'home', file : ''};
		
		// If the friendTip and delete tip isn't append to #contentContainer when the new page loads, then it will be lost
		$("#friendTip").appendTo("#leftContainer").hide();
		$("#deleteTip").appendTo("#leftContainer").hide();
		$("#contentContainer").hide();
		$("#feedNoti").hide();
		$("#loading").show();
		
		// Hide all .mod
		$(".mod").hide();
		// Set the free food filter to off
		if ($("#foodOn.current").length == 1){
			$("#foodOn").removeClass("current");
			$("#foodOff").addClass("current");
		}
		// Hide firstImg (the tutorial) if it exists
		if ($(".firstImg").length == 2){
			$(".firstImg").hide();
		}
		
		switch (tabId){
			case "userFeed":
			case "friendFeed":
			case "everyFeed":
				info['file'] =  "home." + tabId + ".php";
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
					feedContainer();
				});
				scrollFlag = true; // for infinite scrolling
				break;
			case "groupBuffer1":
			case "groupBuffer2":
			case "groupBuffer3":
				info['file'] =  "home.groupBuffer.php";
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
					groupBuffer();
					$("#filterOff").click();
				});
				scrollFlag = false; // for infinite scrolling
				break;
			default:
				info['file'] =  "home." + tabId + ".php";
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
					window[tabId]();
					$("#filterOff").click();
				});
				scrollFlag = false; // for infinite scrolling
				break;
		}
	});
	
	/* --- #sidenavBottom --- */
	
	// Toggles mini tooltip when user hovers over #sidenavBottom .filterBox
	$("#sidenavBottom .filterBox ").live('mouseover',function(){
		var listVal = ($(this).siblings("i").length == 1) ? $(this).siblings("i").attr("class") : $(this).siblings("a").find("img").attr("alt");
		var xPos	= $(this).offset().left;
		var yPos	= $(this).offset().top;
		$("#sideTip").appendTo("body").css({top: yPos + 1 + "px", left: xPos + 34 + "px"}).text(listVal);
		$("#sideTip").show();
	});
	$("#sidenavBottom .filterBox ").live('mouseout',function(){
		$("#sideTip").appendTo("#container").hide();
	});

	// When user clicks #filterOn, appends .filterbox to each <li> and hide all #feedList <li>. Only display feedNoti if user looking at the feed.
	$("#filterOn").click(function(){
		if ($("#tab_userFeed.current").length == 1 || $("#tab_friendFeed.current").length == 1 || $("#tab_everyFeed.current").length == 1 ){
			if ($(".filterBox").length == 0){
				$("#sidenavBottom li").each(function() {
					$("<div class='filterBox'></div>").appendTo($(this));
				});
			}
			$("#feedList > li").hide();
			$(".miniList > li i").css("cursor","pointer");
			$("#filterOff").removeClass("current");
			$(this).addClass("current");
			feedNoti();
		}
	});
	
	// When user clicks #filterOff, remove all filter box and show all <li>. Only display feedNoti if user looking at the feed.
	$("#filterOff").click(function(){
		$(".filterBox").remove();
		$("#feedList > li").show();
		$(".miniList > li i").css("cursor","default");
		$("#filterOn").removeClass("current");
		$(this).addClass("current");
		if ($("#tab_userFeed.current").length == 1 || $("#tab_friendFreed.current").length == 1 || $("#tab_everyFeed.current").length == 1 ){
			feedNoti();
		}
	});
	
	// Removes each .filterBox when user clicks it and show the corresponding feed
	$(".filterBox").live('click',function(){
		var listType  = $(this).parents("ul").attr("id");
		var id 		  = parseInt($(this).parent("li").attr("value"));
		switch(listType){
			case "networkList":
				$("#feedList > li.network" + id ).fadeIn(500);
				break;
			case "fieldList":
				$("#feedList > li.field" + id ).fadeIn(500);
				break;
			case "groupList":
				$("#feedList > li.group" + id ).fadeIn(500);
				break;
			default:
				break;
		}
		$(this).hide();
		feedNoti();
	});
	
	// If user clicks on image and #filterOn has current class, then hide the corresponding feed
	$(".miniList > li > i").click(function(){
		if ($("#filterOn.current").length == 1){
			var listType  = $(this).parents("ul").attr("id");
			var id        = $(this).parent("li").attr("value");
		
			switch(listType){
				case "networkList":
					$("#feedList > li.network" + id ).hide();
					break;
				case "fieldList":
					$("#feedList > li.field" + id ).hide();
					break;
				default:
					break;
			}
			$(this).siblings(".filterBox").show();
		}
		feedNoti();
	});
	
	// When #foodOn is click, go to everyFeed and send in freeFood as a key
	$("#foodOn").click(function(){
		var info = {template: true, filebase: 'home', file: 'home.everyFeed.php', freeFood: true};
		$("#sidenavTop a.current").removeClass("current");
		$("#sidenavTop #tab_everyFeed").addClass("current");
		$("#contentContainer").load(templateFile,info,function(){
			feedContainer();
		}).hide().fadeIn(500);
		$("#foodOff").removeClass("current");
		$(this).addClass("current");
	});
	
	// When #foodOff is click, go to everyFeed as usual
	$("#foodOff").click(function(){
		if ($(".everyFeed").length == 1){
			$("#tab_everyFeed").click();
			$("#foodOn").removeClass("current");
			$(this).addClass("current");
		}
	});
}