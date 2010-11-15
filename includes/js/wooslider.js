/* WooSlider
--------
Author: Foxinni.com
Version: 1.0.2
---------
*/

(function(jQuery) {
	jQuery.fn.wooslider = function(input) {
	
	var defaults = {
		container: '.slider-container',
		nav: '.slider-nav',
		nav_left: '.slider-left',
		nav_right: '.slider-right',
		pagination: '.slider-pag',
		shelf: '.slider-shelf',
		shelf_content: '.slider-shelf-content',
		shelf_title: '.shelf-title',
		clicker : '.clicker',
		shelf_title_default : 'Hover over the images',
		sfade : false, // Slide Fade
		cfade : false, // content Fade
		offset : 20, // Padding offset
		speed: 500,
		timeout: 6000,
		content_speed: 1000
		
		
	};
	
	
	return this.each(function() {
	
	//Crucial Inits
	var holder  = jQuery(this);
    var options = jQuery.extend(defaults, input);
   
   
    //Object Setup
    var container = jQuery(options.container,holder);
    var pagination = jQuery(options.pagination,holder);
    var nav = jQuery(options.nav,holder);
    var nav_items = nav.children();
    var nav_right = jQuery(options.nav_right,nav);
    var nav_left = jQuery(options.nav_left,nav);
    var shelf = jQuery(options.shelf,holder);
    var shelf_content = jQuery(options.shelf_content,holder);
    var the_shelf = jQuery(options.shelf,holder).add(options.shelf_content,holder);
    var shelf_title = shelf_content.find(options.shelf_title);
    var shelf_title_default = options.shelf_title_default;
    var clicker = jQuery(options.clicker,holder);
   
    
    //Animation Variables
    var slide_fade = options.sfade;
    var content_fade = options.cfade;
    var padding_offset = options.offset; 
    var ani_speed = options.speed; 
    var ani_timeout = options.timeout; 
    var ani_content_speed = options.content_speed;
    
    
    
    //Working Variables
    var counter = 0;
    var amount_of_slides = container.find('.slide').length; 
    var rel_left = amount_of_slides;
    var slider_tracker = 1;
    var rel_right = 2;
    var slider_height = container.height();
    var slider_width = container.width();
  	
  	//Basic check for level of tech need here.
    if (amount_of_slides == 1) {
    	the_shelf.remove();
		clicker.remove();
    	nav.remove();
    	pagination.remove();
    }
    else {
    
    //Start
    shelf_title.html(shelf_title_default);
    nav.find('.slider-left').attr('rel',rel_left);
    nav.find('.slider-right').attr('rel',rel_right);
    
    var shelf_height = shelf_content.height();
    
    shelf.height(shelf_height);
    shelf.css('opacity',0.8);
    clicker.css('opacity',0.8);
    shelf_content.find('img').css('opacity',0.3);
    
	the_shelf.css('top',shelf_height * -1);
    
    var flip = 1;
    clicker.click(function(){ 
    flip++;
    if(flip%2 == 0)
    { nav_items.fadeOut(100);
      shelf.animate({top:0},300);
      shelf_content.animate({top:0},300);
      //the_shelf.css('border','red 1px solid');
      jQuery(this).animate({top:shelf_height},300);
     }
    else 
    { shelf.animate({top:shelf_height * -1 },300);
      shelf_content.animate({top:shelf_height * -1 },300);
      jQuery(this).animate({top:0},300,"linear",function(){
      		nav_items.fadeIn(400).attr('style','')
      	});
    }
    });

    //Shelf image hovers and Clicks
    shelf_content.find('img').hover(function(){
        jQuery(this).stop().animate({opacity:1});
        var title = jQuery(this).attr('alt');
        shelf_title.html(title);
	    },function(){
	    	shelf_title.hide();
	        jQuery(this).stop().animate({opacity:0.3})
	        shelf_title.html(shelf_title_default).fadeIn(300);
	 });
    
    shelf_content.find('span').click(function(){
	        var shelf_rel = jQuery(this).attr('class');
	 });
    
    shelf_content.find('img').click(function(){
        var shelf_click = jQuery(this).attr('title');
        var i = 0;
        var dif = shelf_click - slider_tracker;
        if (dif > 0){ while(i < dif) {nav_right.click();i++;}}
        if (dif < 0){ while(i > dif) {nav_left.click();i--;}}
	 });
        
    pagination.find('span').click(function(){
       var shelf_click = jQuery(this).attr('title');
       //alert('is_clicked');
       var i = 0;
       var dif = shelf_click - slider_tracker;
       if (dif > 0){ while(i < dif) { nav_right.click();i++;}}
       if (dif < 0){ while(i > dif) { nav_left.click();i--;}}
    });
    }
    
   //Pagination Setup
   pagination.find('span').fadeTo(200, 0.2);
   pagination.find('span:first').fadeTo(200, 1);
   
   //If there is an image located above it (alternative)
   pagination.find('span')
        .hover(function(){ jQuery(this).prev('img').fadeIn(200); },
               function(){ jQuery(this).prev('img').fadeOut(200);});
               
              
     

    //1st Slide setup
    //container.find('.slide-content-1').css('opacity',0).show().hide().attr('style','');; //Hack
    
    var content_height = jQuery('.slide-content-inner-1').height();
    var cut_from_top = (slider_height - padding_offset) - content_height;
	
	//jQuery("#debug").text(content_height);
	
	 
    
    if(content_fade == false) {
        container.find('.slide-content-1').show().css({'opacity' : 0, 'top' : slider_height}); //Set initial slider content
        container.find('.slide-content-1').animate({ 'top':cut_from_top, 'opacity':0.8},ani_content_speed);
    } else {
        container.find('.slide-content-1').show().css({'opacity' : 0, 'top' : cut_from_top + 'px'}); //Set initial slider content
        container.find('.slide-content-1').animate({opacity:0.8},ani_content_speed);
    }

    nav_items.css('opacity',1);// Navigation Animation LEFT & RIGHT
   
    nav_items.css('opacity',0.6)
        .hover(function(){ jQuery(this).animate({ opacity:1} , 200)},
               function(){ jQuery(this).animate({ opacity:0.6 }, 400); });
                    
    if(amount_of_slides > 1){
    //Amount of slide check 
    var t;
    var timeout = ani_timeout;
    function click_do(){
        t = setTimeout(function(){
            nav.find('.slider-right').click();
            click_do();
        },timeout);
    }
	if(ani_timeout > 0) {
    	click_do();
	}
 
    
// LEFT movement click function
    nav_right.click(function(evt){  
        
        clearTimeout(t);
        counter = counter + 2;
  
        var target_slide = jQuery(this).attr('rel');
		var current_slide = slider_tracker;
        var content_height = container.find('.slide-content-inner-' + target_slide).height();
        var cut_from_top = (slider_height - padding_offset) - content_height;
		
		

		
        if(content_fade == false) { 
		container.find('.slide-content-' + current_slide).animate({'top' : slider_height}); //Normal
		container.find('.slide-content-' + target_slide).css({'z-index' : counter, 'opacity' : '0'});  
        } else {

		container.find('.slide-content-' + target_slide).fadeIn().css({'z-index' : counter, 'opacity' : '0', 'top' : slider_height});
		}
        
        if(slide_fade == false){
        container.find('.slide-' + target_slide)
            .css({'opacity' : '0', 'z-index' : counter, 'left' : slider_width + 'px'})
            .animate({ 'left' : '0px', opacity:1 },ani_speed,"linear", content_stuff ); //Prep , animate and call callback for slider_content
        } else {
        container.find('.slide-' + target_slide)
            .css({'opacity' : 0, 'z-index' : counter, 'left':'0px'})
            .animate({opacity:1},ani_speed,"linear", content_stuff ); //Prep , animate and call callback for slider_content
            }

        function content_stuff(){ 
		

		container.find('.slide-' + target_slide).css({'z-index' : counter, 'left' :  '0px'});
        
            if(content_fade == false){
                container.find('.slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},ani_content_speed);
            } else {
                container.find('.slide-content-' + target_slide).css('top',cut_from_top).animate({opacity:0.8},ani_content_speed);
            }
        }
        
        slider_tracker++; rel_left++; rel_right++;        // Clocks

       if(rel_left > amount_of_slides){rel_left = 1;}         // Clock Statements  
       if(rel_right > amount_of_slides){rel_right = 1;}
       if(slider_tracker > amount_of_slides){slider_tracker = 1;}
       
        nav.find('.slider-left').attr('rel',rel_left);        //Assign Values, Save action
        nav.find('.slider-right').attr('rel',rel_right);
        
       //Pagination
        pagination.find('span').fadeTo(200, 0.2);
        pagination.find('span.pag-' + slider_tracker).fadeTo(200, 1);
        
        evt.preventDefault();//Prevent Click Default Action
		
		//return false;
        
    });

    
//  RIGHT movement click function

    nav.find('.slider-left').click(function(evt){
    
        clearTimeout(t);
        counter = counter + 2;
        
        var target_slide = jQuery(this).attr('rel'); // Lock in on what slide is next
        var content_height = container.find('.slide-content-inner-' + target_slide).height();         //Slider Content Animation
        var cut_from_top = (slider_height - padding_offset) - content_height;
        container.find('.slide-content-' + rel_left).css({'top' : slider_height + 'px','left' : '0px', 'opacity'  : 0});
        container.find('.slide-' + target_slide).attr('style','').css({'z-index' : counter-1, 'left' :  '0px'})     
        
        if(content_fade == false) {
        container.find('.slide-content-' + slider_tracker)
            .css({'z-index' : counter, 'opacity' : 0, 'top' : slider_height + 'px'});   //Prep Slider Content
            } else {
        container.find('.slide-content-' + target_slide)
            .css({'z-index' : counter, 'opacity' : 0, 'top' : cut_from_top + 'px'});   //Prep Slider Content
        }
            
        if(slide_fade == false){
        container.find('.slide-' + slider_tracker)
            .css({'opacity' : 1, 'z-index' : counter, 'left' : '0px'})
            .animate({ 'left': slider_width + 'px', opacity:0 }, ani_speed,"linear", content_stuff_alt ); //Prep , animate and call callback for slider_content
        } else {
        container.find('.slide-' + target_slide)
            .css({'opacity' : '0', 'z-index' : counter})
            .animate({ opacity:1 },ani_speed,"linear", content_stuff_alt ); //Prep , animate and call callback for slider_content
            }

        function content_stuff_alt(){

            if(content_fade == false){
                container.find('.slide-content-' + target_slide).animate({ 'top':cut_from_top,opacity:0.8},ani_content_speed);
            } else {
                container.find('.slide-content-' + target_slide).animate({opacity:0.8},ani_content_speed);
            }
        }

        slider_tracker--; rel_left--; rel_right--;     // Clocks

       if(rel_left < 1){rel_left = amount_of_slides;}          // Clock Statements
       if(rel_right < 1){rel_right = amount_of_slides;}
       if(slider_tracker < 1){slider_tracker = amount_of_slides;}

        nav.find('.slider-left').attr('rel',rel_left);        //Assign Values, Save action
        nav.find('.slider-right').attr('rel',rel_right);
        //Pagination
        pagination.find('span').fadeTo(200, 0.2);
        pagination.find('span.pag-' + slider_tracker).fadeTo(200, 1);
        evt.preventDefault(); //Prevent Click Default Action
        

    });

    };// End amount of slide check
  
   });
   
  };
    
})(jQuery);