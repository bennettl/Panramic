// JavaScript Document
$(document).ready(function(){
	$("#passwordForm").submit(function(){
		var errorMsg = '';
		// If validateEmail has a return value, then there is an errorMsg
		if (validateEmail($("#passwordForm [name='email']"))){
			errorMsg = validateEmail($("#passwordForm [name='email']"));
		}
		if (errorMsg == ''){
			var formInfo = $(this).serialize() + "&resetPassword=true";
			// Send the post request and display the appropriate response
			$.post('ajax/ajax.resetpassword.php',formInfo, function(data){
				var	success = data.success;
				var	message = data.response;
				$("#passNoti").text(message);
				if (success == "yes"){
					$("[name='email']").val("");
					$("#passwordForm").hide();
				}
			},'json');
		} else{
			$("#passNoti").text(errorMsg);	
		}
		return false;
	});
});