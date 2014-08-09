// JavaScript Document
$(document).ready(function(){
	// Depending on which #pageList a the the user clicks, will send the corresponding request and repalce the #statscontainer
	$("#pageList a").click(function(){
		var timeframe = $(this).attr("href").substr(1);
		var info = {template: true, filebase: 'stats', file:'stats.log.php', "timeframe":timeframe};
		$("#statsLog").hide();
		$("#loading").show();
		$.post(templateFile,info,function(data){
			var newLog  = data;
			$("#loading").hide();
			$("#statsLog").replaceWith(newLog);
			$("#statsLog").hide().fadeIn(500);
		},'html');
	});
	navCurrent("#pageList", "#pageList a");
});