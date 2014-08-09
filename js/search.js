// JavaScript Document
$(document).ready(function() {

	$("#searchResults > div:not(':first')").hide();
	navCurrent("#sidenavTop", "#sidenavTop a");

	// When #searchForm is submitted, load up the appropriate page
	$("#searchForm").submit(function() {
		var tabId     = $("#sidenavTop a.current").attr("id").substring(4);
		var searchStr = $("[name='s']").val();
		loadResult(tabId, searchStr);
		return false;
	});

	// When user clicks #sidenvvTop <a>, load up the appropriate page
	$("#sidenavTop a").click(function() {
		var tabId     = $(this).attr("id").substring(4);
		var searchStr = $("[name='s']").val();
		loadResult(tabId, searchStr);
	});

	function loadResult(tabId, searchStr) {
		var info = {template: true, filebase: 'search', file:  "search." + tabId + ".php", "s": searchStr};
		$("#searchResults > div").hide();
		$("#friendTip").appendTo("#leftContainer").hide();
		$("#searchResults").hide();
		$("#loading").show();

		switch (tabId) {
		case "event":
			$("#searchResults").load(templateFile, info , function() {
				$("#loading").hide();
				$(this).hide().fadeIn(500);
				feedToggle("35px");
				$(".delete").remove();
			});
			break;
		default:
			$("#searchResults").load(templateFile, info, function() {
				$("#loading").hide();
				$(this).hide().fadeIn(500);
			});
			break;
		}
	}
});