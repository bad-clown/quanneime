$.extend({
	includePath: '',
	include: function(file) {
		var files = typeof file == "string" ? [file] : file;
		for (var i = 0; i < files.length; i++) {
			var name = files[i].replace(/^\s|\s$/g, "");
			var att = name.split('.');
			var ext = att[att.length - 1].toLowerCase();
			var isCSS = ext == "css";
			var tag = isCSS ? "link" : "script";
			var attr = isCSS ? " type='text/css' rel='stylesheet' " : " type='text/javascript' ";
			var link = (isCSS ? "href" : "src") + "='" + $.includePath + name + "'";
			if(tag == 'link'){
				if ($(tag + "[" + link + "]").length == 0) $('head').append("<" + tag + attr + link + "></" + tag + ">");
			}
			else if(tag == 'script') {
				if ($(tag + "[" + link + "]").length == 0) document.write("<" + tag + attr + link + "></" + tag + ">");
			}
		}
	}
});
$.includePath=$_Path;
$.include(['/js/dataurl.js', '/js/jquery.tmpl.min.js', '/js/jquery.cookie.js', '/js/main.js']);