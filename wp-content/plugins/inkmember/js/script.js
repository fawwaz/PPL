jQuery = jQuery.noConflict();
jQuery(function() {
    
    jQuery('#im_log_pop').click(function(e) {
        jQuery("#im_authform").lightbox_me({
            centered: true, 
            onLoad: function() {
                jQuery("#im_authform").find("input:first").focus();
            }
        });				
        e.preventDefault();
    });
});


