// JavaScript Document
$(document).ready(function() {
	contactForm();
	joinForm();
});

// When #contactForm is submitted when send a post request and #contactNoti is displayed
function contactForm(){
	$("#contactForm").submit(function(){
		var formInfo  = $(this).serialize() + "&contactForm=true";
		var name      = $("[name='name']").val().replace(/\s/g,"");;
		var email     = $("[name='email']").val().replace(/\s/g,"");;
		var message   = $("[name='msg']").val().replace(/\s/g,"");
		
		if (name != "" && email !="" && message !=""){
			$.post('ajax/ajax.mail.php',formInfo);
			$("input:not([name='send']), textarea").val("");
			$("tr").show();
			$("#contactNoti").text("Message Sent!").show();
		} else {
			$("tr").show();
			$("#contactNoti").text("Please fill out all fields").show();
		}
		return false;
	});
}

// When #contactForm is submitted when send a post request and #contactNoti is displayed
function joinForm(){
	$("#joinForm").submit(function(){
		var formInfo  = $(this).serialize() + "&joinForm=true";
		var name      = $("[name='name']").val().replace(/\s/g,"");;
		var email     = $("[name='email']").val().replace(/\s/g,"");;
		var message   = $("[name='msg']").val().replace(/\s/g,"");
		
		if (name != "" && email !="" && message !=""){
			$.post('ajax/ajax.mail.php',formInfo);
			$("input:not([name='send']), textarea").val("");
			$("tr").show();
			$("#joinNoti").text("Message Sent!").show();
		} else{
			$("tr").show();
			$("#joinNoti").text("Please fill out all fields").show();
		}
		return false;
	});
}