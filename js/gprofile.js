// JavaScript Document
$(document).ready(function(){
	leftContainer();
});

/* --- Functions list --- */
// All functions relevant to leftContainer are placed here
function leftContainer(){
	addBtn();
	evtWall();
	checkfbPermission();
	// When user clicks #tabTop <a>, send the post request and display the appropriate page
	$("#bProfile #tabTop a").click(function(){
		var tabId    = $(this).attr("id").substring(4);
		var groupId  = $(this).attr("href").substring(1);
		var info     = {template: true, filebase: 'gprofile', file: "gprofile." + tabId + ".php", "groupId": groupId};
		
		// If the friendTip and delete tip isn't append to #contentContainer when the new page loads, then it will be lost
		$("#friendTip").appendTo("#leftContainer").hide();
		$("#deleteTip").appendTo("#leftContainer").hide();
		
		$("#contentContainer").hide();
		$("#loading").show();
		
		switch(tabId){
			case "evtWall":
			case "userWall":
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
					window[tabId]();
				});
				break;
			case "reviewWall":
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
					reviewWall();
					pageList();
				});
				break;
			case "infoWall":
				$("#contentContainer").load(templateFile,info,function(){
					$("#loading").hide();
					$(this).hide().fadeIn(500);
				});
				break;
			default:
				break;
		}
	});
	
	// When user hovers over #profileSideImg img, the imgs are enlarged in a tooltip
	$(".sideImg").hover(
		function(){
			var thisImg = $(this);
			$(this).addClass("waiting");
			setTimeout(function(){
		
		// If the user is still waiting
		if (thisImg.hasClass("waiting")){
			thisImg.removeClass("waiting");
			var xPos = thisImg.position().left;
			var yPos = thisImg.position().top;
			var imgWidth = thisImg.width();
			var imgHeight = thisImg.height();
			var imgSrc = thisImg.attr("src");
			
			var expandxPos = xPos - Math.ceil(xPos/2.5)
			var expandyPos = yPos;
			var expandImg = $("#photoExpand").attr("src",imgSrc);
			var expandWidth = expandImg.outerWidth();
			var expandHeight = expandImg.outerHeight();
			
			// If the original images are too small, make it larger
			if ((expandWidth < (imgWidth * 3.5)) && (expandHeight < (imgHeight * 3.5))){ 
				expandWidth  = imgWidth * 3.5;
				expandHeight = imgHeight * 3.5;
			}
			
			$("#photoOverlay").css({top: yPos + "px", left: xPos + "px", width: imgWidth, height: imgHeight}).show();
			$("#photoExpand").css({top: expandyPos + "px", left: expandxPos + "px", width: expandWidth + "px", height: expandHeight + "px"}).fadeIn(500);
		
		$("#photoOverlay").live('mousemove',function(evt) {
			var mouseX = evt.pageX;
			mouseX -= Math.ceil(expandWidth/1.5)
			$("#photoExpand").css({left: mouseX + "px"});
		});
	
		$("#photoOverlay").mouseout(function() {
			$(this).hide();
			$("#photoExpand").remove();
			$("<img id='photoExpand' />").appendTo("#leftContainer");
		});
		
		}},100); },
		function(){
			$(this).removeClass("waiting");
	});
}
/*
// All functions relevant to reviewWall are placed here
function reviewWall(){
	var defineHeight = 35 + "px";
	msgTimer();
	textExpand(".reviewText");
	textExpand(".commentText");
	fadeInSubmit(".reviewText",".reviewSubmit");
	fadeInSubmit(".commentText",".commentSubmit");
	deleteHover(".msgFeed");
	deleteHover(".msgReply");
		
	// If the natural height of the .msgBody is larger than the defineHeight, then place a limit on the height
	$(".msgBody").each(function() {
		if ($(this).height() > parseInt(defineHeight)){
			$(this).height(defineHeight);
		}
	});
	
	//  Height toggles when user clicks .moreInfo
	$(".moreInfo").toggle(
		function(){
			$(this).parents(".msgMainContainer").find(".msgBody").css("height","auto");
			$(this).parents(".msgFeed").children().show();
			$(this).text("hide");
			return false },
		function(){
			$(this).parents(".msgMainContainer").find(".msgBody").css("height",defineHeight);
			$(this).parents(".msgFeed").children(":not('.msgMainContainer,.delete')").hide();
			$(this).text("more...").show();
			return false
	});
	
	// Toggles value and class for $reviewInputContainer .reviewText
	$("#reviewInputContainer .reviewText").focus(function(){
		if ($(this).val() == "Write a review..."){
			$(this).val("").removeClass("inactiveText");
		}
	});
	$("#reviewInputContainer .reviewText").blur(function(){
		if ($(this).val() == ""){
			$(this).val("Write a review...").addClass("inactiveText");
		}
	});
	
	// When the user submits a post, check to see if its empty. If its not, template the message, append it to #reviewWall .msgFeedContainer, then send a post request
	$("#reviewWall #reviewForm").submit(function(){
		var formInfo = $(this).serialize() + "&review=true";
		var msgValue = $(this).children(".reviewText").val();
		
		if (msgValue != "Write a review..." && msgValue != ""){
			var template  = templateMsg(msgValue);
			$(this).children(".reviewText").val("").height("45px");
			template.prependTo($("#reviewWall .msgFeedContainer")).hide().fadeIn(500);
			$.post('ajax/ajax.reviewWall.php',formInfo);
		}
		return false;
	});
	
	// When user hits comment submit, see if the message value is empty, if it isnt, then both create a template and send a post request with comment as true, msgId, and replymsg
	$("#reviewWall .commentForm").submit(function(){
		var formInfo = $(this).serialize() + "&reviewReply=true";
		var msgValue = $(this).children(".commentText").val().replace(/\s/g,"");
		
		if (msgValue != ""){
			var msgValue  = $(this).children(".commentText").val();
			var template  = templateMsgReply(msgValue);
			$(this).children(".commentText").val("").height("20px");
			template.appendTo($(this).parents(".commentContainer").siblings(".msgReplyContainer")).hide().fadeIn(500);
			$.post('ajax/ajax.reviewWall.php',formInfo);
		}
		return false;
	})
	
	// When the user clicks on $reviewWall .voteYes, increment both the pVote and tVote, hide .reviewVote, and send a post request
	$("#reviewWall .voteYes").click(function(){
		var reviewId = $(this).parents(".msgFeed").attr("value");
		var pVote 	 = parseInt($(this).parent(".reviewVote").siblings(".reviewCount").children(".pVote").text()) + 1;
		var tVote 	 = parseInt($(this).parent(".reviewVote").siblings(".reviewCount").children(".tVote").text()) + 1;
		
		$(this).parent(".reviewVote").siblings(".reviewCount").children(".pVote").text(pVote);
		$(this).parent(".reviewVote").siblings(".reviewCount").children(".tVote").text(tVote);
		$(this).parent(".reviewVote").hide();
		$.post('ajax/ajax.reviewWall.php',{"pVote": "true","reviewId": reviewId});
		return false;
	});
	
	// When the user clicks on $reviewWall .voteNo, increment only the tVote, hide .reviewVote, and send a post request
	$("#reviewWall .voteNo").click(function(){
		var reviewId = $(this).parents(".msgFeed").attr("value");
		var tVote = parseInt($(this).parent(".reviewVote").siblings(".reviewCount").children(".tVote").text()) + 1;
		
		$(this).parent(".reviewVote").siblings(".reviewCount").children(".tVote").text(tVote);
		$(this).parent(".reviewVote").hide();
		$.post('ajax/ajax.reviewWall.php',{"tVote": "true" ,"reviewId": reviewId});
		return false;
	});
	
	// If #deleteTip has a parent of .msgReply, that means are removeing a reply, else, are removing a message
	$("#reviewWall #removeBtn").live('click',function(){
		if ($("#deleteTip").parents(".msgReply").length == 1){
			var replyId = $(this).parents(".msgReply").attr("value");
			$(this).parents(".msgReply").hide();
			$.post("ajax/ajax.reviewWall.php",{"removeReviewReply": "true", "replyId": replyId});
		} else if ($("#deleteTip").parents(".msgFeed").length == 1){
			var noti      = $("<li class='notification'>Review has been removed </li>");
			var review    = $(this).parents(".msgFeed");
			var reviewId  = $(this).parents(".msgFeed").attr("value");
			$("#deleteTip").appendTo("#leftContainer").hide();
			review.replaceWith(noti);
			$.post("ajax/ajax.reviewWall.php",{"removeReview": "true", "reviewId": reviewId});
		}
		return false;
	});
	
	// If #deleteTip has a parent of .msgReply, that means are removeing a reply, else, are removing a message
	$("#reviewWall #reportBtn").live('click',function(){
		var noti      = $("<li class='notification'>Review has been reported</li>");
		var review    = $(this).parents(".msgFeed");
		var reviewId  = $(this).parents(".msgFeed").attr("value");
		$("#deleteTip").appendTo("#leftContainer").hide();
		review.replaceWith(noti);
		$.post("ajax/ajax.reviewWall.php",{"reportReview": "true", "reviewId": reviewId});
		return false;
	});
}*/