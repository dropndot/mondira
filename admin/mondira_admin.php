<?php
/**
* 
* Admin core initial class file
* 
* @package WordPress
* @subpackage Mondira
* @version 1.0
* @last update 18 Apr, 2014
*/
if(!class_exists('Mondira_admin')){  
    class Mondira_admin {
        function init($name, $slug, $config, $settings_available=true, $documentation_available=false){
            
            $this->theme_name = $name;
            $this->theme_slug = $slug;
            $this->config = $config;
            $this->settings_available = $settings_available;
            $this->documentation_available = $documentation_available;
            
            add_action('admin_init', array(&$this,'load_core'));
            add_action('admin_menu', array(&$this,'menus'));
            add_action('admin_footer', array(&$this,'footer_script'));
            
            $this->functions(); 
        }
		
		function load_core(){
			require_once( FRAMEWORK_ADMIN_HELPERS . '/MondiraThemeShortcodesGenerator.php' );    
		}
        
        function menus(){
            if($this->settings_available){
                $mondira_settings_page = add_theme_page( THEME_NAME . ' Options', 'Theme Options', 'edit_theme_options', 'mondira-settings', array(&$this,'_mondira_load_options_page') );    
            } 
            if($this->documentation_available) {
                $mondira_documentation_page = add_theme_page( THEME_NAME . ' Documentation', 'Documentation', 'edit_theme_options', 'mondira-docs', array(&$this,'_mondira_load_docs_page') );    
            }
        }
        
        function mondira_admin_settings_head_script() { 
            
        }   
        
        function _mondira_load_options_page(){
            global $mondira_theme_settings;
            
            wp_enqueue_media();       
            require_once( FRAMEWORK_ADMIN_HELPERS . '/MondiraThemeSettingsGenerator.php' );                 
            
            $options = array();    
            
            if(file_exists(THEME_OPTIONS . "/" . $_GET['page'] . '.php')) {
                $options_public = require( THEME_OPTIONS . "/" . $_GET['page'] . '.php');   
                if(!empty($mondira_theme_settings)){
                    $options_public = $mondira_theme_settings;    
                }
            }
            
            //updating the options list
            if(!empty($options_public)){
                $options['title']=$options_public['title'];
                foreach($options_public['list'] as $key=>$value){
                     $options['list'][]=$value;
                }
            }
            
            new MondiraThemeSettingsGenerator( $this->config, $this->theme_slug, $options['title'], $options['list'] );
        }
        
        function _mondira_load_docs_page(){   
            global $mondira_theme_docs;
            
            require_once( FRAMEWORK_ADMIN_HELPERS . '/MondiraThemeDocsGenerator.php' );                 
            $options = array();    
            
            if(!empty($_GET['page']) && file_exists(THEME_DOCS . "/" . $_GET['page'] . '.php')) {   
                $options_public = require( THEME_DOCS . "/" . $_GET['page'] . '.php' );
                if(!empty($mondira_theme_docs)){
                    $options_public = $mondira_theme_docs;    
                }
            } 
        
            //updating the options list
            if(!empty($options_public)){
                $options['title']=$options_public['title'];
                foreach($options_public['docs'] as $key=>$value){
                     $options['docs'][]=$value;
                }
            } 
            
            new MondiraThemeDocsGenerator( $this->config, $this->theme_slug, $options['title'], $options['docs'] );
        }
        
        function functions(){           
            require_once( FRAMEWORK_ADMIN_GENERAL_FUNCTIONS . '/mondira-admin-general.php' );                 
            require_once( FRAMEWORK_ADMIN_GENERAL_FUNCTIONS . '/mondira-admin-head.php' );                 
            require_once( FRAMEWORK_ADMIN_GENERAL_FUNCTIONS . '/mondira-media-upload.php' );                 
	    } 
        
        function footer_script(){
            wp_enqueue_script('mondira-admin-footer-script', FRAMEWORK_ADMIN_JS . '/mondira-admin-footer.js');
        }
    }
}