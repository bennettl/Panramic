// JavaScript Document
$(document).ready(function(){
	// Depending on which #fbmenu a user clicks, display the appropriate frame/table
	$("#fbmenu a").click(function(){
        var href = $(this).attr("href");
		$("#fbmenu .current").removeClass("current");
		$("#fbcontentContainer > *").hide();
		$(this).addClass("current");
		$(href + " *").show();
		$(href).fadeIn(1000, function(){
			focus_input(href);
		});
		return false;
    });
	
	// When signInform is submitted send a post request. If it's sucessful, redirect user, else display error message
	$("#signInForm").submit(function(){
		var fburl    = 'http://www.panramic.com/fb.home.php';
		var formInfo = $(this).serialize() + "&signIn=true";
		$("#signInError").hide();
		$.post('ajax/ajax.signin.php',formInfo, function(data){
			if (data.success == 'yes'){
				window.location = fburl;
			} else if (data.success == 'no'){
				$("#signInError").text(data.message).show();
			}
		},'json');
		return false;
	});
});

// Depending on which #fbmenu a is clicked, focus on the appropriate input
function focus_input(href){
	if (href == "#signInForm"){
		if ($("[name='siEmail']").val().length > 1){
			$("[name='siPassword']").focus();
		} else{
			$("[name='siEmail']").focus();
		}
	} else if (href == "#signUpForm"){
		$("[name='fullname']").focus();
	}
}