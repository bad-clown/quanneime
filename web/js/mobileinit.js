$(document).bind("mobileinit", function() {
	$.mobile.linkBindingEnabled = false;
	$.mobile.ajaxEnabled = false;
	$.mobile.defaultPageTransition = "none";
	$.mobile.activeBtnClass = "ui-btn-hover-a";
	$.mobile.page.prototype.options.domCache = false;
	$.mobile.buttonMarkup.hoverDelay = "false";
	$.mobile.loader.prototype.options.text = "加载中...";
	$.mobile.loader.prototype.options.textVisible = true;
	$.mobile.loader.prototype.options.theme = "a";
	$.mobile.loader.prototype.options.html = "";

	$.mobile.loader.prototype.defaultHtml = "<div class='ui-loader' data-overlay-theme='a' class='ui-content' style='opacity: 0.5;'>" +
		"<span class='ui-icon ui-icon-loading'></span>" +
		"<h1></h1>" +
		"<div class='ui-loader-curtain'></div>" +
		"</div>";
});