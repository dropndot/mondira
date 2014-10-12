/*
---------------------------------------------------------------------------------------
    Theme related all JS custom functions for Admin Users
    @Since Version 1.0
---------------------------------------------------------------------------------------
*/

/*
---------------------------------------------------------------------------------------
    Loading different settings meta for Post, Portfolio when post format selection done
---------------------------------------------------------------------------------------
*/


jQuery(document).ready( function($) {
    
    //JS for post formats metaboxes of blog posts
    if($('.post-format').size()>0) {
        var formats = new Array( "0", "link", "gallery", "image", "aside", "chat", "quote", "audio", "video", "status" );
        $.each(formats, function(index, value){
            
            $('#post-format-'+value).click(function(){
                $.each(formats, function(index1, value1){
                    if($('#post-format-'+value1).is(":checked")) {
                        $('#post-'+value1).show();    
                        $('#portfolio-'+value1).show();    
                        $('#slider-'+value1).show();    
                    } else {
                        $('#post-'+value1).hide();
                        $('#portfolio-'+value1).hide();
                        $('#slider-'+value1).hide();
                    }
                });
            })
            
            if($('#post-format-'+value).is(":checked")) {
                $('#post-'+value).show();    
                $('#portfolio-'+value).show();    
                $('#slider-'+value).show();    
            } else {
                $('#post-'+value).hide();
                $('#portfolio-'+value).hide();
                $('#slider-'+value).hide();
            }
        });
        
    }
    
});