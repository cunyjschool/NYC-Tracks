jQuery(document).ready(function(){
    
    // You can edit the Sldier animation her
    var slide_fade = false;
    var content_fade = false;
    var padding_offset = 20; // Adjust for slider content -popup

    // --- CORE ---   
    var counter = 0;
    var amount_of_slides = jQuery('#slider-holder .slide').length; 

    var rel_left = amount_of_slides;
    var slider_tracker = 1;
    var rel_right = 2;
    var slider_height = jQuery('#slider-holder').height();
    var slider_width = jQuery('#slider-holder').width();
    

    jQuery('#slider-nav .slider-left').attr('rel',rel_left);     //Setup
    jQuery('#slider-nav .slider-right').attr('rel',rel_right);
   // jQuery('#slider-holder .slide-1').css('opacity',1);
    
    // --- CORE END ---
    
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
          shelf.animate({top:0},300);
          jQuery(this).animate({top:shelf_height},300);
          jQuery('#slider-nav .slider-right').add('#slider-nav .slider-left').fadeOut(100); 
       }
        else 
        { 
            shelf.animate({top:shelf_height * -1 },300);
            jQuery(this).animate({top:0},300);
            jQuery('#slider-nav .slider-right').add('#slider-nav .slider-left').fadeIn(400).attr('style',''); 
            
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
               
               

// --- CORE ---
    jQuery('#slider-holder .slide-content-1').css('opacity','0').show(); // Crappy hack
    var content_height = jQuery('#slider-holder .slide-content-height-1').height();
    jQuery('#slider-holder .slide-content-1').hide().attr('style',''); //Crappy hack
    var cut_from_top = (slider_height - padding_offset) - content_height;
    if(content_fade == false) {
        jQuery('#slider-holder .slide-content-1').show().css({'opacity' : '0', 'top' : slider_height + 'px'}); //Set initial slider content
        jQuery('#slider-holder .slide-content-1').animate({ 'top':cut_from_top,opacity:0.8},1000);
    } else {
        jQuery('#slider-holder .slide-content-1').show().css({'opacity' : '0', 'top' : cut_from_top + 'px'}); //Set initial slider content
        jQuery('#slider-holder .slide-content-1').animate({opacity:0.8},1000);
    }

    jQuery('#slider-holder .slider-left')
        .add('#slider-holder .slider-right')
        .css('opacity',0.6);// Navigation Animation LEFT & RIGHT
   
    jQuery('#slider-holder .slider-left')
        .add('#slider-holder .slider-right')
        .hover(function(){ jQuery(this).animate({ opacity:1} , 200)},
               function(){ jQuery(this).animate({ opacity:0.6 }, 400); });
                    
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
    
// CLICK FUNCTION - FORWARD
    jQuery('#slider-nav .slider-right').click(function(evt){  
        
        clearTimeout(t);
        counter = counter + 2;
  
        var target_slide = jQuery(this).attr('rel'); // Lock in on what slide is next
        var content_height = jQuery('#slider-holder .slide-content-height-' + target_slide).height();         //Slider Content Animation
        var cut_from_top = (slider_height - padding_offset) - content_height;
        
        if(content_fade == false) {
        jQuery('#slider-holder .slide-content-' + target_slide)
            .css({'z-index' : counter, 'opacity' : '0', 'top' : slider_height + 'px'});   //Prep Slider Content
            } else {
        jQuery('#slider-holder .slide-content-' + target_slide)
            .css({'z-index' : counter, 'opacity' : '0', 'top' : cut_from_top + 'px'});   //Prep Slider Content
        }
       
 
        if(slide_fade == false){
        jQuery('#slider-holder .slide-' + target_slide)

            .css({'opacity' : '0', 'z-index' : counter, 'left' : slider_width + 'px'})
            .animate({ 'left' : '0px', opacity:1 },500,"linear", content_stuff ); //Prep , animate and call callback for slider_content
        } else {
        jQuery('#slider-holder .slide-' + target_slide)
            .css({'opacity' : '0', 'z-index' : counter})
            .animate({ opacity:1 },500,"linear", content_stuff ); //Prep , animate and call callback for slider_content
            }

        function content_stuff(){ 
        jQuery('#slider-holder .slide-' + target_slide).attr('style','').css({'z-index' : counter, 'left' :  '0px'})
            if(content_fade == false){
                jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},1000);
            } else {
                jQuery('#slider-holder .slide-content-' + target_slide).animate({opacity:0.8},1000);
            }
        }
        
        slider_tracker++; rel_left++; rel_right++;        // Clocks

       if(rel_left > amount_of_slides){rel_left = 1;}         // Clock Statements  
       if(rel_right > amount_of_slides){rel_right = 1;}
       if(slider_tracker > amount_of_slides){slider_tracker = 1;}
       
        jQuery('#slider-nav .slider-left').attr('rel',rel_left);        //Assign Values, Save action
        jQuery('#slider-nav .slider-right').attr('rel',rel_right);
        
        evt.preventDefault();         //Prevent Click Default Action

        // ----- CORE END ------------
       
       //Daily Edition Dots Tracking
        jQuery('.slider-dots span').fadeTo(200, 0.2);
        jQuery('.slider-dots span.dot-' + slider_tracker).fadeTo(200, 1);
        
    });

    
