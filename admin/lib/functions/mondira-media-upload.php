<?php
/*
* 
* Mondia image upload via Thickbox 
* 
* @since version 1.0
* @last modified 28 Feb, 2015
* @author Jewel Ahmed<tojibon@gmail.com>
* @author url http://www.codeatomic.com 
* 
*/

/*
* Unseting image upload via url tab from Thickbox image upload page of wordpress
* @return default tabs without url image upload
*/
if (!function_exists('mondira_image_upload_tabs')) {
    function mondira_image_upload_tabs ($tabs) {
	    unset($tabs['type_url']);
        return $tabs;
    }
}

/*
* @return Thickbox image upload form action url
*/
if (!function_exists('mondira_image_upload_form_url')) {
    function mondira_image_upload_form_url($form_action_url, $type){
	    $form_action_url = $form_action_url.'&mondira_image_upload=1&target='.$_GET['target'];
	    return $form_action_url;
    }
}


/*
* Disabling flash media uploader on Thickbox media upload wordpress page
*/
if (!function_exists('disable_media_flash_uploader')) {
    function disable_media_flash_uploader($flash){
	    return false;
    }
}

/*
* Customizing image edit, delete view page of Thickbox on wordpress media upload interface
*/
if (!function_exists('mondira_image_upload_attachment_fields_to_edit')) {
    function mondira_image_upload_attachment_fields_to_edit($form_fields, $post){
	    unset($form_fields);
	    $filename = basename( $post->guid );
	    $attachment_id = $post->ID;
	    if ( current_user_can( 'delete_post', $attachment_id ) ) {
		    if ( !EMPTY_TRASH_DAYS ) {
			    $delete = "<a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Delete Permanently' , 'mondira_admin' ) . '</a>';
		    } elseif ( !MEDIA_TRASH ) {
			    $delete = "<a href='#' class='del-link' onclick=\"document.getElementById('del_attachment_$attachment_id').style.display='block';return false;\">" . __( 'Delete' , 'mondira_admin' ) . "</a>
			     <div id='del_attachment_$attachment_id' class='del-attachment' style='display:none;'>" . sprintf( __( 'You are about to delete <strong>%s</strong>.' , 'mondira_admin' ), $filename ) . "
			     <a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='button'>" . __( 'Continue Delete' , 'mondira_admin' ) . "</a>
			     <a href='#' class='button' onclick=\"this.parentNode.style.display='none';return false;\">" . __( 'Cancel Delete' , 'mondira_admin' ) . "</a>
			     </div>";
		    } else {
			    $delete = "<a href='" . wp_nonce_url( "post.php?action=trash&amp;post=$attachment_id", 'trash-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Move to Trash' , 'mondira_admin' ) . "</a>
			    <a href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$attachment_id", 'untrash-attachment_' . $attachment_id ) . "' id='undo[$attachment_id]' class='undo hidden'>" . __( 'Undo' , 'mondira_admin' ) . "</a>";
		    }
	    } else {
		    $delete = '';
	    }
        
        $pos = strpos(strtolower($_GET['target']), strtolower('zip'));
        if ($pos === false) {
            $form_fields['buttons'] = array( 
            'tr' => "\t\t<tr><td></td><td><input type='button' class='button' onclick='mondiraMediaUploader.UseThisImage(".$post->ID.",\"". $_GET['target']."\")' value='" . __( 'Use This' , 'mondira_admin' ) . "' /> $delete</td></tr>\n"
        );
        } else {
            $form_fields['buttons'] = array( 
            'tr' => "\t\t<tr><td></td><td><input type='button' class='button' onclick='mondiraMediaUploader.UseThisZIP(\"".$post->guid."\",\"". $_GET['target']."\")' value='" . __( 'Use This File' , 'mondira_admin' ) . "' /> $delete</td></tr>\n"
        );
            
        }
	    return $form_fields;
    }
}

/*
* @return image source
* called from mondiraJs scripts of admin
*/
if (!function_exists('mondira_image_upload_get_image_callback')) {
    function mondira_image_upload_get_image_callback() {
        $full_image_src = wp_get_attachment_image_src($_POST['id'],'full');
        if ( !empty($full_image_src) ) {
            echo $full_image_src[0];
        } else {
            die(0);
        }
        die();
    }
}
add_action('wp_ajax_mondira-image-upload-get-image', 'mondira_image_upload_get_image_callback');


