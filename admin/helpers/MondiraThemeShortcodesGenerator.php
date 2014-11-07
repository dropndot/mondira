<?php
/*
* 
* This Class is to generate shortcode 
* 
* @since version 1.0
* @last modified 19 Oct, 2014
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
* 
*/ 
if ( !class_exists( 'MondiraThemeShortcodesGenerator' ) ) {
	class MondiraThemeShortcodesGenerator {
        var $shortcodes = array();
        var $html;
        
        public function init() {
            add_action( 'media_buttons', array( &$this, 'mondira_editor_buttons' ), 100 );            
            add_action( 'admin_footer', array( &$this, 'generated_shortcodes_html' ) );            
        }
		
		public function mondira_editor_buttons() {
			if( is_admin() ) { 
				echo "<a class='button mondira-shortcode-generator' href='#mondira-shortcode-generator'>Theme Shortcodes</a>";
			}
		}
		
		public function mondira_addshortcode( $key, $attr ) {
			$this->shortcodes[$key]=$attr;
		}
		
		public function mondira_shortcode_fields( $name = '', $attr_option = array(), $shortcode = '', $unique_option_id = '' ) {
			$shortcode_field_html = $desc = $class = $postfix = $suffix = $value = '';
			
			if ( empty( $unique_option_id ) ) {
				$unique_option_id = time();
			}
			
			if( !empty( $attr_option['html'] ) ) {
				$shortcode_field_html = $attr_option['html'];
				return $shortcode_field_html;
			}
			
			
			if( !empty( $attr_option['desc'] ) ) {
				$desc = '<p class="description">'.$attr_option['desc'].'</p>';
			} else if( !empty( $attr_option['description'] ) ) {	//Adding support for Visual Composer default attributes
				$desc = '<p class="description">'.$attr_option['description'].'</p>';
			} 
			
			if( !empty( $attr_option['class'] ) ) {
				$class = $attr_option['class'];
			}
			if( !empty( $attr_option['postfix'] ) ) {
				$postfix = '<span class="postfix">' . $attr_option['postfix'] . '</span>';
			}
			if( !empty( $attr_option['suffix'] ) ) {
				$suffix = '<span class="suffix">' . $attr_option['suffix'] . '</span>';
			}
			if( !empty( $attr_option['value'] ) ) {
				$value = $attr_option['value'];
			} 
			
			$label = '';
			if ( !empty( $attr_option['title'] ) ) {
				$label = $attr_option['title'];
			} else if ( !empty( $attr_option['heading'] ) ) {
				$label = $attr_option['heading'];	//Adding support for Visual Composer default attributes
			}
			
			//Shortcode options dependency control
			$dependency_element = $dependency_is_empty = $dependency_not_empty = $dependency_values = '';
			if ( !empty( $attr_option['dependency'] ) && is_array( $attr_option['dependency'] ) ) {
				$display = 'none';
				
				$dependency = $attr_option['dependency'];
				if ( !empty( $dependency['element'] ) ) {
					$dependency_element = $dependency['element'];
				} else {
					$dependency_element = '';
				}
				
				if ( !empty( $dependency['is_empty'] ) && $dependency['is_empty'] ) {
					$dependency_is_empty = 'true';
				} else {
					$dependency_is_empty = 'false';
				}
				
				if ( !empty( $dependency['not_empty'] ) && $dependency['not_empty'] ) {
					$dependency_not_empty = 'true';
				} else {
					$dependency_not_empty = 'false';
				}
				
				if ( !empty( $dependency['values'] ) && $dependency['values'] ) {
					$dependency_values = implode( '|', $dependency['values'] );
				} else {
					$dependency_values = '';
				}
				
			} else {
				$display = 'block';
			}
			
			
			switch( $attr_option['type'] ) {
				case 'radio':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><strong>'.$label.' </strong></div><div class="content">';
						foreach( $attr_option['opt'] as $val => $title ){
							(isset($attr_option['def']) && !empty($attr_option['def'])) ? $def = $attr_option['def'] : $def = '';
							 $shortcode_field_html .= '
								<label for="shortcode-option-'.$shortcode.'-'.$name.'-'.$val.'">'.$title.'</label>
								<input class="attr" type="radio" data-attrname="'.$name.'" name="'.$shortcode.'-'.$name.'" value="'.$val.'" id="shortcode-option-'.$shortcode.'-'.$name.'-'.$val.'"'. ( $val == $def ? ' checked="checked"':'').'>';
						}
						$shortcode_field_html .=  $postfix . ' ' . $desc . '</div>
					</div>';
					break;
					
				case 'checkbox':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="' . $name . '"><strong>' . $label . ' </strong></label></div>    
						<div class="content container-checkbox">' . $suffix; 

						if( !empty( $attr_option['value'] ) ) {
							$checkbox_value = $attr_option['value'];
						} else if( !empty( $attr_option['values'] ) ) {
							$checkbox_value = $attr_option['values'];
						}
						
						if( !empty( $checkbox_value ) && !is_array( $checkbox_value ) ) {
							$shortcode_field_html .= '<input type="checkbox" data-attrname="'.$name.'" value="'.$checkbox_value.'" class="skip-it input-checkbox-to-text ' . $name . '" id="' . $name . '" />';
						} else if( !empty( $checkbox_value ) && is_array( $checkbox_value ) ) {
							foreach( $checkbox_value as $k => $v ) {
								$shortcode_field_html .= '<input type="checkbox" data-attrname="'.$name.'" value="'.$k.'" class="skip-it input-checkbox-to-text ' . $name . '" id="' . $name . '" /><span class="checkbox-label">' . $v . '</span>';
							}
						}
						$shortcode_field_html .='<input data-display="none" class="attr '.$class.'" type="text" data-attrname="'.$name.'" value="" id="checkbox-val-' . $name . '" />';
						
						$shortcode_field_html .=  $postfix . ' ' . $desc. '</div>
					</div>';
					break;	
				
				case 'select':
				case 'dropdown': //Adding support for Visual Composer default attributes
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-select">' . $suffix . '<select data-attrname="'.$name.'" id="'.$name.'">';
						if ( !empty( $attr_option['values'] ) ) {
							$values = $attr_option['values'];
						} else if ( !empty( $attr_option['value'] ) ) {
							$values = $attr_option['value'];
						} else {
							$values = array();
						}
						foreach( $values as $key=>$value ){
							$shortcode_field_html .= '<option value="'.$key.'">'.$value.'</option>';
						}
						$shortcode_field_html .= '</select>' . $postfix . ' '  . $desc . '</div>
					</div>';
					break;
				
				case 'multi-select':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-multi-select">' . $suffix . '<select data-attrname="'.$name.'" multiple="multiple" id="'.$name.'">';
						$values = $attr_option['values'];
						foreach( $values as $k => $v ){
							$shortcode_field_html .= '<option value="'.$k.'">'.$v.'</option>';
						}
						$shortcode_field_html .= '</select>' . $postfix . ' '  . $desc . '</div>
					</div>';
					break;
					
				case 'textarea':
					if( !empty( $attr_option['value'] ) ) {
						$textarea_value = $attr_option['value'];
					} else if( !empty( $attr_option['values'] ) ) {
						$textarea_value = $attr_option['values'];
					} else {
						$textarea_value = '';
					}
				
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-textarea">' . $suffix . '<textarea data-attrname="'.$name.'">'.$textarea_value.'</textarea> ' . $postfix . ' '  . $desc . '</div>
					</div>';
					break;
						
				case 'color':
				case 'colorpicker': //Adding support for Visual Composer default attributes
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-color">' . $suffix . '<input class="attr wp-color-picker '.$class.'" type="text" data-attrname="'.$name.'" value="" />' . $postfix . ' '  . $desc . '</div>
					</div>';
					break;
				
				case 'number':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-number">' . $suffix . '<input class="attr '.$class.'" type="number" data-attrname="'.$name.'" value="'.$value.'" />' . $postfix . ' ' . $desc . '</div>
					</div>';
					break;
				
				case 'attach_image':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
					<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-image">' . $suffix . '<input class="attr mondira-upload-input '.$class.'" type="text" id="'.$name.'" data-attrname="'.$name.'" value="'.$value.'" />' . $postfix;
					$shortcode_field_html .= $desc;
					$shortcode_field_html.= '
						<br class="clearfix" />
						<div class="mondira-media-upload-button">
							<a class="upload_image_button_latest button theme-upload-button" data-target="'.$name.'" id="'.$name.'_thickbox" href="media-upload.php?post_id=0&target='.$name.'&mondira_image_upload=1&type=image&TB_iframe=1&width=640&height=644">Upload</a><br />';
					
					$shortcode_field_html.= '<div id="'.$name.'_preview" class="mondira-media-preview"></div>';
					$shortcode_field_html.= '</div>';
					$shortcode_field_html.= '</div>';
					$shortcode_field_html.= '</div>';
					break;
				
				case 'attach_file':
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
					<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-image">' . $suffix . '<input class="attr mondira-upload-input '.$class.'" type="text" id="'.$name.'" data-attrname="'.$name.'" value="'.$value.'" />' . $postfix;
					$shortcode_field_html .= $desc;
					$shortcode_field_html.= '
						<br class="clearfix" />
						<div class="mondira-media-upload-button">
							<a class="upload_image_button_latest button theme-upload-button" data-target="'.$name.'" id="'.$name.'_thickbox" href="media-upload.php?post_id=0&target='.$name.'&mondira_image_upload=1&type=file&TB_iframe=1&width=640&height=644">Upload/Choose One</a><br />';
					$shortcode_field_html.= '</div>';
					$shortcode_field_html.= '</div>';
					$shortcode_field_html.= '</div>';
					break;
				
				case 'text':
				case 'textfield': //Adding support for Visual Composer default attributes
				default:
					if( !empty( $attr_option['value'] ) ) {
						$text_value = $attr_option['value'];
					} else if( !empty( $attr_option['values'] ) ) {
						$text_value = $attr_option['values'];
					} else {
						$text_value = '';
					}
					$shortcode_field_html .= '
					<div class="mondira-shortcode-option" id="mondira-shortcode-option-'.$unique_option_id.'" data-shortcode="'.$shortcode.'" data-dependency_element="'.$dependency_element.'" data-dependency_is_empty="'.$dependency_is_empty.'" data-dependency_not_empty="'.$dependency_not_empty.'" data-dependency_values="'.$dependency_values.'" data-display="'.$display.'">
						<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$label.' </strong></label></div>
						<div class="content container-text">' . $suffix . '<input class="attr '.$class.'" type="text" data-attrname="'.$name.'" value="'.$text_value.'" />' . $postfix . ' '  . $desc . '</div>
					</div>';
					break;
			}
			
			//$shortcode_field_html .= '<div class="clear"></div>';
			
			return $shortcode_field_html;
		}


			
		public function generated_shortcodes_html()	{
			if ( empty( $this->shortcodes ) && is_array( $this->shortcodes ) ) {
				return '';
			}		
			$content_html = $option_html = '';
			ob_start();			
			if ( !empty( $this->shortcodes ) && is_array( $this->shortcodes ) ) {
				$i = 1;
				foreach( $this->shortcodes as $shortcode => $options ) {
					$title = '';
					if ( !empty( $options['title'] ) ) {
						$title = $options['title'];
					} else if ( !empty( $options['heading'] ) ) {
						$title = $options['heading'];	//Adding support for Visual Composer default attributes
					}
					if(strpos($shortcode,'header') !== false) {
						$option_html .= '<optgroup label="'.$title.'">';
					} else {
						$option_html .= '<option value="'.$shortcode.'">'.$title.'</option>';
						
						$content_html .= '<div class="shortcode-options" id="options-'.$shortcode.'" data-name="'.$shortcode.'" data-type="'.$options['type'].'">';
						if( !empty($options['attr']) ) {
							foreach( $options['attr'] as $name => $attr_option ) {
								if ( $name == 'nested_shortcode' ) {
									$nested_shortcode = $attr_option;
									
									if( !empty( $nested_shortcode['shortcode']) ) {
										$nshortcode = $nested_shortcode['shortcode'];
									} else {
										$nshortcode = 'nested';
									}
									
									
									if ( !empty( $nested_shortcode['title'] ) ) {
										$nshortcode_title = $nested_shortcode['title'];
									} else {
										$nshortcode_title = 'Nested Shortcode';
									}
									
									if ( !empty( $nested_shortcode['attr'] ) ) {
										//Nested inner options index
										$j = 0;
									
										//Nested shortcode container started
										$nattr_option = array( 'html' => '<div class="nested_shortcode" data-nested-shortcode="'.$nshortcode.'"><div class="set-of-nested-shortcode-options">' );	
										$content_html .= $this->mondira_shortcode_fields( '', $nattr_option, $shortcode, $i . $j );
										$j++;
										
										//Nested shortcode content
										foreach( $nested_shortcode['attr'] as $nname => $nattr_option ) {
											$content_html .= $this->mondira_shortcode_fields( $nname, $nattr_option, $nshortcode, $i . $j );
											$j++;
										}
										
										//Nested shortcode container ended
										$nattr_option = array( 'html' => '</div>' );	
										$content_html .= $this->mondira_shortcode_fields( '', $nattr_option, $shortcode, $i . $j );
										$j++;
										
										$nattr_option = array( 'html' => '<div class="inner_shortcode_buttons"><a href="#" class="inner_shortcode_addnew btn">Add New</a><a href="#" class="inner_shortcode_remove btn">Remove</a></div>' );	
										$content_html .= $this->mondira_shortcode_fields( '', $nattr_option, $shortcode, $i . $j );
										$j++;

										$nattr_option = array( 'html' => '</div>' );	
										$content_html .= $this->mondira_shortcode_fields( '', $nattr_option, $shortcode, $i . $j );
										$j++;
									}
									
								} else {
									$content_html .= $this->mondira_shortcode_fields( $name, $attr_option, $shortcode, $i );
								}
								$i++;
							}
						}	
						$content_html .= '</div>'; 
					}
				} 
			}
			?>			 
			<div id="mondira-shortcode-heading">
				<div id="mondira-shortcode-generator" class="mfp-hide mfp-with-anim">
					<div class="shortcode-content">
						<div id="mondira-shortcode-header">
							<div class="label"><strong>Theme Shortcodes</strong></div>
							<div class="content">
								<select class="chosen" id="mondira-shortcodes" data-placeholder="Choose a shortcode">
									<option></option>
									<?php echo $option_html; ?>
								</select>
							</div>
						</div>
						<?php echo $content_html; ?>
					</div>				
					<code class="shortcode_code">
						<span id="shortcode-opening-tag" style=""></span>
						<span id="shortcode-inner-content"></span>
						<span id="shortcode-closing-tag" style=""></span>
					</code>
					<a class="btn" id="mondira-insert-shortcode">Insert Shortcode</a>
				</div>
			</div>
		<?php 
			$output = ob_get_clean();
			echo $output;
		}         
    }
}
