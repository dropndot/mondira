jQuery(document).ready( function($){	
	/*
	---------------------------------------------------------------------------------------
		Note: alert( wp.media ); It will be undefined if you do not use thumbnail for post type like  'supports' => array( 'title', 'post-formats', 'page-attributes', 'thumbnail'),
		Opening the frame to make the input text field file upload friendly with WordPress default file uploader.
		@Dependency This file is dependent on mondira-admin.js as mondiraAdmin.themeGetNDisplayImage(attachment,target);  class defined in that file.
		@Since Version 1.0
		@Last Updated 29 Sept, 2014
	---------------------------------------------------------------------------------------
	*/

	var file_frame;
	var wp_media_post_id = 1; 
	var set_to_post_id = 10;
	var target = "";
 
	jQuery('.upload_image_button_latest').live('click', function( event ){
		if ( typeof wp.media == 'undefined' ) {
			return;  
		}
		target = $(this).attr('data-target');      
		event.preventDefault();
		
		if ( file_frame ) {
		  file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		  file_frame.open();
		  return;
		}
		if ( typeof wp.media !== 'undefined' && wp.media != '' ) {
			wp.media.model.settings.post.id = set_to_post_id;
		}
	 
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false  
		});
	 
		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();
			mondiraAdmin.themeGetNDisplayImage(attachment,target); 
			wp.media.model.settings.post.id = wp_media_post_id;
		});
		file_frame.open();
	});
  
	jQuery('a.add_media').on('click', function() {      
		if ( wp.media != 'undefined' && wp.media != '' ) {         
			wp.media.model.settings.post.id = wp_media_post_id;
		}
	});
})



