/*
---------------------------------------------------------------------------------------
    Theme Admin JS custom functions                           
    @Since Version 1.0
---------------------------------------------------------------------------------------
*/

/*
---------------------------------------------------------------------------------------
	Mondira custom input for file url attachment field Initialization
	This class mondiraAdmin has been used on mondira-image-uploader-3.5.js file to make a text field file upload friendly with WordPress default file uploader
	@Since Version 1.0
---------------------------------------------------------------------------------------
*/    
var mondiraAdmin = {
    themeGetNDisplayImage : function( attachment, target ){    
		if( jQuery("#"+target+"_preview").size()>0 ){ 
			var cssObj = {
              'background-color' : '#fff',
              'border' : '1px solid #CCC',
              'height' : '120px',
              'width' : '140px',
              'padding' : '5px',
              'text-align' : 'center',
              'display' : 'block'
            }            
            jQuery("#"+target+"_preview").css(cssObj);
            jQuery("#"+target+"_preview").html('<img style="margin-top:44px;" src="'+ FRAMEWORK_ADMIN_RESOURCES_URI + '/images/ajax-loader.gif"/>');
        } else {
            jQuery("#"+target).val(attachment.url);   
            return true;
        }        
        jQuery.post(ajaxurl, {
            action:'mondira-image-upload-get-image',
            id: attachment.id, 
            cookie: encodeURIComponent(document.cookie)
        }, function(src){
            if ( src == '0' ) {
                alert( 'Empty image source.');
            } else {
                if(jQuery("#"+target).size()>0){
                    jQuery("#"+target).val(src);
                    if( jQuery("#"+target+"_preview").size()>0 ){
                        jQuery("#"+target+"_preview").html('<a class="fancybox prview_thumb_image" href="'+src+'"><img src="' + src+'" width="140" /></a>');
                    } 
                }
            }        
        });        
    },    
    themeGetNDisplayZIP : function(attachment_id,target){  
        jQuery("#"+target).val(attachment_id);
    }
}

/*
---------------------------------------------------------------------------------------
    jQuery Initializing WP_Editor
---------------------------------------------------------------------------------------
*/
jQuery(function () {
    window.init_textarea_html = function($element) {

        var qt, textfield_id = $element.attr("id");
		
		// Init Quicktag
        if(_.isUndefined(tinyMCEPreInit.qtInit[textfield_id])) {
			window.tinyMCEPreInit.qtInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.qtInit[wpActiveEditor], {id: textfield_id})
        }
		
        // Init tinymce
        if(window.tinyMCEPreInit && window.tinyMCEPreInit.mceInit[wpActiveEditor]) {
			window.tinyMCEPreInit.mceInit[textfield_id] = _.extend({}, window.tinyMCEPreInit.mceInit[wpActiveEditor], {
		        resize: 'vertical',
		        height: 200,
		        id: textfield_id,
		        setup: function (ed) {
			       if (typeof(ed.on) != 'undefined') {
				        ed.on('init', function (ed) {
					        ed.target.focus();
					        wpActiveEditor = textfield_id;
				        });
			        } else {
				        ed.onInit.add(function (ed) {
					        ed.focus();
					        wpActiveEditor = textfield_id;
				        });
			        }
		        }
	        });
			window.tinyMCEPreInit.mceInit[textfield_id].plugins =  window.tinyMCEPreInit.mceInit[textfield_id].plugins.replace(/,?wpfullscreen/, '');
        }
        
		qt = quicktags( window.tinyMCEPreInit.qtInit[textfield_id] );
        QTags._buttonsInit();
        if(window.tinymce) {
            window.switchEditors && window.switchEditors.go(textfield_id, 'tmce');
            if(tinymce.majorVersion === "4") tinymce.execCommand( 'mceAddEditor', true, textfield_id );
        }
        vc_activeMce = textfield_id;
	    wpActiveEditor = textfield_id;
    };
});

