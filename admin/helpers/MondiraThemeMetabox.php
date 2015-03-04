<?php
/*
* 
* This Class is to process custom post type or post/page meta informations
* 
* @since version 1.0
* @last modified 24 Sep, 2014
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
* 
*/

if ( !class_exists( 'MondiraThemeMetabox' ) ) {
    class MondiraThemeMetabox extends MondiraThemeHelper {
        var $config;
        var $options;
        var $html;
        
        
        public function init() {
            $this->metaConfig = $this->info['config'];
            $this->options = $this->info['options'];
            add_action('admin_menu', array(&$this, 'generate'));
            add_action('save_post', array(&$this, 'savemeta'));            
        }
        
        
        public function generate() {
            if (function_exists('add_meta_box')) {
                
                if(empty($this->metaConfig['page']) || empty($this->metaConfig['id'])) 
                    return false;
                
                add_meta_box($this->metaConfig['id'], $this->metaConfig['title'], array(&$this, 'generate_html_field'), $this->metaConfig['page'], !empty($this->metaConfig['context'])?$this->metaConfig['context']:'normal', 'high');
                
            }
        }

        function savemeta($post_id) {
            if (! isset($_POST[$this->metaConfig['id'] . '_noncename'])) {
                return $post_id;
            }
            
            if (! wp_verify_nonce($_POST[$this->metaConfig['id'] . '_noncename'], plugin_basename(__FILE__))) {
                return $post_id;
            }
            
            if ('page' == $_POST['post_type']) {
                if (! current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } else {
                if (! current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }
            
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
            
            $slug = $this->metaConfig['id'];
            if(empty($slug))
                return false;
                
            foreach($this->options as $option) {
                
                if (isset($option['id']) && ! empty($option['id'])) {
                    
                    if (isset($_POST[$slug . $option['id']])) {
                        switch ($option['type']) {
                            case 'select_multi':              
                                //$value = implode( ',', $_POST[ $slug . $option['id'] ] );
								$tmp = array();
								foreach( $_POST[ $slug . $option['id'] ] as $k => $v ){
									foreach( $v as $index => $values ) {
										$tmp[] = $values;
									}
								}
								$value = implode( ',', $tmp );
                                break;
                            case 'select':
                                if(is_array($_POST[$slug . $option['id']])){
                                    $value = array_unique(explode(',', $_POST[$slug . $option['id']]));
                                } else {
                                    $value = $_POST[$slug . $option['id']];
                                }
                                break;
                            default:
                                $value = $_POST[$slug . $option['id']];
                        }
                    } else {
                        $value = false;
                    }   
                    
                    
                    //Fix for WP 3.9 as It is not taking any id as array for wp_editor
                    if( $option['type'] == 'editor' ) {
                        if( !empty( $_POST[ $option['id'] ]) ) {
                            $value = $_POST[ $option['id'] ];  
                        } else {
                            $value = false;
                        }
                        $option_id = $option['id'];    
                    } else {
                        $option_id = $slug . $option['id'];
                    }  
                    
                    if (get_post_meta($post_id, $option_id) == "") {
                        add_post_meta($post_id, $slug . $option['id'], $value, true);
                    } elseif ($value != get_post_meta($post_id, $option_id, true)) {
                        update_post_meta($post_id, $slug . $option['id'], $value);
                    } elseif ($value == "") {
                        delete_post_meta($post_id, $slug . $option['id'], get_post_meta($post_id, $slug . $option['id'], true));
                    }
                }
            }
        }
        
        
        function generate_html_field() {
            global $post;
            
            $slug = $this->metaConfig['id'];
            $this->html = new MondiraThemeHtml(array('slug'=>$slug, 'field_type'=>'meta'));
            echo $this->html->tableStart();
            
            foreach($this->options as $option) {
                if (method_exists($this, $option['type'])) {
                    if (isset($option['id'])) {         
                        $default = get_post_meta($post->ID, $slug . $option['id'], true);
                        if ($default != "") {
                            $option['default'] = $default;
                        }
                    }
                    $this->$option['type']($option);
                }
            }
            
            echo $this->html->tableEnd();
            
            echo '<input type="hidden" name="' . $this->metaConfig['id'] . '_noncename" id="' . $this->metaConfig['id'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
            
        }
        
        
        function text($value=array()) {
            echo $this->html->formTableInput(array('title'=>$value['title'], 'name'=>$value['id'], 'value'=>$value['default'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'type'=>'text', 'id'=>$value['id'], 'class'=>!empty($value['class'])?$value['class']:'', 'avoid_br'=>!empty($value['avoid_br'])?$value['avoid_br']:'no'), $description = $value['desc']);
        }
        
        function dated($value=array()) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'mondira-datepicker-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);

            //wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
            echo $this->html->formTableInput(array('title'=>$value['title'], 'name'=>$value['id'], 'value'=>$value['default'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'type'=>'text', 'id'=>$value['id'], 'class'=>'datepicker', 'avoid_br'=>!empty($value['avoid_br'])?$value['avoid_br']:'no'), $description = $value['desc']);
        }
        
        function textarea($value=array()) {
			echo $this->html->formTableTextarea(array('title'=>$value['title'], 'name'=>$value['id'], 'value'=>$value['default'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'type'=>'textarea', 'id'=>$value['id'], 'class'=>!empty($value['class'])?$value['class']:'regular-text', 'avoid_br'=>!empty($value['avoid_br'])?$value['avoid_br']:'no'), $description = $value['desc']);
        }
        
        function editor($value=array()) {
            echo $this->html->formTableTextarea(array('title'=>$value['title'], 'name'=>$value['id'], 'value'=>$value['default'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'type'=>'editor', 'id'=>$value['id'], 'class'=>!empty($value['class'])?$value['class']:'regular-text', 'avoid_br'=>!empty($value['avoid_br'])?$value['avoid_br']:'no'), $description = $value['desc']);
        }
        
        function checkbox($value=array()){
            echo $this->html->formTableCheckbox(array('title'=>$value['title'], 'name'=>$value['id'], 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'checked'=>$value['default']), $description = $value['desc']);
        }
        
        function color($value=array()){
            echo $this->html->formTableColor(array('title'=>$value['title'], 'name'=>$value['id'], 'value'=>$value['default'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'data-default-color'=>$value['data-default-color']));
        }   
         
        function select($value=array()){
            
            if(!empty($value['source'])){
                
                if(!empty($value['source']) && $value['source']=='page' && !empty($value['nasted']) && $value['nasted']=='yes'){
                    $selectString = '<option value="">Select Page</option>';
                    $selected = ($target == "page")?$target_value:0;
                    $args = array(
                        'depth' => 0, 'child_of' => 0,
                        'selected' => $selected, 'echo' => 1,
                        'name' => 'page_id', 'id' => '',
                        'show_option_none' => '', 'show_option_no_change' => '',
                        'option_none_value' => ''
                    );
                    $pages = get_pages($args);
                    $selectString.= walk_page_dropdown_tree($pages,$args['depth'],$args);
                    
                    echo $this->html->formTableSelect(array('options'=>$selectString, 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'name'=>$value['id'], 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default'], 'nasted'=>'yes'), $description=$value['desc']);    
                } else if($value['source']=='users'){
                    
                    $args = array(
                        'show_option_all'         => null, // string
                        'show_option_none'        => null, // string
                        'hide_if_only_one_author' => null, // string
                        'orderby'                 => 'display_name',
                        'order'                   => 'ASC',
                        'include'                 => null, // string
                        'exclude'                 => null, // string
                        'multi'                   => false,
                        'show'                    => 'display_name',
                        'echo'                    => false,
                        'selected'                => $value['default'],
                        'include_selected'        => true,
                        'name'                    => $this->metaConfig['id'] . $value['id'], // string
                        'id'                      => $this->metaConfig['id'] . $value['id'], // integer
                        'class'                   => $value['class'], // string 
                    );
                    $select_box = wp_dropdown_users( $args );
                    echo $this->html->formTableSelect(array('field'=>$select_box, 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'name'=>$value['id'], 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default']), $description=$value['desc']);    
                } else {
                    echo $this->html->formTableSelect(array('options'=>$this->get_wpdb_options($value['source'], $value['post_type']), 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'empty'=>'Please select...', 'name'=>$value['id'], 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default']), $description=$value['desc']);    
                }
                
            } else {
                echo $this->html->formTableSelect(array('options'=>$value['options'], 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'name'=>$value['id'], 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default']), $description=$value['desc']);    
            }
            
            
        }
        
        function select_multi($value=array()){
            if(!empty($value['source'])){
                if(!empty($value['source']) && $value['source']=='page' && !empty($value['nasted']) && $value['nasted']=='yes'){
                    $selectString = '<option value="">Select Page</option>';
                    $selected = ($target == "page")?$target_value:0;
                    $args = array(
                        'depth' => 0, 'child_of' => 0,
                        'selected' => $selected, 'echo' => 1,
                        'name' => 'page_id', 'id' => '',
                        'show_option_none' => '', 'show_option_no_change' => '',
                        'option_none_value' => ''
                    );
                    $pages = get_pages($args);
                    $selectString.= walk_page_dropdown_tree($pages,$args['depth'],$args);
                    
                    echo $this->html->formTableSelect(array('options'=>$selectString, 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'multiple'=>'multiple', 'name'=>$value['id'].'[]', 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default'], 'nasted'=>'yes'), $description=$value['desc']);    
                } else if($value['source']=='users'){
                    
                    $args = array(
                        'show_option_all'         => null, // string
                        'show_option_none'        => null, // string
                        'hide_if_only_one_author' => null, // string
                        'orderby'                 => 'display_name',
                        'order'                   => 'ASC',
                        'include'                 => null, // string
                        'exclude'                 => null, // string
                        'multi'                   => false,
                        'show'                    => 'display_name',
                        'echo'                    => false,
                        'selected'                => $value['default'],
                        'include_selected'        => true,
                        'name'                    => $this->metaConfig['id'] . $value['id'], // string
                        'id'                      => $this->metaConfig['id'] . $value['id'], // integer
                        'class'                   => $value['class'], // string 
                    );
                    $select_box = wp_dropdown_users( $args );
                    echo $this->html->formTableSelect(array('field'=>$select_box, 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'multiple'=>'multiple', 'name'=>$value['id'].'[]', 'id'=>$value['id'], 'selected'=>$value['default']), $description=$value['desc']);    
                } else {
					echo $this->html->formTableSelect(array('options'=>$this->get_wpdb_options($value['source'], $value['post_type']), 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'multiple'=>'multiple', 'empty'=>'Please select...', 'name'=>$value['id'].'[]', 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default']), $description=$value['desc']);    
                }
                
            } else {
                echo $this->html->formTableSelect(array('options'=>$value['options'], 'class'=>!empty($value['class'])?$value['class']:'', 'title'=>$value['title'], 'multiple'=>'multiple', 'name'=>$value['id'].'[]', 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'selected'=>$value['default']), $description=$value['desc']);    
            }
            
            
        }
        
        function upload($value=array()){
            echo $this->html->formTableInput(array('upload'=>$value['upload'], 'title'=>$value['title'], 'name'=>$value['id'], 'avoid_br'=>!empty($value['avoid_br'])?$value['avoid_br']:'no', 'type'=>'text', 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'class'=>'regular-text', 'value'=>!empty($value['default'])?$value['default']:''), $description=!empty($value['desc'])?$value['desc']:'');
        }
        
        function upload_zip($value=array()){
            echo $this->html->formTableInput(array('upload_zip'=>$value['upload_zip'], 'title'=>$value['title'], 'name'=>$value['id'], 'avoid_br'=>$value['avoid_br'], 'type'=>'text', 'id'=>$value['id'], 'data-fold'=>!empty($value['data-fold'])?$value['data-fold']:'', 'class'=>'regular-text', 'value'=>!empty($value['default'])?$value['default']:''), $description=!empty($value['desc'])?$value['desc']:'');
        }
        
        function html($value=array()){
            echo $this->html->formTableHtml(array('html'=>$value['html']));
        }
        
        function get_wpdb_options($type, $post_type = null) {
            
            $options = array();
            switch($type){
               case 'page':
                    $entries = get_pages('title_li=&orderby=name&suppress_filters=0');
                    foreach($entries as $key => $entry) {
                        $options[$entry->ID] = $entry->post_title;
                    }
                    break;
                case 'cat':
                    $entries = get_categories('title_li=&orderby=name&hide_empty=0&suppress_filters=0');
                    foreach($entries as $key => $entry) {
                        $options[$entry->term_id] = $entry->name;
                    }
                    break;
                case 'post':
                    $entries = get_posts('orderby=title&numberposts=-1&order=ASC&suppress_filters=0');
                    foreach($entries as $key => $entry) {
                        $options[$entry->ID] = $entry->post_title;
                    }
                    break;
                case 'custom':
                    $entries = get_posts('post_type='.$post_type.'&orderby=title&numberposts=-1&order=ASC&suppress_filters=0');
                    foreach($entries as $key => $entry) {
                        $options[$entry->ID] = $entry->post_title;
                    }
                    break;
                case 'custom-tax':
                    $entries = get_categories('taxonomy='.$post_type.'&title_li=&orderby=name&hide_empty=1&suppress_filters=0');
                    foreach($entries as $key => $entry) {
                        $options[$entry->term_id] = $entry->name;
                    }
                    break;
            }
            
            return $options;
        }
    }
}
