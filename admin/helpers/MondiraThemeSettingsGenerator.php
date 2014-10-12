<?php
/*
* 
* This Class is to process Mondira Theme Option settings
* 
* @since version 1.0
* @last modified 18 Apr, 2014
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
* 
*/
if(!class_exists('MondiraThemeSettingsGenerator')){
    class MondiraThemeSettingsGenerator {
	    var $title;
	    var $docs;
        var $config;
        var $theme_slug;
	    function MondiraThemeSettingsGenerator( $config, $theme_slug, $title, $docs ) {
		    $this->config = $config;
            $this->theme_slug = $theme_slug;
            $this->title = $title;
            $this->docs = $docs;
            
            $this->processPost();
		    $this->render();
	    }
        
        // add slashes to html if magic quotes is not on
        function atf_slashit($stringvar){
            if (!get_magic_quotes_gpc()){
                $stringvar = addslashes($stringvar);
            }
            return $stringvar;
        }
        // remove slashes if magic quotes is on
        function atf_deslashit($stringvar){
            if (1 == get_magic_quotes_gpc()){
                $stringvar = stripslashes($stringvar);
            }
            return $stringvar;
        }
        
        function processDemoPortfolioItems() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            if ( !is_plugin_active( 'mondira-portfolio/index.php' ) ) { //Checking if mondira portfolio plugins installed otherwise skip it
                return false;    
            } 
            
            $the_query = new WP_Query( 
                array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => -1,
                    'orderby'=> 'menu_order date'
                )
            );
            
            while ( $the_query->have_posts() ) { 
                $the_query->the_post();       
                $format = get_post_format();
                if ( !empty( $format ) ) {
                    continue;   //Go for the next item
                } else {
                    $format = mondira_get_manual_checked_post_format( get_the_ID() );
                }  
                
                if ( !empty( $format ) ) {                       
                    set_post_format( get_the_ID(), $format ); //sets the given post to the $format format
                }
                
            }
            wp_reset_postdata();                          
        }
	    
        function processPost() { 
            global $wpdb;
            
            if ( !empty( $_POST[ 'slug' ] ) ) {
                
                $options = get_option( $this->theme_slug . '_' . $_POST[ 'slug' ] );
                $post_option_values_array = $_POST[ $_POST[ 'slug' ] ];
                
                
                //Updating media urls & Portfolio post formats for demo data
                if ( !empty( $post_option_values_array ) && !empty( $post_option_values_array[ '_initialize_demo_data' ] ) && $post_option_values_array[ '_initialize_demo_data' ] == 'yes' ) {
                    
                    $media_demo_base_url = $post_option_values_array[ 'media_demo_base_url' ];
                    
                    //Found media demo base url by theme-functions.php for current theme
                    if ( !empty( $media_demo_base_url ) ) {
                        $upload_dir = wp_upload_dir(); 
                        $media_upload_url = $upload_dir[ 'baseurl' ];
                        
                        $update_media_url_sql = "update {$wpdb->prefix}posts set guid = replace(guid, '{$media_demo_base_url}', '{$media_upload_url}/')";
                        $wpdb->query( $update_media_url_sql );
                        
                        $update_media_url_sql = "update {$wpdb->prefix}posts set post_content = replace(post_content, '{$media_demo_base_url}', '{$media_upload_url}/')";
                        $wpdb->query( $update_media_url_sql );
                         
                        $update_media_url_sql = "update {$wpdb->prefix}postmeta set meta_value = replace(meta_value, '{$media_demo_base_url}', '{$media_upload_url}/')";
                        $wpdb->query( $update_media_url_sql );                                                                                                        
                    }
                    
                    //Process manual imported portfolio demo items post format!
                    $this->processDemoPortfolioItems();
                } 
                
                //Validating $post_option_values_array
                $tmpArr = $post_option_values_array;
                foreach ( $post_option_values_array as $key => $value ) {
                    if( !is_array( $value) ) {
                        $tmpval = stripslashes( $value );
                        $tmpArr[ $key ] = $tmpval;
                    } else {
                        $tmpval = implode( ',', $value );
                        $tmpArr[ $key ] = $tmpval;
                    }
                }
                
                //Fix for WP 3.9 as It is not taking any id as array for wp_editor
                foreach ( $_POST as $key => $value ) {
                    if( !is_array( $value) ) {
                        $tmpval = stripslashes( $value );
                        $tmpArr[ $key ] = $tmpval;
                    } else {
                        /*$tmpval = implode( ',', $value );
                        $tmpArr[ $key ] = $tmpval;*/
                    }
                }
                
                //If the slug is from Export / Import tab so needs to process differently with import_settins post field
                if ( $_POST['slug'] == 'expimp' ) {
                    $str_arr = unserialize( $tmpArr[ 'import_settins' ] );
                    foreach ( $str_arr as $k => $v ) {
                        $tmpArr = array();
                        foreach ( $v as $key => $value ) {
                            $tmpval = stripslashes( $value );
                            $tmpArr[ $key ] = $tmpval;
                        }
                        $options = get_option( $this->theme_slug . '_' . $k );
                        if ( !empty( $options ) && is_array( $options ) ) {
                            update_option( $this->theme_slug . '_' . $k, $tmpArr );
                        } else {
                            add_option( $this->theme_slug . '_' . $k, $tmpArr);
                        }
                    }                        
                } else {
                
                    if ( !empty( $options ) && is_array( $options ) ) {
                        update_option( $this->theme_slug . '_' . $_POST[ 'slug' ], $tmpArr );
                    } else {
                        add_option( $this->theme_slug . '_' . $_POST[ 'slug' ], $tmpArr);
                    }
                    
                }

                
            }
        }
        
        function render() {
		    echo '<div class="wrap mondira-docs-page">';
		    echo '<div id="icon-'.$this->theme_slug.'" class="icon32 icon32-posts-'.$this->theme_slug.'"><br></div><h2>'.$this->title.'</h2>';
		    
		    echo '<div id="mondira-options-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all"><ul class="mondira-docs-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
            $i = 0;
		    foreach($this->docs as $docs) {
                
                if(empty($_GET['section'])){
                    if($i==0)
                    echo '<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="themes.php?page=mondira-settings&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                    else
                    echo '<li class="ui-state-default ui-corner-top"><a href="themes.php?page=mondira-settings&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                    
                    $i++;
                } else {
                    if($docs['section']==$_GET['section'])
                    echo '<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="themes.php?page=mondira-settings&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                    else
                    echo '<li class="ui-state-default ui-corner-top"><a href="themes.php?page=mondira-settings&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                }
                
		    }
		    echo '</ul>';
            
            $i = 0;
            if(!empty($this->docs)){   
		        foreach($this->docs as $docs) {
                    if(empty($_GET['section'])){
                        if($i==0)
                            $this->renderSection($docs['section']);
                        $i++;
                    } else {
                        if($docs['section']==$_GET['section'])
                            $this->renderSection($docs['section']);
                    }
                }
            }
		    echo '<div class="clear"></div>';
		    echo '</div>';
		    echo '</div>';
	    }
	    
	    function renderSection($section) {
		    echo '<div id="'.$section.'" class="block">';
            
            $html = new MondiraThemeHtml(array('slug'=>$section));
            $options = get_option($this->theme_slug . '_' . $section);
            
            $tmp = array();
            if(!empty($options) && is_array($options))
            foreach($options as $key=>$value){
                $tmp[$key]=stripcslashes($value);
            }
            $options = $tmp;
            
            if(!empty($options))
                extract($options);
           
            if(file_exists(THEME_OPTIONS.'/'.$section.'.php'))
                include THEME_OPTIONS.'/'.$section.'.php';
            else if(file_exists(FRAMEWORK_ADMIN_OPTIONS.'/'.$section.'.php'))
		        include FRAMEWORK_ADMIN_OPTIONS.'/'.$section.'.php';
            
            
		    echo '<div class="clear"></div>';
		    echo '</div>';
	    }
    }
}