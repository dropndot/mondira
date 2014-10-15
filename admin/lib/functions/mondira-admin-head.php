<?php        
if (!function_exists('mondira_admin_head')) { 
    function mondira_admin_head() {
        ?>
        <script>
        var FRAMEWORK_ADMIN_RESOURCES_URI = "<?php echo FRAMEWORK_ADMIN_RESOURCES_URI;?>";   
        </script>
        <?php
    } 
}
add_action('admin_head', 'mondira_admin_head');


if (!function_exists('load_mondira_admin_enqueue_script')) {
    function load_mondira_admin_enqueue_script() {
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-effects-core', false, array('jquery'), '1.0', true);
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-tabs'); 
        wp_enqueue_script('wp-color-picker'); 
        wp_enqueue_script('mondira-admin-jquery-ibutton-script', FRAMEWORK_ADMIN_JS . '/plugins/jquery.ibutton.js', array('jquery'), '1.0', true );
        wp_enqueue_script('mondira-admin-jquery-rangeinput-script', FRAMEWORK_ADMIN_JS . '/plugins/jquery.rangeinput.js', array('jquery'), '1.0', true );
        wp_enqueue_script('mondira-admin-jquery-chosen-script', FRAMEWORK_ADMIN_JS . '/plugins/chosen/chosen.jquery.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script('mondira-admin-jquery-magnific-popup-script', FRAMEWORK_ADMIN_JS . '/plugins/magnific-popup.js', array('jquery'), '1.0', true );
        
		//Code Editor JS
		wp_enqueue_script('mondira-admin-code-editor-ace', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/js/ace/ace.js', array('jquery'), '1.0', true  );
		wp_enqueue_script('mondira-admin-code-editor-ext-emmet', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/js/ace/ext-emmet.js', array('jquery'), '1.0', true  );
		wp_enqueue_script('mondira-admin-code-editor-emmet', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/js/ace/emmet.js', array('jquery'), '1.0', true  );
		wp_enqueue_script('mondira-admin-code-editor-jquery.ui.position', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/js/jquery.ui.position.min.js', array('jquery'), '1.0', true  );
		wp_enqueue_script('mondira-admin-code-editor-cloudEdit', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/js/cloudEdit.js', array('jquery'), '1.0', true  );
		
		wp_enqueue_script('mondira-admin-script', FRAMEWORK_ADMIN_JS . '/mondira-admin.js', array('jquery'), '1.0', true );
        wp_enqueue_script('mondira-custom-admin-js', get_template_directory_uri() . '/resources/js/custom-admin.js', array('jquery'), '1.2', true );
        
        // Localize the script with template uri.
        $post_type = 'post';
        if ( !empty( $_REQUEST['post'] ) ) {
            $post_type = get_post_type($_REQUEST['post']);
        } else if ( !empty( $_REQUEST['post_type'] ) ) {
            $post_type = $_REQUEST['post_type'];          
        } 
        $translation_array = array( 'template_directory_uri' => get_template_directory_uri(), 'post_type' => $post_type );
        wp_localize_script( 'mondira-custom-admin-js', 'theme', $translation_array );
        
        wp_enqueue_script('mondira-media-uploader', FRAMEWORK_ADMIN_JS . '/mondira-image-uploader-3.5.js');
        add_thickbox();
        
        
    }
}
if(is_admin()){
	add_action('admin_enqueue_scripts', 'load_mondira_admin_enqueue_script');
}


if (!function_exists('load_admin_enqueue_style')) {
    function load_admin_enqueue_style() {
        wp_enqueue_style('wp-color-picker');    
        wp_enqueue_style('mondira-admin-jquery-ibutton-style', FRAMEWORK_ADMIN_CSS . '/jquery.ibutton.css');
        wp_enqueue_style('mondira-admin-jquery-rangeinput-style', FRAMEWORK_ADMIN_CSS . '/jquery.rangeinput.css');
		wp_enqueue_style('mondira-admin-jquery-chosenx-style', FRAMEWORK_ADMIN_JS . '/plugins/chosen/chosen.css' );
		wp_enqueue_style('mondira-admin-magnific-popup-style', FRAMEWORK_ADMIN_CSS . '/magnific-popup.css' );
		
		//Code Editor CSS
		wp_enqueue_style('mondira-admin-code-editor-cloudEdit', FRAMEWORK_ADMIN_RESOURCES_URI . '/lib/code-editor/css/cloudEdit.css' );
		
		wp_enqueue_style('mondira-theme-admin-style', FRAMEWORK_ADMIN_CSS . '/style.css');        
    }
}
if(is_admin()){
	add_action('admin_enqueue_scripts', 'load_admin_enqueue_style');
}

if (!function_exists('mondira_admin_tinymce_dialog')) {
    function mondira_admin_tinymce_dialog() {       
        if (function_exists('add_thickbox')) add_thickbox();
        wp_print_scripts('media-upload');
        wp_admin_css();
        wp_enqueue_script('utils');
    }
}

if (!function_exists('mondira_admin_tinymce')) {
    function mondira_admin_tinymce() { 
        wp_print_scripts('editor');
        if (function_exists('add_thickbox')) add_thickbox();
        wp_print_scripts('media-upload');
        //if (function_exists('wp_tiny_mce')) wp_tiny_mce();
        wp_admin_css();
        wp_enqueue_script('utils');
    }
}

if(is_admin()){
    add_filter('admin_head','mondira_admin_tinymce');
}