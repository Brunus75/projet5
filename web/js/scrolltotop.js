jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        if ($(this).scrollTop() > 50) {
            jQuery('#scroll-to-top').fadeIn();
        } else {
            jQuery('#scroll-to-top').fadeOut();
        }
    });

    jQuery('#scroll-to-top').click(function(){
        jQuery('html, body').animate({scrollTop : 0},1200);
        return false;
    });
    jQuery('#scroll-to-bottom').click(function(){
        jQuery('html, body').animate({scrollTop: $(document).height()-$(window).height()},1200);
        return false;
    });
});