/*
---------------------------------------------------------------------------------------
    A jQuery Plugins to apply html data attributes to make inline css.
    Plugin: jQuery Data CSS
	Version 1.0.0
	Author: Jewel Ahmed
	Author URL: http://codeatomic.com/

	Licensed under The MIT License (MIT)
---------------------------------------------------------------------------------------
*/
(function( $ ){
	
	$.fn.datacss = function() {
		
		var cssProperties = {
			"background": "background",
				"background-color": "background-color",
				"background-image": "background-image",
				"background-attachment": "background-attachment",
				"background-size": "background-size",
				"background-repeat": "background-repeat",
				"background-position": "background-position",
		  
			"border": "border",
			  
			"border-width": "border-width",
				"border-top-width": "border-top-width",
				"border-right-width": "border-right-width",
				"border-bottom-width": "border-bottom-width",
				"border-left-width": "border-left-width",

			"border-style": "border-style",
				"border-top-style": "border-top-style",
				"border-right-style": "border-right-style",
				"border-bottom-style": "border-bottom-style",
				"border-left-style": "border-left-style",

			"border-color": "border-color",
				"border-top-color": "border-top-color",
				"border-right-color": "border-right-color",
				"border-bottom-color": "border-bottom-color",
				"border-left-color": "border-left-color",
			
			"border-radius": "border-radius",
				"border-top-left-radius": "border-top-left-radius",
				"border-top-right-radius": "border-top-right-radius",
				"border-bottom-right-radius": "border-bottom-right-radius",
				"border-bottom-left-radius": "border-bottom-left-radius",
		  
			"padding": "padding",
				"padding-top": "padding-top",
				"padding-right": "padding-right",
				"padding-bottom": "padding-bottom",
				"padding-left": "padding-left",

			"margin": "margin",
				"margin-right": "margin-right",
				"margin-bottom": "margin-bottom",
				"margin-left": "margin-left",
				"margin-top": "margin-top",				
				
			"width": "width",
				"max-width": "max-width",
				"min-width": "min-width",
			
			"height": "height",
				"max-height": "max-height",
				"min-height": "min-height",
			
			"overflow": "overflow",
				"overflow-x": "overflow-x",
				"overflow-y": "overflow-y",
			
			"color": "color",	
			"opacity": "opacity",	
			"display": "display",	
			"visibility": "visibility",	
			"position": "position",	
			"top": "top",	
			"right": "right",	
			"bottom": "bottom",	
			"left": "left",	
			"z-index": "z-index",	
			"resize": "resize",	
			"clear": "clear",	
			"cursor": "cursor",	
			"vertical-align": "vertical-align",	
			
			"font": "font",	
				"font-family": "font-family",	
				"font-size": "font-size",	
				"font-size-adjust": "font-size-adjust",	
				"font-stretch": "font-stretch",	
				"font-style": "font-style",	
				"font-variant": "font-variant",	
				"font-weight": "font-weight",
				
			
			"text-align": "text-align",	
			"text-align-last": "text-align-last",	
			"text-indent": "text-indent",	
			"text-justify": "text-justify",	
			"text-overflow": "text-overflow",	
			"text-shadow": "text-shadow",	
			"text-transform": "text-transform",	
			
			"text-decoration": "text-decoration",	
				"text-decoration-color": "text-decoration-color",	
				"text-decoration-line": "text-decoration-line",	
				"text-decoration-style": "text-decoration-style",	

			"transform": "transform",
				"transform-origin": "transform-origin",
				"transform-style": "transform-style",
			
			"transition": "transition",
			"transition-delay": "transition-delay",
			"transition-duration": "transition-duration",
			"transition-property": "transition-property",
			"transition-timing-function": "transition-timing-function",

			"white-space": "white-space",
			"word-break": "word-break",	
			"word-spacing": "word-spacing",
			"word-wrap": "word-wrap",

		};
		$.each( cssProperties, function( key, value ) {
			$( '[data-' + key + ']' ).not( '[data-' + key + '=""]' ).each(function() {
				$( this ).css( value, $( this ).data( key ) );					
			});
		});	
		
	};
	
	jQuery.fn.datacss();
	
})(jQuery);