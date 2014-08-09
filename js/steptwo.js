// JavaScript Document
$(document).ready(function(){
	// Prevents hitting enter from submitting form
	$(window).keydown(function(evt){
		if (evt.which == 13) {evt.preventDefault();}
	})
	$("select").change(function(){
		var network = $(this).val();
		if (network == 0){
			var image = "css/images/default.png";
			$("#networkImg").attr("src", image).hide().fadeIn(1000); 
		} else {
			var image = "css/images/networks/" + network +".png";
			$("#networkImg").attr("src", image).hide().fadeIn(1000); 
		}
	});
	// have an image
	/*
	var imgInput = $("#" + inputField.attr("name") + "Img img")				
	  inputField.val(allLists[i].innerHTML);
	  $("#" + inputField.attr("name")).val(allLists[i].value);
	  $(imgInput).attr("src", "css/images/networks/" + allLists[i].value + ".png").hide().fadeIn(500); 
	  
	  // remove one
	  var imgInput = $("#" + inputField.attr("name") + "Img img");
  $("#searchList").empty();			
  $("#" + inputField.attr("name")).removeAttr("value");
  $(imgInput).attr("src", "css/images/default.png"); */
});