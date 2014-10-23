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
              'text-align' : 'center'
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
			appendText: "mm/dd/yyyy",
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
    //OLD METHOD
	// $('input[data-fold^="_"]').each(function(){
        // var $input_obj = $(this);         
        // var fold_id = $input_obj.data("fold");
        // if ( $('#'+fold_id).is(':checked') ) {
        // } else {
            // $input_obj.parents("tr").hide("slow");    
        // }
        // $('#'+fold_id).change(function(){
            // if($('#'+fold_id).is(':checked')){
                // $input_obj.parents("tr").show("slow");
            // } else {
                // $input_obj.parents("tr").hide("slow");    
            // }    
        // });
    // });  
	
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
			
			$('#'+$fold_id).change(function(){
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
			var $fold_id_list = $fold_str.split('#')[0];
			var $fold_value_list = $fold_str.split('#')[1];
			
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
	} ); 
	
	
	
	/*
	---------------------------------------------------------------------------------------
		Generating Shortcode for WP Editor
	---------------------------------------------------------------------------------------
	*/
	function generate_shortcode() {		
		var shortcode_name = $('#mondira-shortcodes').val();
		var shortcode_type = $('#options-'+shortcode_name).attr('data-type');
		var code = '[' + shortcode_name;
		
		/*
		---------------------------------------------------------------------------------------
			Checkbox field attr processing
		---------------------------------------------------------------------------------------
		*/    
		$('#options-'+shortcode_name+' input[type=checkbox]').each(function(){
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
		$('#options-'+shortcode_name+' textarea:not(".skip-it")').each(function(){
			 code += ' ' + $(this).attr('data-attrname')+'="'+ $(this).val() +'"';	
		});
		
		/*
		---------------------------------------------------------------------------------------
			Select field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' select:not("[multiple=multiple], .skip-it")').each(function(){
			 code += ' ' + $(this).attr('id')+'="' + $(this).attr('value') + '"';	
		});
				
		/*
		---------------------------------------------------------------------------------------
			Multiselect field attr processing
		---------------------------------------------------------------------------------------
		*/
		$('#options-'+shortcode_name+' select[multiple=multiple]').each(function(){
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
		$('#options-'+shortcode_name+' input.attr').each(function(){
			if ( $(this).hasClass('skip-it') ) {
				
			} else {
				if( $(this).attr('type') == 'text' ){ code += ' '+ $(this).attr('data-attrname')+'="'+ $(this).val()+'"'; }
				else { if($(this).attr('checked') == 'checked') code += ' '+ $(this).attr('data-attrname')+'="'+ $(this).val()+'"'; }
			}
		});
		
		code += ']';

		/*
		---------------------------------------------------------------------------------------
			Writing shortcode content on storage container
		---------------------------------------------------------------------------------------
		*/
		$( '#shortcode-opening-tag' ).html( code );
		$( '#shortcode-inner-content' ).html( '' );
		$( '#shortcode-closing-tag' ).html( '[/'+shortcode_name+']' );
	}
    
	/*
	---------------------------------------------------------------------------------------
		Inserting Shortcode in WP Editor
	---------------------------------------------------------------------------------------
	*/	
	$('#mondira-insert-shortcode').click(function(){
    	generate_shortcode();
		var $shortcode_string = $('#shortcode-opening-tag').text() + $('#shortcode-inner-content').text() + $('#shortcode-closing-tag').text() ;
		window.wp.media.editor.insert( $shortcode_string );
		$('#shortcode-opening-tag, #shortcode-inner-content, #shortcode-closing-tag').text( '' );
		$.magnificPopup.close();
		return false;
    });

	/*
	---------------------------------------------------------------------------------------
		Hiding and Showing shortcode options / attributes on the fly!
	---------------------------------------------------------------------------------------
	*/
    $('#mondira-shortcodes').change(function() {
		$('.shortcode-options').hide();
		$('#options-'+$(this).val()).show();
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
			
			$( '#options-' + $shortcode ).find( '.mondira-shortcode-option textarea, .mondira-shortcode-option input:text' ).each(function(){
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