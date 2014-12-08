<?php
/**
* Initial class for mondira
*
* @package Wordpress
* @subpackage Mondira
* @version 1.0
* @last updated 18 Apr, 2014
*/
if(!class_exists('Mondira')){
    class Mondira {
	    public $theme_config = array();
        
        
	    function init($options) {
		    
		    $this->constants($options);
		    
		    $this->setup();
            
		    $this->loadCore();
             
		    $this->functions();
		    
            $this->admin();
	    }
        
        function constants($options) {
            //Checking if the theme is for settings available if not then set it as false by default
            if(empty($options['settings_available'])){
                $options['settings_available'] = false;    
            }
            
            //Checking if the theme is for documentation available if not then set it as false by default
            if(empty($options['documentation_available'])){
                $options['documentation_available'] = false;    
            }
            $this->theme_config = $options;
            
            define('THEME_NAME', $options['theme_name']);
            define('THEME_SLUG', $options['theme_slug']);
            define('THEME_DIR', get_template_directory());
            define('THEME_URI', get_template_directory_uri());
            define('THEME_FRAMEWORK', THEME_DIR . '/functions/core');
            define('THEME_FRAMEWORK_URI', THEME_URI . '/functions/core');
            
            require_once( THEME_FRAMEWORK . '/constants.php' );  
        }
        
        function setup(){
            add_action('after_setup_theme', array(&$this, 'mondira_add_theme_supports'));
        }
	    
	    function loadCore(){
            require_once( FRAMEWORK_ADMIN_GENERAL_CORE . '/MondiraThemeHelper.php' );    
            require_once( FRAMEWORK_ADMIN_HELPERS . '/MondiraThemeHtmlHelper.php' );    
            require_once( FRAMEWORK_ADMIN_HELPERS . '/MondiraThemeMetabox.php' );    
        }
        
	    function mondira_add_theme_supports() {
		    if (function_exists('add_theme_support')) {
                if(!empty($_REQUEST['post'])){
                    $mondira_post_type = get_post_type($_REQUEST['post']);
                    if (!empty($mondira_post_type)){
                        add_theme_support('post-thumbnails', array('post', 'page', $mondira_post_type));
                    } else {
                        add_theme_support('post-thumbnails', array('post', 'page'));
                    }
                } else if(!empty($_REQUEST['post_type'])) {
                    $mondira_post_type = $_REQUEST['post_type'];
                    add_theme_support('post-thumbnails', array('post', 'page', $mondira_post_type));
                } else {
                    add_theme_support('post-thumbnails', array('post', 'page'));
                }   
                
                $post_formats = array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'video' );                    
                if ( !empty( $_REQUEST['post'] ) ) {
                    $post_type = get_post_type($_REQUEST['post']);
                    $post_formats = apply_filters( 'mondira_post_formats_'.$post_type, $post_formats );  
                } else if ( !empty( $_REQUEST['post_type'] ) ) {
                    $post_type = $_REQUEST['post_type'];
                    $post_formats = apply_filters( 'mondira_post_formats_'.$post_type, $post_formats );  
                } 
				add_theme_support( 'post-formats', $post_formats );   
                
                add_theme_support('menus');
                add_theme_support('automatic-feed-links');
		    }
	    }
	            
	    function functions() {
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/general.php')) {
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/general.php' );    
            } 
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/templates.php')){
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/templates.php' );    
            }                             
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/pagination.php')){
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/pagination.php' );    
            }
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/aqua-resizer.php')){
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/aqua-resizer.php' );    
            }
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/aqua-resizer-ratio-check.php')) {
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/aqua-resizer-ratio-check.php' );    
            }     
            if(file_exists(FRAMEWORK_GENERAL_FUNCTIONS . '/framework-support.php')) {
                require_once( FRAMEWORK_GENERAL_FUNCTIONS . '/framework-support.php' );    
            }                                                                                                                 
            $this->options();
	    }
	    
	    function options() {
		    global $mondira_options, $mondira_theme_settings;
            $mondira_options = array();
			if(file_exists(THEME_OPTIONS . '/mondira-settings.php')){
                $page = include_once(THEME_OPTIONS . '/mondira-settings.php');
                $page = $mondira_theme_settings;
                if(!empty($page) && is_array($page) && !empty($page['list']) && is_array($page['list'])){
                    foreach($page['list'] as $option) {
                        $opt_array = (array) get_option(THEME_SLUG . '_' . $option['section']);
                        if(!empty($mondira_options[$option['section']]))
                        $opt_index_array = (array) $mondira_options[$option['section']];
                        else
                        $opt_index_array = array();                
                        $mondira_options[$option['section']] = array_merge($opt_index_array, $opt_array);
                    }  
                }
            }
	    }
	    
        function admin() {
		    if (is_admin()) {
                if(file_exists(FRAMEWORK_ADMIN . '/mondira_admin.php')){
                    include_once( FRAMEWORK_ADMIN . '/mondira_admin.php' );   
			        $admin = new Mondira_admin();
			        $admin->init( $this->theme_config['theme_name'], $this->theme_config['theme_slug'], $this->theme_config, $this->theme_config['settings_available'], $this->theme_config['documentation_available'] );
                }
		    }
	    }
    }
}
