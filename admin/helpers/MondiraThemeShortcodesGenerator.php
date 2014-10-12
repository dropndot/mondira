<?php
/*
* 
* This Class is to generate shortcode 
* 
* @since version 1.0
* @last modified 24 Sep, 2014
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
		
		public function mondira_add_shortcode( $key, $attr ) {
			$this->shortcodes[$key]=$attr;
		}
		
		public function mondira_shortcode_fields( $name, $attr_option, $shortcode ) {
			$shortcode_field_html = $desc = $class = '';
			
			if( !empty( $attr_option['desc'] ) ) {
				$desc = '<p class="description">'.$attr_option['desc'].'</p>';
			} 
			
			if( !empty( $attr_option['class'] ) ) {
				$class = $attr_option['class'];
			} 
			
			switch( $attr_option['type'] ) {
				case 'radio':
					$shortcode_field_html .= '<div class="label"><strong>'.$attr_option['title'].': </strong></div><div class="content">';
					foreach( $attr_option['opt'] as $val => $title ){
						(isset($attr_option['def']) && !empty($attr_option['def'])) ? $def = $attr_option['def'] : $def = '';
						 $shortcode_field_html .= '
							<label for="shortcode-option-'.$shortcode.'-'.$name.'-'.$val.'">'.$title.'</label>
							<input class="attr" type="radio" data-attrname="'.$name.'" name="'.$shortcode.'-'.$name.'" value="'.$val.'" id="shortcode-option-'.$shortcode.'-'.$name.'-'.$val.'"'. ( $val == $def ? ' checked="checked"':'').'>';
					}
					$shortcode_field_html .= $desc . '</div>';
					break;
					
				case 'checkbox':
					$shortcode_field_html .= '<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>    <div class="content"> <input type="checkbox" class="' . $name . '" id="' . $name . '" />'. $desc. '</div> ';
					break;	
				
				case 'select':
					$shortcode_field_html .= '<div class="label"><label for="'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
						<div class="content"><select id="'.$name.'">';
						$values = $attr_option['values'];
						foreach( $values as $key=>$value ){
							$shortcode_field_html .= '<option value="'.$key.'">'.$value.'</option>';
						}
					$shortcode_field_html .= '</select>' . $desc . '</div>';
					break;
				
				case 'multi-select':
					$shortcode_field_html .= '<div class="label"><label for="'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
						<div class="content"><select multiple="multiple" id="'.$name.'">';
						$values = $attr_option['values'];
						foreach( $values as $k => $v ){
							$shortcode_field_html .= '<option value="'.$k.'">'.$v.'</option>';
						}
					$shortcode_field_html .= '</select>' . $desc . '</div>';
					break;
					
				case 'textarea':
					$shortcode_field_html .= '<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
						<div class="content"><textarea data-attrname="'.$name.'"></textarea> ' . $desc . '</div>';
					break;
						
				case 'color':
					$shortcode_field_html .= '<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
						<div class="content"><input class="attr '.$class.'" type="text" data-attrname="'.$name.'" value="" />' . $desc . '</div>';
					break;
						
				case 'text':
				default:
					$shortcode_field_html .= '<div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
						<div class="content"><input class="attr '.$class.'" type="text" data-attrname="'.$name.'" value="" />' . $desc . '</div>';
					break;
			}
			
			$shortcode_field_html .= '<div class="clear"></div>';
			
			return $shortcode_field_html;
		}


			
		public function generated_shortcodes_html()	{
			if ( empty( $this->shortcodes ) && is_array( $this->shortcodes ) ) {
				return '';
			}		
			$content_html = $option_html = '';
			ob_start();			
			if ( !empty( $this->shortcodes ) && is_array( $this->shortcodes ) ) {
				foreach( $this->shortcodes as $shortcode => $options ) {
					if(strpos($shortcode,'header') !== false) {
						$option_html .= '<optgroup label="'.$options['title'].'">';
					} else {
						$option_html .= '<option value="'.$shortcode.'">'.$options['title'].'</option>';
						
						$content_html .= '<div class="shortcode-options" id="options-'.$shortcode.'" data-name="'.$shortcode.'" data-type="'.$options['type'].'">';
							if( !empty($options['attr']) ) {
								 foreach( $options['attr'] as $name => $attr_option ) {
									$content_html .= $this->mondira_shortcode_fields( $name, $attr_option, $shortcode );
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
