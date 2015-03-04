<?php
/*
* 
* This Class is to output all meta settings input fields
* 
* @since version 1.0
* @last modified 28 Feb, 2015
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
* 
*/

if(!class_exists('MondiraThemeHtml')){
    class MondiraThemeHtml extends MondiraThemeHelper{
        public function init(){}

        function formTableSelect($attr = array('class'=>'regular-select', 'avoid_br'=>'no',  'empty'=>'Select...', 'options'=>array()), $description = ''){
            $default = array('class'=>'regular-select', 'empty'=>'Select...', 'options'=>array());
            $attr = array_merge($default, $attr);
            if(!empty($attr['desc'])){
                $description = $attr['desc'];
            }
            
            if(empty($attr['source'])){
                $attr['source'] = null;
            }
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            if($attr['source']=='page'){
                $pages = get_pages(); 
                //$tmp_page_list = array(''=>'Select A Page');
                foreach ( $pages as $page ) {
                    $tmp_page_list[$page->ID] = $page->post_title;
                }
                $attr['options'] = $tmp_page_list;
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
                
                $output.= $this->tableTd();
                    if(!empty($attr['field'])){
                       $output.= $attr['field']; 
                    } else {
                        
                        if ( !empty( $attr['multiple'] ) ) {
                            $output.= $this->select_multi($attr);
                        } else {
                            $output.= $this->select($attr);    
                        }
                        
                        if(!empty($description)) {
                            if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                                $output.= '<br class="clearfix" />';   
                                $output.= $this->span(array('class'=>'description'), $description);
                            } else {
                                $output.= $this->span(array('class'=>'description avoid_br'), $description);
                            } 
                        } 
                    }
                    
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableOption($attr = array('class'=>'regular-option', 'avoid_br'=>'no',  'empty'=>'', 'options'=>array()), $description = ''){
            
            $default = array('class'=>'regular-option', 'empty'=>'', 'options'=>array());
            $attr = array_merge($default, $attr);
            if(!empty($attr['desc'])){
                $description = $attr['desc'];
            }
            
            if(empty($attr['source'])){
                $attr['source'] = null;
            }
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            if($attr['source']=='page'){
                $pages = get_pages(); 
                //$tmp_page_list = array(''=>'Select A Page');
                foreach ( $pages as $page ) {
                    $tmp_page_list[$page->ID] = $page->post_title;
                }
                $attr['options'] = $tmp_page_list;
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
                
                $output.= $this->tableTd();
                    if(!empty($attr['field'])){
                       $output.= $attr['field']; 
                    } else {
                        $output.= $this->option($attr);
                        
                        if(!empty($description)) {
                            if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                                $output.= '<br class="clearfix" />';   
                                $output.= $this->span(array('class'=>'description'), $description);
                            } else {
                                $output.= $this->span(array('class'=>'description avoid_br'), $description);
                            } 
                        } 
                    }
                    
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableCheckbox($attr = array('type'=>'checkbox', 'avoid_br'=>'no', 'class'=>'yesno', 'checked'=>''), $description = ''){
            $default = array('type'=>'checkbox', 'class'=>'yesno', 'checked'=>'');
            $attr = array_merge($default, $attr);
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
                
                $output.= $this->tableTd();
                
                    if(!empty($attr['checked']) && 'yes'==$attr['checked']){
                        $attr['checked'] = 'checked';
                    } else {
                        unset($attr['checked']);
                    }
                    $output.= $this->input($attr);
                    if(!empty($description)) {
                        if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                            $output.= '<br class="clearfix" />';   
                            $output.= $this->span(array('class'=>'description'), $description);
                        } else {
                            $output.= $this->span(array('class'=>'description avoid_br'), $description);
                        } 
                    }
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableColor($attr = array('type'=>'text', 'avoid_br'=>'yes', 'class'=>'regular-text', 'data-default-color'=>'#ffffff'), $description = ''){
            $default = array('type'=>'text', 'class'=>'regular-text wp-color-picker', 'data-default-color'=>'#ffffff');
            $attr = array_merge($default, $attr);
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
                
                $output.= $this->tableTd();
                    $output.= $this->input($attr);
                    
                   if(!empty($description)) {
                        if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                            $output.= '<br class="clearfix" />';   
                            $output.= $this->span(array('class'=>'description'), $description);
                        } else {
                            $output.= $this->span(array('class'=>'description avoid_br'), $description);
                        } 
                    }
                    
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableInput($attr = array('type'=>'text', 'avoid_br'=>'no', 'class'=>'regular-text', 'upload'=>array()), $description = ''){
            $default = array('type'=>'text', 'class'=>'regular-text', 'upload'=>array());
            $attr = array_merge($default, $attr);
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
				
                $output.= $this->tableTd();
                    $output.= $this->input($attr);
                    
                    if(!empty($attr['upload']) && is_array($attr['upload'])){    //Image Upload True
                        if(!empty($description)){
                            if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                                $output.= '<br class="clearfix" />';   
                                $output.= $this->span(array('class'=>'description'), $description);
                            } else {
                                $output.= $this->span(array('class'=>'description avoid_br'), $description);
                            } 
                        }
                        $output.= '<br class="clearfix" />';
                        $output.= '
                        <div class="mondira-media-upload-button">
                            <a class="upload_image_button_latest button theme-upload-button" data-target="'.$attr['id'].'" id="'.$attr['id'].'_thickbox" href="media-upload.php?post_id=0&target='.$attr['id'].'&mondira_image_upload=1&type=image&TB_iframe=1&width=640&height=644">'.$attr['upload']['title'].'</a><br />';
                            
                            
                            $custom_logo = $attr['value'];
                            if(!empty($custom_logo)){
                                $output.= '<div id="'.$attr['id'].'_preview" class="mondira-media-preview" style="background-color:#fff; border:1px solid #CCC; height:120px; width:140px; padding:5px; text-align:center;">';
                                
                                $output.= '<a class="fancybox prview_thumb_image" href="'.$custom_logo.'"><img src="'.$custom_logo.'" width="140" /></a>'; 
                                $output.= '</div>';
                                
                            } else {
                                $output.= '<div id="'.$attr['id'].'_preview" class="mondira-media-preview"></div>';
                            }
                            
                        $output.= '</div>';
                    } else if(!empty($attr['upload_zip']) && is_array($attr['upload_zip'])){    //Image Upload True
                        if(!empty($description)){
                            if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                                $output.= '<br class="clearfix" />';   
                                $output.= $this->span(array('class'=>'description'), $description);
                            } else {
                                $output.= $this->span(array('class'=>'description avoid_br'), $description);
                            } 
                        }
                        $output.= '<br class="clearfix" />';
                        $output.= '
                        <div class="mondira-media-upload-button">
                            <a class="upload_image_button_latest button theme-upload-button" data-target="'.$attr['id'].'" id="'.$attr['id'].'_thickbox" href="media-upload.php?post_id=0&target='.$attr['id'].'&mondira_image_upload=1&type=file&TB_iframe=1&width=640&height=644">'.$attr['upload_zip']['title'].'</a>';
                        $output.= '</div>';
                    } elseif(!empty($description)) {
                        if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                            $output.= '<br class="clearfix" />';   
                            $output.= $this->span(array('class'=>'description'), $description);
                        } else {
                            $output.= $this->span(array('class'=>'description avoid_br'), $description);
                        } 
                    }
                    
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableTextarea($attr = array('type'=>'textarea', 'avoid_br'=>'no', 'class'=>'regular-text'), $description = ''){
            $default = array('type'=>'textarea', 'class'=>'regular-textarea');
            $attr = array_merge($default, $attr);
            
            if(empty($attr['name']))
                return false;
                
            if(empty($attr['title'])){
                $attr['title'] = $this->createInputTitle($attr['name']);
            }
                
            if(empty($attr['id'])){
                $attr['id'] = $this->stringToId($attr['title']);
            }
            
            if(empty($attr['title'])){
                $attr['title'] = $attr['name'];
            }
            
            $output = $this->tableTr();
                $output.= $this->tableTh();
                    $output.= $this->label(array('title'=>$attr['title'], 'for'=>$attr['name']));
                $output.= $this->tableThEnd();
                
                $output.= $this->tableTd();
                    $output.= $this->textarea($attr);
                    
                    if( !empty( $description ) ) {
                        if( !empty( $attr['avoid_br'] ) && $attr['avoid_br'] == 'no' ){
                            $output.= '<br class="clearfix" />';   
                            $output.= $this->span(array('class'=>'description'), $description);
                        } else {
                            $output.= $this->span(array('class'=>'description avoid_br'), $description);
                        } 
                    }
                    
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output;
        }
        
        function formTableHtml ( $attr = array('html'=>'html') ) {
            $output = $this->tableTr();
                $output.= $this->tableTd(array('colspan'=>2, 'class'=>'empty'));
                    $output.= $attr['html'];
                $output.= $this->tableTdEnd();
            $output.= $this->tableTrEnd();
            return $output; 
        }
    }   //End of html helper class
}