/*
---------------------------------------------------------------------------------------
    Theme settings accordion JS
    @Since Version 1.0
---------------------------------------------------------------------------------------
*/
function set_accordion_cookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function get_accordion_cookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}
function mondira_theme_settings_accordion_initialization(){
	if ( jQuery('.mondira-theme-settings-accordion').length > 0 ) {
		var i = 1;
		var cv = '';
		var $this = '';
		jQuery('.mondira-theme-settings-accordion').each(function(){
		
			jQuery(this).find('> h3').append('<a data-open-id="acc-open-'+i+'" data-id="acc-close-'+i+'" href="#" class="acc-close">Close</a><a data-id="acc-open-'+i+'" href="#" class="acc-open">Open</a>');
			
			cv = get_accordion_cookie('mondira-acc-open-' + i );
			
			if ( cv == 'open' ) {
				$this = jQuery(this).find('> h3').find('a.acc-open');
				
				$this.hide();
				$this.parent('h3').find('a.acc-close').show();
				$this.parents('.mondira-theme-settings-accordion').find('.mondira-theme-settings-accordion-inner').show();
				set_accordion_cookie( 'mondira-' + $this.data('id'), 'open', 1 );
				
			}
			
			i++;
		});
		jQuery('.mondira-theme-settings-accordion').find('> h3 > a').on('click', function(){
			if ( jQuery(this).hasClass('acc-close') ) {
				jQuery(this).hide();
				jQuery(this).parent('h3').find('a.acc-open').show();
				jQuery(this).parents('.mondira-theme-settings-accordion').find('.mondira-theme-settings-accordion-inner').hide();
				set_accordion_cookie( 'mondira-' + jQuery(this).data('open-id'), '', 1 );
			} else {
				jQuery(this).hide();
				jQuery(this).parent('h3').find('a.acc-close').show();
				jQuery(this).parents('.mondira-theme-settings-accordion').find('.mondira-theme-settings-accordion-inner').show();
				set_accordion_cookie( 'mondira-' + jQuery(this).data('id'), 'open', 1 );
			}
			return false;
		});
	}
}

