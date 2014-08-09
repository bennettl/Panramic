// JavaScript Document
$(document).ready(function(){
	leftContainer();
});
// All functinos relevant to leftContainer are placed here
function leftContainer(){
	addBtn();
	evtWall();
	// When user clicks #tabTop <a>, send the post request and display the appropriate page
	$("#bProfile #tabTop a").click(function(){
		var tabId     = $(this).attr("id").substring(4);
		var profileId = parseInt($(this).attr("href").substring(1),8);
		var info      = {template: true, filebase: 'profile', file: "profile." + tabId + ".php", "profileId": profileId};
		
		// If the friendTip and delete tip isn't append to #contentContainer when the new page loads, then it will be lost
		$("#friendTip").appendTo("#leftContainer").hide();
		$("#deleteTip").appendTo("#leftContainer").hide();
		$("#contentContainer").hide();
		$("#loading").show();
		
		switch(tabId){
			case "evtWall":
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
	
	// Mini tooltip when user hovers over #tProfile img
	$("#profileNetwork i").hover(
		function(){
			var listVal = $(this).attr("class");
			var xPos = $(this).position().left;
			var yPos = $(this).position().top;
			$("#sideTip").appendTo("#leftContainer").css({top: yPos + 2 + "px", left: xPos + 30 + "px", display:"block"}).text(listVal);},
		function(){
			$("#sideTip").appendTo("#container").hide();
	});
}