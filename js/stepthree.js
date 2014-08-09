// JavaScript Document
$(document).ready(function(){
	fieldInterface();
});

function fieldInterface(){
	// When mouse over, display overlay and change font color
    $("#fieldContainer li").mouseenter(function(){
		var list = $(this);
		list.find(".overlay").fadeIn(500,"swing");
		list.css("color","#FFFFFF");
	});
	// When mouse leaves, hide .overlay and change font color if it does not have current class
	$("#fieldContainer li").mouseleave(function(){
		var list = $(this);
		if (!list.find(".overlay").hasClass("current")){
			list.css("color","#555555");
			list.find(".overlay").hide();
		}
	});
	// Toggles hidden input and border of image
	$("#fieldContainer .overlay").toggle(
		function(){
			var fieldId = $(this).parent("li").attr("id");
			$(this).addClass("current");
			$("<input type='hidden' name='field[]' value='" + fieldId + "'/>").appendTo("#addField"); },
		function(){
			var fieldId = $(this).parent("li").attr("id");
			$(this).removeClass("current");
			$("#addField input[value='"+ fieldId + "']").remove();
	});	

}