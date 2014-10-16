/*global $:false, ace:false, htmlField:false, cssField:false, jsField:false, jqconsole:false*/
(function cloudEdit() {
  "use strict";
  // Globals
  // ---
  // For buildOutput() creation. Toggle includes in html output.
  var use = {
    Autoprefixer: false,
    Less: false,
    Sass: false,
    Modernizr: false,
    Normalize: false,
    Bootstrap: false,
    Foundation: false,
    liveEdit: false
  };

  // ---
  // End Globals

  // Create Text Area panes
  // Init ACE Editor and set options;
  (function initAce() {
    var aceTheme;
    if (localStorage.getItem("theme")) {
      aceTheme = localStorage.getItem("theme");
    } else {
      aceTheme = "ace/theme/chrome";
    }

	// HTML Editor
	if ( jQuery('#html').length > 0 ) {
		window.htmlField = ace.edit("html");
		htmlField.setOptions({
		  useWorker: false,
		  theme: aceTheme,
		  displayIndentGuides: true,
		  mode: "ace/mode/html",
		  tabSize: 2,
		  useSoftTabs: true,
		  showPrintMargin: false,
		  enableEmmet: false
		});
	}


    // CSS Editor
	if ( jQuery('#css').length > 0 ) {
		window.cssField = ace.edit("css");
		cssField.setOptions({
		  theme: aceTheme,
		  displayIndentGuides: true,
		  mode: "ace/mode/css",
		  tabSize: 2,
		  useSoftTabs: true,
		  showPrintMargin: false,
		  enableEmmet: false
		});
	}

    // JS Editor
	if ( jQuery('#js').length > 0 ) {
		window.jsField = ace.edit("js");
		jsField.setOptions({
		  theme: aceTheme,
		  displayIndentGuides: true,
		  mode: "ace/mode/javascript",
		  tabSize: 2,
		  useSoftTabs: true,
		  showPrintMargin: false
		});
	}

    // Retrieve values from sessionStorage if set
    (function sessionStorageGet() {

		if ( typeof htmlField != 'undefined' && htmlField != '' ) {
			if (sessionStorage.getItem("html")) {
				htmlField.setValue(sessionStorage.getItem("html"));
				htmlField.clearSelection();
			  } else {
				var existingValue = htmlField.getValue();
				if ( typeof existingValue == 'undefined' || existingValue == '' ) {
					htmlField.setValue("<!-- Do not place html/head/body tags here.\n" +
					  "Insert the tags as would normally be used in your\n" +
					  "body element. <script> tags ARE allowed, though\n" +
					  "they're best placed at the end of your HTML -->\n");
					htmlField.clearSelection();
				}
			  }
		}
		
		if ( typeof jsField != 'undefined' && jsField != '' ) {
			if (sessionStorage.getItem("js")) {
				jsField.setValue(sessionStorage.getItem("js"));
				jsField.clearSelection();
			}
		}
		
		if ( typeof cssField != 'undefined' && cssField != '' ) {
			if (sessionStorage.getItem("css")) {
				cssField.setValue(sessionStorage.getItem("css"));
				cssField.clearSelection();
			}
			  
			if (sessionStorage.getItem("cssMode")) {
				cssField.getSession().setMode(sessionStorage.getItem("cssMode"));
			}
		}
      
		if (sessionStorage.getItem("use")) {
			use = JSON.parse(sessionStorage.getItem("use"));
		}
      
    })();

  })();
  // END ACE Editor

	if ( typeof htmlField != 'undefined' && htmlField != '' ) {
		htmlField.getSession().on("change", function(e) {
			var textToWrite = htmlField.getValue();
			var targetId = jQuery('#html').attr('data-target-id');
			document.getElementById(targetId).value = textToWrite;
		});
	}

	if ( typeof cssField != 'undefined' && cssField != '' ) {
		cssField.getSession().on("change", function(e) {
			var textToWrite = cssField.getValue();
			var targetId = jQuery('#css').attr('data-target-id');
			document.getElementById(targetId).value = textToWrite;
		});
	}

	if ( typeof jsField != 'undefined' && jsField != '' ) {
		jsField.getSession().on("change", function(e) {
			var textToWrite = jsField.getValue();
			var targetId = jQuery('#js').attr('data-target-id');
			document.getElementById(targetId).value = textToWrite;
		});
	}
})();