/*
---------------------------------------------------------------------------------------
    Framework Input field JS Initialization
    @Since Version 1.0
---------------------------------------------------------------------------------------
*/
jQuery(document).ready( function($) {
	/*
	---------------------------------------------------------------------------------------
		Creating theme settings or documentation
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 
    creatTabForMondiraDocumentation(); 
	/*
	---------------------------------------------------------------------------------------
		Theme settings accordion Init
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 
    mondira_theme_settings_accordion_initialization(); 
	
	/*
	---------------------------------------------------------------------------------------
		Yes No Custom Checkbox Initialization
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 	
	$(".yesno")
		.iButton({
			labelOn: "Yes", 
			labelOff: "No", 
			easing: 'easeOutBounce', 
			duration: 500, 
			change: function ($input){
				if($input.is(":checked")){
					$input[0].value = 'yes';    
				} else {
					$input[0].value = 'no';
				}
			}
		}).trigger("change");
	
	/*
	---------------------------------------------------------------------------------------
		Select field chosen Initialization
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 	
    $('select.chosen').chosen();
	
	/*
	---------------------------------------------------------------------------------------
		Range Input Initialization
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 
	$(":range").rangeinput();
	$(".range").rangeinput();
	
	/*
	---------------------------------------------------------------------------------------
		Date Picker Initialization
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/ 
	$(function(){
        var pickerOpts = {
			dateFormat : "yy-mm-dd",
			defaultDate: "+5",
			showOtherMonths: true
        };
        $(".datepicker").datepicker(pickerOpts);
    });
    
	/*
	---------------------------------------------------------------------------------------
		Color Picker Initialization
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/    
    $('.wp-color-picker').wpColorPicker();
	
	/*
	---------------------------------------------------------------------------------------
		Enabling input fold on/off
		@Since Version 1.0
	---------------------------------------------------------------------------------------
	*/   
    var checkbox_fold = function( $fold_id, $input_obj ) {
		if ( $('#'+$fold_id).is(':checked') ) {
		} else {
			$input_obj.parents("tr").hide("slow");    
		}
		$('#'+$fold_id).change(function(){
			if($('#'+$fold_id).is(':checked')){
				$input_obj.parents("tr").show("slow");
			} else {
				$input_obj.parents("tr").hide("slow");    
			}    
		});
	}
	
	var input_custom_fold = function( $fold_id, $input_obj, $fold_value ) {
		var $input_value = $('#'+$fold_id).val();
		
		
		if ( $fold_value.indexOf(' ') > 1 ) { //Has multiple value
			var $displayed = $seldisplayed = false;
			$.each( $fold_value.split(' '), function(index){
				if ( $input_value == $fold_value.split(' ')[index] ) {
					$input_obj.parents("tr").show("slow");    
					$displayed = true;
				} 
			});
			
			var $select_value = '';
			$('#'+$fold_id).change(function(){
				$seldisplayed = false;
				$select_value = jQuery(this).val();
				$.each( $fold_value.split(' '), function(index){
					if ( $select_value == $fold_value.split(' ')[index] ) {
						$input_obj.parents("tr").show("slow");    
						$seldisplayed = true;
					} 
				});
				
				if ( $seldisplayed ) {
				} else {
					$input_obj.parents("tr").hide("slow");
				}
			});
			
			if ( $displayed ) {
			} else {
				$input_obj.parents("tr").hide("slow");
			}
		} else { //Has only one value
			if ( typeof $input_value == 'undefined' || $input_value == '' ) {
				if ( $fold_value == 'empty' ) {
					$input_obj.parents("tr").show("slow");
				} else {
					$input_obj.parents("tr").hide("slow");
				}
			} else {
				if ( $fold_value == 'notempty' ) {
					$input_obj.parents("tr").show("slow");
				} else {
					if ( $input_value == $fold_value ) {
						$input_obj.parents("tr").show("slow");    
					} else {
						$input_obj.parents("tr").hide("slow");
					}
				}
			}
			
			$(document).on('change', '#'+$fold_id, function() {
				if ( typeof jQuery(this).val() == 'undefined' || jQuery(this).val() == '' ) {
					if ( $fold_value == 'empty' ) {
						$input_obj.parents("tr").show("slow");
					} else {
						$input_obj.parents("tr").hide("slow");
					}
				} else {
					if ( $fold_value == 'notempty' ) {
						$input_obj.parents("tr").show("slow");
					} else {
						if ( jQuery(this).val() == $fold_value ) {
							$input_obj.parents("tr").show("slow");    
						} else {
							$input_obj.parents("tr").hide("slow");
						}
					}
				}
			});
		}		
	}
	
	var input_custom_empty_fold = function( $fold_id, $input_obj, $fold_value ) {
		var $input_value = $('#'+$fold_id).val();
		if ( typeof $input_value == 'undefined' ) {
			$input_obj.parents("tr").hide("slow");
		} else {
			if ( $input_value == '' ) {
				$input_obj.parents("tr").show("slow");    
			} else {
				$input_obj.parents("tr").hide("slow");
			}
		}
		
		$('#'+$fold_id).change(function(){
			if ( typeof jQuery(this).val() == 'undefined' ) {
				$input_obj.parents("tr").hide("slow");
			} else {
				if ( jQuery(this).val() == '' ) {
					$input_obj.parents("tr").show("slow");    
				} else {
					$input_obj.parents("tr").hide("slow");
				}
			}
		});
	}
	
	var input_custom_notempty_fold = function( $fold_id, $input_obj, $fold_value ) {
		var $input_value = $('#'+$fold_id).val();
		if ( typeof $input_value == 'undefined' ) {
			$input_obj.parents("tr").hide("slow");
		} else {
			if ( $input_value != '' ) {
				$input_obj.parents("tr").show("slow");    
			} else {
				$input_obj.parents("tr").hide("slow");
			}
		}
		
		$('#'+$fold_id).change(function(){
			if ( typeof jQuery(this).val() == 'undefined' ) {
				$input_obj.parents("tr").hide("slow");
			} else {
				if ( jQuery(this).val() != '' ) {
					$input_obj.parents("tr").show("slow");    
				} else {
					$input_obj.parents("tr").hide("slow");
				}
			}
		});
	}
	
	var fold_it = function( $fold_id, $input_obj, $fold_value ) {
		if ( $fold_value == 'empty' ) {
			input_custom_empty_fold( $fold_id, $input_obj, $fold_value );
		} else if ( $fold_value == 'notempty' ) {
			input_custom_notempty_fold( $fold_id, $input_obj, $fold_value );
		} else if ( $fold_value != '' ) {
			input_custom_fold( $fold_id, $input_obj, $fold_value );
		} else { //By default it is supposed to be a checkbox and required checked|yes
			checkbox_fold( $fold_id, $input_obj );
		}
	}
	
	$('input, select, textarea').each(function(){
        var $input_obj = $(this);         
		var $fold_str = $input_obj.data('fold');
					
		if ( typeof $fold_str == 'undefined' || $fold_str == '' ) {
			
		} else {
			//$fold_str it can be a value like
			//LIST_OF_ID saperated by COMMA # LIST_OF_VALUE saperated by COMMA or EMPTY or SINGLE ID only WITHOUT #
			//Predefined value 1. empty,checked,notempty,value1 value2, 
			//website_layout#top 
			
			var $fold_id = '';
			var $split_arr = $fold_str.split('#');
			var $fold_id_list = $split_arr[0];
			var $fold_value_list = $split_arr[1];
			
			if ( $fold_id_list.split(',').length > 1 ) {
				$fold_id_list.split(',').each(function(index){
					//better to keep for framework version 1.1.0
				});
			} else { //SINGLE FIELD Depends only! so we know $fold_value_list has only one field value|values
				$fold_id = $fold_id_list;
				if ( typeof $fold_value_list == 'undefined' || $fold_value_list == '' ) {
					//By default it is supposed to be a checkbox and required checked|yes
					fold_it( $fold_id, $input_obj, '' );
				} else {
					fold_it( $fold_id, $input_obj, $fold_value_list );
				}	
			}
		}        
    });  


	/*
	---------------------------------------------------------------------------------------
		Shortcode Generators JS
	---------------------------------------------------------------------------------------
	*/

	/*
	---------------------------------------------------------------------------------------
		WP Editor Toolbar Button event to open shortcode generator add new inner shortcode for nested shortcode
	---------------------------------------------------------------------------------------
	*/
	$('body').on('click','.inner_shortcode_addnew',function(){
		
		var $clone = $(this).parent().parent().find('.set-of-nested-shortcode-options:first').clone(true);
		$clone.find('input[type=text],input[type=number],textarea').attr('value','');
		
		if( $clone.find('.container-image').length > 0 ) {
    		$clone.find('.mondira-upload-input').attr('src','');
    		$clone.find('.mondira-media-preview').html('');
			var $random = Math.floor((Math.random() * 10) + 1);
			var id = href = '';
			
			$clone.find('.upload_image_button_latest').each(function(){
				id = 'clone_' + $random + '_thickbox';
				$(this).parent().parent().find('.mondira-upload-input').attr('id', id)
				$(this).attr('id', id + '_anchor' );
				$(this).attr('data-target', id );
				href = $(this).attr( 'href' );
				href = href.replace( 'target=image', 'target=' + id );
				$(this).attr( 'href', href );
				
				$(this).parent().find('.mondira-media-preview').attr( 'id', id + '_preview' );
				
			});
			
    		//setTimeout(function(){ initUpload($clone) },200);
    	}
		
		$(this).parent().before( $clone );
		
		return false;
		
	} ); 

	/*
	---------------------------------------------------------------------------------------
		WP Editor Toolbar Button event to open shortcode generator remove inner shortcode for nested shortcode
	---------------------------------------------------------------------------------------
	*/
	$('body').on('click','.inner_shortcode_remove',function(){
		
		if ( $(this).parent().parent().find('.set-of-nested-shortcode-options').length > 1 ) {
			$(this).parent().parent().find('.set-of-nested-shortcode-options:last').remove();		
		} else {
			alert( 'No child shortcode found to delete, You can add one by add new button.' );
		}
		return false;
		
	} ); 

	/*
	---------------------------------------------------------------------------------------
		WP Editor Toolbar Button event to open shortcode generator
	---------------------------------------------------------------------------------------
	*/
	$('body').on('click','.mondira-shortcode-generator',function(){
		$.magnificPopup.open( {
			mainClass: 'mfp-zoom-in',
			items: {
				src: '#mondira-shortcode-generator'
			},
			type: 'inline',
			removalDelay: 500
	    }, 0 ); 
		
		/*
		var $id = $('#options-' + $('#mondira-shortcodes').val() ).find('.wp-editor-wrap').first().attr('id'); 
		if ( $id ) {
			id = $id.slice( 3, -5 );
			//window.init_textarea_html( $('#'+id) );
			window.wpActiveEditor = id; 
		}
		*/
	} ); 
	
	
	
	/*
	---------------------------------------------------------------------------------------
		Generating Shortcode for Dynamic Items
	---------------------------------------------------------------------------------------
	*/
	function shortcode_nested_items( shortcode_name, nested_shortcode_name ) {
   	
		var code = '';
		var inner_content = '';
		//lopping the set of nested shortcode options
		$('#options-'+shortcode_name+' .set-of-nested-shortcode-options').each(function(){
			inner_content = '';
			
			code += '['+nested_shortcode_name+' ';
				
				/*
				---------------------------------------------------------------------------------------
					Checkbox field attr processing
				---------------------------------------------------------------------------------------
				*/    
				$(this).find('input[type=checkbox]').each(function(){
					 if( $(this).attr('checked') == 'checked' ) {
						if ( $(this).hasClass('skip-it') ) {
						
						} else {
							code += ' ' + $(this).data('attrname') + '="' + $(this).val() + '"';	
						}
					 }		 
				});
				
				/*
				---------------------------------------------------------------------------------------
					Textarea field attr processing
				---------------------------------------------------------------------------------------
				*/
				$(this).find('textarea:not(".skip-it")').each(function(){
					if ( $(this).hasClass( 'wp-editor-area' ) ) {
						//no support for editor in nasted shortcodes
					} else if ( 'content' == $(this).attr('data-attrname') ) {
						inner_content = $(this).val();
					} else {
						code += ' ' + $(this).attr('data-attrname')+'="'+ $(this).val() +'"';	
					}
				});
				
				/*
				---------------------------------------------------------------------------------------
					Select field attr processing
				---------------------------------------------------------------------------------------
				*/
				$(this).find('select:not("[multiple=multiple], .skip-it")').each(function(){
					code += ' ' + $(this).attr('id')+'="' + $(this).attr('value') + '"';	
				});
						
				/*
				---------------------------------------------------------------------------------------
					Multiselect field attr processing
				---------------------------------------------------------------------------------------
				*/
				$(this).find('select[multiple=multiple]').each(function(){
					if ( $(this).hasClass('skip-it') ) {
						
					} else {
						var $values = ($(this).val() != null && $(this).val().length > 0) ? $(this).val() : 'all';
						code += ' ' + $(this).attr('id')+'="' + $values + '"';	
					}
				});
				
				/*
				---------------------------------------------------------------------------------------
					Input field attr processing
				---------------------------------------------------------------------------------------
				*/
				$(this).find('input.attr').each(function(){
					if ( $(this).hasClass('skip-it') ) {
						
					} else {
						code += ' '+ $(this).attr('data-attrname')+'="'+ $(this).val()+'"';
					}
				});
				
			code += ']';
			
			code += inner_content;
			code += '[/'+nested_shortcode_name+']';
		});
		
		$('#shortcode-inner-content').html(code);
    }
	
	/*
	---------------------------------------------------------------------------------------
		Generating CSS for CSS Design Option
	---------------------------------------------------------------------------------------
	*/
	function modira_generate_css( textarea, shortcode_name ) {
		var $me = textarea; //The textarea where we are going to put the generated css
		
		var $css_str = '#mondira_css_id_' +  $.now() + ' {';
		var $css = '';
		var $field_name = '';
		$('#options-'+shortcode_name+' .mondira-css-design-attr input').each(function(){
			$field_name = $(this).data('name');
			$field_value = $(this).val();
			if ( typeof $field_value == 'undefined' || $field_value == '' || typeof $field_name == 'undefined' || $field_name == '') {
									
			} else if ( $field_name == 'background-image' ) {
				$css+=' ' + $field_name + ':url(' + $field_value + ');';
			} else if ( $field_name == 'background-size' ) {
				if ( $field_value == 'no-repeat' ) {
					$css+=' background-repeat:' + $field_value + ';';
				} else if ( $field_value == 'repeat' ) {
					$css+=' background-repeat:' + $field_value + ';';
				} else if ( $field_value == 'cover' ) {
					$css+=' background-size:' + $field_value + ';';
				} else if ( $field_value == 'certain' ) {
					$css+=' background-size:' + $field_value + ';';
				} else {
					$css+=' background-size:' + $field_value + ';';
				}
				
			} else {
				if ( $(this).hasClass('in_px') ) {
					$css+=' ' + $field_name + ':' + $field_value + 'px !important;';
				} else {
					$css+=' ' + $field_name + ':' + $field_value + ' !important;';
				}
			}
		});		
		
		$('#options-'+shortcode_name+' .mondira-css-design-attr select').each(function(){
			$field_name = $(this).data('name');
			$field_value = $(this).val();
			if ( typeof $field_value == 'undefined' || $field_value == '' || typeof $field_name == 'undefined' || $field_name == '') {
									
			} else if ( $field_name == 'background-image' ) {
				$css+=' ' + $field_name + ':url(' + $field_value + ');';
			} else {
				$css+=' ' + $field_name + ':' + $field_value + ';';
			}
		});		
		
		if ( $css != "" ) {
			$css_str+=$css;
			$css_str+='}';
		} else {
			$css_str = '';
		}
		
		$($me).val( $css_str );
	}
	
	/*
	---------------------------------------------------------------------------------------
		Generating Shortcode for WP Editor
	---------------------------------------------------------------------------------------
	*/
	function generate_shortcode( shortcode_name, parent_shortcode ) {		
	
		if ( typeof parent_shortcode == 'undefined' || parent_shortcode == '') {
			parent_shortcode = 'yes';
		} else if ( parent_shortcode == 'yes' ) {
			parent_shortcode = 'yes';
		} else {
			parent_shortcode = 'no';
		}

		if ( typeof shortcode_name == 'undefined' || shortcode_name == '') {
			shortcode_name = $('#mondira-shortcodes').val();					//The selected shortcode
		}
		var code = '[' + shortcode_name;
		
		/*
		---------------------------------------------------------------------------------------
			Processing dymanic items
		---------------------------------------------------------------------------------------
		*/    
		if ( $('#options-'+shortcode_name).find( '.nested_shortcode' ).length > 0 ) {
			var nested_shortcode_item = $('#options-'+shortcode_name).find( '.nested_shortcode' )[0];
			var nested_shortcode_name = $(nested_shortcode_item).data('nested-shortcode');
			if ( typeof nested_shortcode_name == 'undefined' || nested_shortcode_name == '') {
				
			} else {
				shortcode_nested_items( shortcode_name, nested_shortcode_name );
			}
		}
		
		/*
		---------------------------------------------------------------------------------------
			Checkbox field attr processing
		---------------------------------------------------------------------------------------
		*/    
		$('#options-'+shortcode_name+' input[type=checkbox]').each(function(){
			 if ( $(this).parents('.nested_shortcode').length > 0 ) {
				
			 } else {
		
				 if( $(this).attr('checked') == 'checked' ) {
					if ( $(this).hasClass('skip-it') ) {
					
					} else {
						code += ' ' + $(this).data('attrname') + '="' + $(this).val() + '"';	
					}
				 }		
			 
			 }			 
		});
		
		/*
		---------------------------------------------------------------------------------------
			Textarea field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' textarea:not(".skip-it")').each(function(){
			if ( $(this).parents('.nested_shortcode').length > 0 ) {
				
			 } else {
				if ( $(this).hasClass( 'css_design_textarea' ) ) {
					modira_generate_css( this, shortcode_name );
					code += ' ' + $(this).attr('data-attrname')+'="'+ $(this).val() +'"';	
				} else if ( $(this).hasClass( 'wp-editor-area' ) ) {
					$( '#shortcode-inner-content' ).html( $(this).val() );
				} else if ( 'content' == $(this).attr('data-attrname') ) {
					$( '#shortcode-inner-content' ).html( $(this).val() );
				} else {
					code += ' ' + $(this).attr('data-attrname')+'="'+ $(this).val() +'"';	
				}
			
			}
		});
		
		/*
		---------------------------------------------------------------------------------------
			Select field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' select:not("[multiple=multiple], .skip-it")').each(function(){
			if ( $(this).parents('.nested_shortcode').length > 0 ) {
				
			 } else {
				
				code += ' ' + $(this).attr('id')+'="' + $(this).attr('value') + '"';	
				
			 }
		});
				
		/*
		---------------------------------------------------------------------------------------
			Multiselect field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' select[multiple=multiple]').each(function(){
			if ( $(this).parents('.nested_shortcode').length > 0 ) {
				
			 } else {
			 
				if ( $(this).hasClass('skip-it') ) {
					
				} else {
					var $values = ($(this).val() != null && $(this).val().length > 0) ? $(this).val() : 'all';
					code += ' ' + $(this).attr('id')+'="' + $values + '"';	
				}
				
			}
		});
		
		/*
		---------------------------------------------------------------------------------------
			Input field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' input.attr').each(function(){
			if ( $(this).parents('.nested_shortcode').length > 0 ) {
				
			 } else {
			 
				if ( $(this).hasClass('skip-it') ) {
					
				} else {
					code += ' '+ $(this).attr('data-attrname')+'="'+ $(this).val()+'"';
				}
				
			}
		});
		
		code += ']';

		/*
		---------------------------------------------------------------------------------------
			Writing shortcode content on storage container
		---------------------------------------------------------------------------------------
		*/
		$( '#shortcode-opening-tag' ).html( code );
		//$( '#shortcode-inner-content' ).html( '' );
		$( '#shortcode-closing-tag' ).html( '[/'+shortcode_name+']' );
	}
    
	/*
	---------------------------------------------------------------------------------------
		Inserting Shortcode in WP Editor
	---------------------------------------------------------------------------------------
	*/	
	$('#mondira-insert-shortcode').click(function(){
    	generate_shortcode( '', 'yes' );
		var $shortcode_string = $('#shortcode-opening-tag').text() + $('#shortcode-inner-content').text() + $('#shortcode-closing-tag').text() ;
		$('#shortcode-opening-tag, #shortcode-inner-content, #shortcode-closing-tag').text( '' );
		
		window.wp.media.editor.insert( $shortcode_string );
		$.magnificPopup.close();
		return false;
		
		//https://core.trac.wordpress.org/attachment/ticket/27210/27210.diff
		/*var id = $('.wp-editor-wrap').first().attr('id'); 
		if ( id ) { 
			window.wpActiveEditor = id.slice( 3, -5 ); 
		}*/ 
		
		/*var previous_tinymce_activeEditor_id = tinymce.activeEditor.id;
		if ( $('#content').length > 0 ) {
			window.wpActiveEditor = 'content';
			window.wp.media.editor.insert( $shortcode_string );
			window.wpActiveEditor = previous_tinymce_activeEditor_id;
		}
		$.magnificPopup.close();
		return false;*/
    });

	/*
	---------------------------------------------------------------------------------------
		Hiding and Showing shortcode options / attributes on the fly!
	---------------------------------------------------------------------------------------
	*/
	 ;
	$('#mondira-shortcodes').change(function() {
		$('.shortcode-options').hide();
		$('#options-'+$(this).val()).show();
		var id = $('#options-'+$(this).val()).find('.wp-editor-wrap').first().attr('id'); 
		if ( id ) { 
			$id = id.slice( 3, -5 );
			//window.init_textarea_html( $('#' + $id) );
			window.wpActiveEditor = $id; 
		}
    });

	/*
	---------------------------------------------------------------------------------------
		Adding preview value for Icon Manager
	---------------------------------------------------------------------------------------
	*/
    $(".icons-list li").click(function() {
		$(this).attr("class","selected").siblings().removeAttr("class");
		var icon = $(this).attr("data-icons");
		$(this).parents('.mondira-shortcode-option').find('input.icon-input').val(icon);
		$(this).parents('.mondira-shortcode-option').find('.preview-icon').html("<i class=\'"+icon+"\'></i>");
	});
	
	/*
	---------------------------------------------------------------------------------------
		Adding value on text field on checkbox click event
	---------------------------------------------------------------------------------------
	*/
    $('.input-checkbox-to-text').click(function() {
		var option_name = $(this).data('attrname');
		var parent = $(this).parents('.mondira-shortcode-option');
		var parent_id = '#'+parent.attr('id');
		var inner_checkboxes = $(parent_id).find('.input-checkbox-to-text');
		var checkboxs_values = [];
		inner_checkboxes.each(function(){
			if (!$(this).is(':checked')) {
				
			} else {
				checkboxs_values.push($(this).val());
			}
		});
		$('#checkbox-val-' + option_name).val(checkboxs_values.join());
    });
	
	/*
	---------------------------------------------------------------------------------------
		WP Editor dependency for shortcode generator
	---------------------------------------------------------------------------------------
	*/
	var $shortcode = $dependency_element = $dependency_is_empty = $dependency_not_empty = $dependency_values = '';
	var $ = jQuery;
	$('.mondira-shortcode-option').each(function(){
		$shortcode = $(this).data('shortcode');
		$dependency_element = $(this).data('dependency_element');
		$dependency_is_empty = $(this).data('dependency_is_empty');
		$dependency_not_empty = $(this).data('dependency_not_empty');
		$dependency_values = $(this).data('dependency_values');
		if ( typeof $shortcode == 'undefined' || $shortcode == '' || typeof $dependency_element == 'undefined' || $dependency_element == '' ) {
			
		} else if (  $dependency_element != '' ) {
			var $delement = $(this);//Depencency Element
			$( '#options-' + $shortcode ).find( '.mondira-shortcode-option select' ).each(function(){
				var $ashortcode = $shortcode;
				var $adependency_element = $dependency_element;
				var $adependency_is_empty = $dependency_is_empty;
				var $adependency_not_empty = $dependency_not_empty;
				var $adependency_values = $dependency_values;
				var $adelement = $delement;
				
				var $attrname = $(this).data('attrname');
				
				if ( $attrname == $adependency_element ) {
					$(this).change(function(){
						var $aadelement = $adelement;
						var $initial_val = $(this).val();
						$aadelement.hide();
						if ( $initial_val == '' && $adependency_is_empty ) {
							$aadelement.show();
						} else if ( $initial_val != '' && $adependency_not_empty ) {
							$aadelement.show();
						} else if ( $initial_val == '' && $adependency_not_empty ) {
							$aadelement.hide();
						} else if ( $initial_val == $adependency_values ) {
							$aadelement.show();
						} else {
							var $dvalues = $adependency_values.split('|');
							$.each($dvalues, function( key, value ) {
								if ( $initial_val == value ) {
									$aadelement.show();
								}
							});
						}
					});
				}
			});
			
			$( '#options-' + $shortcode ).find( '.mondira-shortcode-option textarea, .mondira-shortcode-option input.number_input, .mondira-shortcode-option input:text' ).each(function(){
				var $ashortcode = $shortcode;
				var $adependency_element = $dependency_element;
				var $adependency_is_empty = $dependency_is_empty;
				var $adependency_not_empty = $dependency_not_empty;
				var $adependency_values = $dependency_values;
				var $adelement = $delement;
				
				var $attrname = $(this).data('attrname');
				
				if ( $attrname == $adependency_element ) {
					$(this).blur(function(){
						var $aadelement = $adelement;
						var $initial_val = $(this).val();
						$aadelement.hide();
						if ( $initial_val == '' && $adependency_is_empty ) {
							$aadelement.show();
						} else if ( $initial_val != '' && $adependency_not_empty ) {
							$aadelement.show();
						} else if ( $initial_val == '' && $adependency_not_empty ) {
							$aadelement.hide();
						} else if ( $initial_val == $adependency_values ) {
							$aadelement.show();
						} else {
							var $dvalues = $adependency_values.split('|');
							$.each($dvalues, function( key, value ) {
								if ( $initial_val == value ) {
									$aadelement.show();
								}
							});
						}
					});
				}
			});
			
			$( '#options-' + $shortcode ).find( '.mondira-shortcode-option input:checkbox' ).each(function(){
				var $ashortcode = $shortcode;
				var $adependency_element = $dependency_element;
				var $adependency_is_empty = $dependency_is_empty;
				var $adependency_not_empty = $dependency_not_empty;
				var $adependency_values = $dependency_values;
				var $adelement = $delement;
				
				var $attrname = $(this).data('attrname');
				
				if ( $attrname == $adependency_element ) {
					$(this).click(function() {
						$delement.hide();
						if (!$(this).is(':checked')) {
							if ( $adependency_is_empty ) {
								$delement.show();
							} else if ( $adependency_not_empty ) {
								$delement.hide();
							}
						} else {
							if ( $adependency_not_empty ) {
								$delement.show();
							} else if ( $adependency_is_empty ) {
								$delement.hide();
							}
						}
					});
					
					if (!$(this).is(':checked')) {
						if ( $adependency_is_empty ) {
							$delement.show();
						} else if ( $adependency_not_empty ) {
							$delement.hide();
						}
					} else {
						if ( $adependency_not_empty ) {
							$delement.show();
						} else if ( $adependency_is_empty ) {
							$delement.hide();
						}
					}
				}
			});
		}
	});
});

/*
---------------------------------------------------------------------------------------
	Creating theme settings or documentation
---------------------------------------------------------------------------------------
*/
function creatTabForMondiraDocumentation(){
    if(jQuery("#mondira-docs-tabs").size()>0){
        jQuery("#mondira-docs-tabs").tabs({selected:0});    
    }
}                        