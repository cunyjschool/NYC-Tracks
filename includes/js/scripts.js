jQuery(document).ready(function(){

    // Box Equal Height
    jQuery('.col1 .post.fl').each(function(){
            
            h_left = jQuery(this).children('.box-post-content').height();
            h_right = jQuery(this).next('.fr').children('.box-post-content').height();
            
            if(h_left >= h_right)
            {
                jQuery(this).next('.fr').children('.box-post-content').height(h_left);
            }
            if (h_left < h_right)
            {
                jQuery(this).children('.box-post-content').height(h_right);
            }
        })
        

    
})