jQuery(document).ready(function(){

/* Slider */
    
    var amount_of_slides = jQuery('#slider-holder .slide').length;   
    var counter = 0;

    var rel_left = amount_of_slides;
    var slider_tracker = 1;
    var rel_right = 2;
    
    //Setup
    jQuery('#slider-nav .slider-left').attr('rel',rel_left);
    jQuery('#slider-nav .slider-right').attr('rel',rel_right);
    jQuery('#slider-holder .slide-1').css('opacity',1);
    jQuery('#slider-holder img.full-mask-1').show(); // Show the correct mask before click action

    //Slider Shelf
    
    if(amount_of_slides == 1)
    {
       jQuery('#slider-holder .slider-shelf').add('#slider-holder #slider-nav').add('#slider-holder .shelf-content').add('#slider-holder .clicker').hide();   
       
    }
    else 
    {
    var shelf_height = jQuery('#slider-holder .shelf-content').height();
    shelf_height = shelf_height + 8

    jQuery('#slider-holder .slider-shelf').css('opacity',0.9);
    jQuery('#slider-holder .clicker').css('opacity',0.9);
    jQuery('#slider-holder .shelf-content img').css('opacity',0.3);
    jQuery('#slider-holder .slider-shelf').css('height',shelf_height);
    
    var shelf =   jQuery('#slider-holder .slider-shelf').add('#slider-holder .shelf-content');
  
    shelf.css('top',shelf_height * -1);
    
    var flip = 1;
    jQuery('#slider-holder .clicker').click(function(){
    
        flip++;
        if(flip%2 == 0)
        {
          shelf.animate({top:0},300)
          jQuery(this).animate({top:shelf_height},300)
       }
        else 
        { 
            shelf.animate({top:shelf_height * -1 },300)
            jQuery(this).animate({top:0},300);
        }
        
    });
    
    jQuery('#slider-holder .shelf-content img').hover(function(){
        jQuery(this).stop().animate({ opacity:1});
        var title = jQuery(this).parent('span').attr('title');
        jQuery('#slider-holder .shelf-title').html(title);
        
    },function(){
        jQuery(this).stop().animate({ opacity:0.3})
        jQuery('#slider-holder .shelf-title').html('');
    });
    
    jQuery('#slider-holder .shelf-content span').click(function(){
        var shelf_rel = jQuery(this).attr('class');
    });
    
    jQuery('#slider-holder .shelf-content img').click(function(){
        var shelf_click = jQuery(this).parent('span').attr('class');

        var i = 0;
        var dif = shelf_click - slider_tracker;
        //alert(dif);
        
        if (dif > 0){
            while(i < dif) { 
                 jQuery('#slider-nav .slider-right').click(); 
                 i++; 
            }
        }
        if (dif < 0){
             while(i > dif) { 
                     
                     jQuery('#slider-nav .slider-left').click();
                    i--;
            }
        }
    })
    
    }
    
    
    
    
    //Set initial slider content
    
    var content_height = jQuery('#slider-holder .slide-content-height-1').height();
    var slider_height = jQuery('#slider-holder').height();
    jQuery('#slider-holder .slide-content-1').css('opacity','0').css('top', slider_height + 'px');
    var cut_from_top = (slider_height - 20) - content_height;
    jQuery('#slider-holder .slide-content-1').animate({ 'top':cut_from_top,opacity:0.8},1000);

    
    // Navigation Animation LEFT & RIGHT
    //Set opacity on buttons
    jQuery('#slider-holder .slider-left')
        .add('#slider-holder .slider-right').css('opacity','0.2');
   
    
    jQuery('#slider-holder .slider-left')
        .add('#slider-holder .slider-right')
        .hover(function(){
                        jQuery(this).animate({
                            opacity:1
                        },200)
                    },function(){
                      jQuery(this).animate({
                            opacity:0.2
                        },400)
                    })
    
    if(amount_of_slides > 1){
    
    
   

    //Amount of slide check 
    var t;
    var timeout = 6000;
    function click_do(){
        t = setTimeout(function(){
            jQuery('#slider-nav .slider-right').click();
            click_do();
        },timeout);
    }
    click_do();
    
    
    
     // --------------------------- CLICK FUNCTIONS - FORWARD ------------------------------
    jQuery('#slider-nav .slider-right').click(function(evt){
        
        clearTimeout(t);
        
        var slider_height = jQuery('#slider-holder').height();
        
        // Action     
        jQuery('#slider-holder .slide-full').css('left','595px').css('top','0px');
        jQuery('#slider-holder img.full-mask').hide();
        jQuery('#slider-holder img.full-mask-' + slider_tracker).show(); // Keeps the correct mask shown

        
        jQuery('#slider-holder .slide-content').css('top', slider_height + 'px'); // Keps the correct mask shown
        

        
        counter++;
        
        var target_slide = jQuery(this).attr('rel'); // Lock in on what slide is next
        
        //Prep Slider Content

        jQuery('#slider-holder .slide-content-' + target_slide).css('z-index',counter).css('opacity','0').css('top',slider_height + 'px');
        if(jQuery.browser.msie){ jQuery("#slider-holder .slide-content").css("opacity",1);} else {
            jQuery('#slider-holder .slide-content-' + target_slide).css('opacity','0');
        }
        //Main Action
        jQuery('#slider-holder .slide-' + target_slide).css('opacity','0').css('z-index',counter).css('left','595px');
        jQuery('#slider-holder .slide-' + target_slide).animate({ "left": "0px", opacity:1 },500,"swing", content_stuff );
        

        
        //Slider Content Fade in and Height calc
        function content_stuff(){
            var content_height = jQuery('#slider-holder .slide-content-height-' + target_slide).height();
            var cut_from_top = (slider_height - 20) - content_height;
            var opacity = 0.8;
            if(jQuery.browser.msie){
                jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top},1000);
            } else{
                jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},1000);
            }
        }
        
        // Clocks
        slider_tracker++; 
        rel_left++;
        rel_right++; 
        
        // Clock Statements  
       if(rel_left > amount_of_slides){rel_left = 1;}
       if(rel_right > amount_of_slides){rel_right = 1;}
       if(slider_tracker > amount_of_slides){slider_tracker = 1;}
        
        
        //Assign Values, Save action
        jQuery('#slider-nav .slider-left').attr('rel',rel_left);
        jQuery('#slider-nav .slider-right').attr('rel',rel_right);
        
        //Prevent Click Default Action
        evt.preventDefault();
        
        /*        
        jQuery('#slider-nav span').html(slider_tracker + ' ... init');
        jQuery('#slider-nav .slider-left').html('<--- ' + rel_left + ' --- ');
        jQuery('#slider-nav .slider-right').html('--- ' + rel_right + ' --->'); 
        */

    });
    
