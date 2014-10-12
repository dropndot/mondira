var mondiraMediaUploader = {
    UseThisImage : function(id,target){
        var win = window.dialogArguments || opener || parent || top;
        win.mondiraAdmin.themeGetNDisplayImage(id,target);
        win.tb_remove();
    },
    
    UseThisZIP : function(id,target){
        var win = window.dialogArguments || opener || parent || top;
        win.mondiraAdmin.themeGetNDisplayZIP(id,target);
        win.tb_remove();
    }
}

jQuery(document).ready( function($) {
    if(location.search.indexOf('option_image_upload') != -1){
        jQuery('#media-upload #filter').append('<input type="hidden" value="1" name="option_image_upload">');
        jQuery('#media-upload #gallery-settings').remove();
    }
}) 