//  CLICK FUNCTIONS - REVERSE------------------------------

    jQuery('#slider-nav .slider-left').click(function(evt){
    
        clearTimeout(t);
        counter = counter + 2;
        
        var target_slide = jQuery(this).attr('rel'); // Lock in on what slide is next
        var content_height = jQuery('#slider-holder .slide-content-height-' + target_slide).height();         //Slider Content Animation
        var cut_from_top = (slider_height - padding_offset) - content_height;
        jQuery('#slider-holder .slide-content-' + rel_left).css({'top' : slider_height + 'px','left' : '0px', 'opacity'  : 0});
        jQuery('#slider-holder .slide-' + target_slide).attr('style','').css({'z-index' : counter-1, 'left' :  '0px'})     
        
        if(content_fade == false) {
        jQuery('#slider-holder .slide-content-' + slider_tracker)
            .css({'z-index' : counter, 'opacity' : 0, 'top' : slider_height + 'px'});   //Prep Slider Content
            } else {
        jQuery('#slider-holder .slide-content-' + target_slide)
            .css({'z-index' : counter, 'opacity' : 0, 'top' : cut_from_top + 'px'});   //Prep Slider Content
        }
            
        if(slide_fade == false){
        jQuery('#slider-holder .slide-' + slider_tracker)
            .css({'opacity' : 1, 'z-index' : counter, 'left' : '0px'})
            .animate({ 'left': slider_width + 'px', opacity:0 }, 500,"linear", content_stuff_alt ); //Prep , animate and call callback for slider_content
        } else {
        jQuery('#slider-holder .slide-' + target_slide)
            .css({'opacity' : '0', 'z-index' : counter})
            .animate({ opacity:1 },500,"linear", content_stuff_alt ); //Prep , animate and call callback for slider_content
            }

        function content_stuff_alt(){

            if(content_fade == false){
                
            //jQuery('#slider-holder .slide-' + target_slide).attr('style','').css({'z-index' : counter, 'left' :  '0px'})
                jQuery('#slider-holder .slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},1000);
            } else {
                jQuery('#slider-holder .slide-content-' + target_slide).animate({opacity:0.8},1000);
            }
        }

        slider_tracker--; rel_left--; rel_right--;     // Clocks

       if(rel_left < 1){rel_left = amount_of_slides;}          // Clock Statements
       if(rel_right < 1){rel_right = amount_of_slides;}
       if(slider_tracker < 1){slider_tracker = amount_of_slides;}

        jQuery('#slider-nav .slider-left').attr('rel',rel_left);        //Assign Values, Save action
        jQuery('#slider-nav .slider-right').attr('rel',rel_right);
        
        evt.preventDefault(); //Prevent Click Default Action
        
        //Daily Edition Dots Tracking
        jQuery('.slider-dots span').fadeTo(200, 0.2);
        jQuery('.slider-dots span.dot-' + slider_tracker).fadeTo(200, 1);
        

    });

    }// End amount of slide check
    
})