// --------------------------- CLICK FUNCTION  - END ------------------------------------
    
// --------------------------- CLICK FUNCTIONS - REVERSE------------------------------

    jQuery('#slider-nav .slider-left').click(function(evt){
        
        // Action     
        jQuery('#slider-holder .slide-full').css('left','595px').css('top','0px');
        jQuery('#slider-holder img.full-mask').hide();
        jQuery('#slider-holder img.full-mask-' + slider_tracker).show(); // Keps the correct mask shown
        
        counter++;
        
        var target_slide = jQuery(this).attr('rel'); // Lock in on what slide is next
        
        //Prep Slider Content
        var slider_height = jQuery('#slider-holder').height();
        jQuery('#slider-holder .slide-content-' + target_slide).css('z-index',counter).css('opacity','0').css('top',slider_height + 'px');
        
        //Main Action
        jQuery('#slider-holder .slide-' + target_slide).css('opacity','0').css('z-index',counter).css('left','595px');
        jQuery('#slider-holder .slide-' + target_slide).animate({ "left": "0px", opacity:1 },500,"swing",content_stuff_left);
        
        
        //Slider Content Fade in and Height calc
        function content_stuff_left(){
            var content_height = jQuery('#slider-holder .slide-content-height-' + target_slide).height();
            var cut_from_top = (slider_height - 20) - content_height;
            if(jQuery.browser.msie){
                  jQuery('#slider-holder .slide-content-' + target_slide).css('z-index',counter).css('opacity',1).css('top',slider_height + 'px').css('left','0');
                  jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top},1000);
            }
            else
            {
                jQuery('#slider-holder .slide-content-' + target_slide).css('z-index',counter).css('opacity','0').css('top', slider_height + 'px').css('left','0');
                 jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},1000);
            }

        }
        
        


        // Clocks
        slider_tracker--; 
        rel_left--;
        rel_right--; 
        
        // Clock Statements
       if(rel_left < 1){rel_left = amount_of_slides;}
       if(rel_right < 1){rel_right = amount_of_slides;}
       if(slider_tracker < 1){slider_tracker = amount_of_slides;}
        
        //Assign Values, Save action
        jQuery('#slider-nav .slider-left').attr('rel',rel_left);
        jQuery('#slider-nav .slider-right').attr('rel',rel_right);
        
        //Prevent Click Default Action
        evt.preventDefault();
        
        /*
        jQuery('#slider-nav span').html(slider_tracker + ' ... init');
        jQuery('#slider-nav .slider-left').html('<--- ' + rel_left + ' --- ');
        jQuery('#slider-nav .slider-right').html('--- ' + rel_right + ' --->'); 
        */
        

    });
// --------------------------- CLICK FUNCTION  - END -------------------------------------

    }// End amount of slide check
    
})