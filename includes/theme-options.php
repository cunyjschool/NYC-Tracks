<?php



function woo_options(){
// VARIABLES
$themename = "Gazette";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/gazette-edition/';
$shortname = "woo";



$GLOBALS['template_path'] = get_bloginfo('template_directory');

//Access the WordPress Categories via an Array
$woo_categories = array();  
$woo_categories_obj = get_categories('hide_empty=0');
foreach ($woo_categories_obj as $woo_cat) {
    $woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
$categories_tmp = array_unshift($woo_categories, "Select a category:");    
       
//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; }
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");       


//Testing 
$options_select = array("one","two","three","four","five"); 
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options
$all_uploads_path = get_bloginfo('home') . '/wp-content/uploads/';
$all_uploads = get_option('woo_uploads');
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");

// THIS IS THE DIFFERENT FIELDS

$options = array();   

$options[] = array(	"name" => "General Settings",
					"type" => "heading");
						
$options[] = array(	"name" => "Theme Stylesheet",
					"desc" => "Please select your colour scheme here.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "default.css",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array( "name" => "Custom Logo",
					"desc" => "Upload a logo for your theme, or specify the image address of your online logo. (http://yoursite.com/logo.png)",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");    

$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 

$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");        

$options[] = array( "name" => "RSS URL",
					"desc" => "Enter your preferred RSS URL. (Feedburner or other)",
					"id" => $shortname."_feedburner_url",
					"std" => "",
					"type" => "text");

$options[] = array(	"name" => "Feedburner ID",
					"desc" => "Enter your Feedburner ID here.",
					"id" => $shortname."_feedburner_id",
					"std" => "",
					"type" => "text");
                        
$options[] = array( "name" => "Custom CSS",
					"desc" => "Quickly add some CSS to your theme by adding it to this block.",
					"id" => $shortname."_custom_css",
					"std" => "",
					"type" => "textarea");
					
$options[] = array(	"name" => "Home Page Carousel",
					"type" => "heading");

$options[] = array(	"name" => "Display Carousel?",
					"desc" => "Check this box if you wish to display the carousel on your homepage.",
					"id" => $shortname."_show_carousel",
					"std" => "false",
					"type" => "checkbox");
                    

						
$options[] = array( "name" => "Featured Category",
					"desc" => "Select the category that you would like to have displayed in the featured section on your homepage.",
					"id" => $shortname."_featured_category",
					"std" => "Select a category:",
					"type" => "select",
					"options" => $woo_categories);

$options[] = array(	"name" => "Featured Entries",
					"desc" => "Select the number of entries that should appear in the Featured Article scroller.",
					"id" => $shortname."_feat_entries",
					"std" => "Select a Number:",
					"type" => "select",
					"options" => $other_entries);
                    
$options[] = array(    "name" => "Slider Fade",
                    "desc" => "Slides will have a fade transition",
                    "id" => $shortname."_slider_sfade",
                    "std" => "false",
                    "type" => "checkbox");
              
$options[] = array(    "name" => "Content Fade",
                    "desc" => "Slide Content will have a fade transition",
                    "id" => $shortname."_slider_cfade",
                    "std" => "false",
                    "type" => "checkbox");         

$options[] = array(    "name" => "Slide/Fade Speed",
                    "desc" => "The time in milliseconds it will take for the slide transition to complete.",
                    "id" => $shortname."_slider_speed",
                    "std" => "500",
                    "type" => "text");  
                    
$options[] = array(    "name" => "Slide/Fade Auto Start Time",
                    "desc" => "The time before the slides start to automatically automate.",
                    "id" => $shortname."_slider_timeout",
                    "std" => "6000",
                    "type" => "text");  

$options[] = array(    "name" => "Content Slide/Fade Speed",
                    "desc" => "The time in milliseconds it will take for the slide content transition to complete.",
                    "id" => $shortname."_slider_content_speed",
                    "std" => "1000",
                    "type" => "text");  

$options[] = array(    "name" => "Carousel Height",
                    "desc" => "Adjust the defualt height of the slider.",
                    "id" => $shortname."_carousel_height",
                    "std" => "292",
                    "type" => "text"); 
			    		
$options[] = array(	"name" => "Layout",
					"type" => "heading");
						
$options[] = array(	"name" => "Activate 1 Column Homepage",
					"desc" => "This will activate the custom homepage which shows blog posts in one wider column, rather than the default two column blog post boxes.",
					"id" => $shortname."_home",
					"std" => "false",
					"type" => "checkbox");

$options[] = array(	"name" => "Disable Sidebar Tabs",
					"desc" => "Disable the tabs in the sidebar if you don't wish to use them.",
					"id" => $shortname."_tabs",
					"std" => "false",
					"type" => "checkbox");

$options[] = array(	"name" => "Display author info?",
					"desc" => "Display author info below single posts. Set this by editing your user in WP admin and adding text in 'Biographical Info'.",
					"id" => $shortname."_author",
					"std" => "false",
					"type" => "checkbox");	
						
$options[] = array(	"name" => "Home Page Video Player",
					"type" => "heading");

$options[] = array(	"name" => "Display Video?",
					"desc" => "Check this box if you wish to display the video panel on your homepage.",
					"id" => $shortname."_show_video",
					"std" => "false",
					"type" => "checkbox");				
						
$options[] = array( "name" => "Video Category",
					"desc" => "Select the category that you would like to have displayed in the video panel on your homepage.",
					"id" => $shortname."_video_category",
					"std" => "Select a category:",
					"type" => "select",
					"options" => $woo_categories);	
                        
$options[] = array( "name" => "Dynamic Images",
                    "type" => "heading");

$options[] = array( "name" => "Enable Dynamic Image Resizer",
					"desc" => "This will enable the thumb.php script. It dynamically resizes images on your site.",
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox");    
    
$options[] = array( "name" => "Automatic Image Thumbs",
					"desc" => "If no image is specified in the 'image' custom field then the first uploaded post image is used.",
					"id" => $shortname."_auto_img",
					"std" => "false",
					"type" => "checkbox");
                        
$options[] = array( "name" => "Thumbnails (homepage)",
					"desc" => "Enter an integer value i.e. 100 for the desired size which will be used when dynamically creating the images.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_home_thumb_width',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Width',
											),
									array(  'id' => $shortname. '_home_thumb_height',
											'type' => 'text',
											'std' => 57,
											'meta' => 'Height',
											)
							 ));
						
$options[] = array(	"name" => "Disable Single Post",
					"desc" => "Check this if you don't want to display the thumbnail on the single posts.",
					"id" => $shortname."_image_single",
					"std" => "false",
					"type" => "checkbox");	
						
$options[] = array( "name" => "Single Post Image",
					"desc" => "Enter an integer value i.e. 100 for the desired size which will be used when dynamically creating the images.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_single_width',
											'type' => 'text',
											'std' => 250,
											'meta' => 'Width',
											),
					                array(  'id' => $shortname. '_single_height',
											'type' => 'text',
											'std' => 180,
											'meta' => 'Height',
											)
							 ));																				

$options[] = array(	"name" => "Ads - Top Banner (468x60px)",
					"type" => "heading");

$options[] = array(	"name" => "Disable Ad",
					"desc" => "Disable the ad space",
					"id" => $shortname."_ad_top_disable",
					"std" => "false",
					"type" => "checkbox");
	
$options[] = array(	"name" => "Adsense code",
					"desc" => "Enter your adsense code here.",
					"id" => $shortname."_ad_top_adsense",
					"std" => "",
					"type" => "textarea");
	
$options[] = array(	"name" => "Banner Ad Top - Image Location",
					"desc" => "Enter the URL to the banner ad image location.",
					"id" => $shortname."_ad_top_image",
					"std" => "http://www.woothemes.com/ads/468x60a.jpg",
					"type" => "text");
	
$options[] = array(	"name" => "Banner Ad Top - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_top_url",
					"std" => "http://www.woothemes.com",
					"type" => "text");	

$options[] = array(	"name" => "Ads - Sidebar MPU (300x250px)",
					"type" => "heading");

$options[] = array(	"name" => "Disable Ad",
					"desc" => "Disable the ad space",
					"id" => $shortname."_ad_mpu_disable",
					"std" => "false",
					"type" => "checkbox");
	
$options[] = array(	"name" => "Adsense code",
					"desc" => "Enter your adsense code here.",
					"id" => $shortname."_ad_mpu_adsense",
					"std" => "",
					"type" => "textarea");
	
$options[] = array(	"name" => "Banner Ad Top - Image Location",
					"desc" => "Enter the URL to the banner ad image location.",
					"id" => $shortname."_ad_mpu_image",
					"std" => "http://www.woothemes.com/ads/300x250a.jpg",
					"type" => "text");
	
$options[] = array(	"name" => "Banner Ad Top - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_mpu_url",
					"std" => "http://www.woothemes.com",
					"type" => "text");	
			    		
$options[] = array(	"name" => "Ads - Sidebar Widget (125x125)",
					"type" => "heading");

$options[] = array(	"name" => "Rotate banners?",
					"desc" => "Check this to randomly rotate the banner ads.",
					"id" => $shortname."_ads_rotate",
					"std" => "true",
					"type" => "checkbox");	

$options[] = array(	"name" => "Banner Ad #1 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_1",
					"std" => "http://www.woothemes.com/ads/125x125a.jpg",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #1 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_1",
					"std" => "http://www.woothemes.com",
					"type" => "text");					

$options[] = array(	"name" => "Banner Ad #2 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_2",
					"std" => "http://www.woothemes.com/ads/125x125b.jpg",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #2 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_2",
					"std" => "http://www.woothemes.com",
					"type" => "text");

$options[] = array(	"name" => "Banner Ad #3 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_3",
					"std" => "http://www.woothemes.com/ads/125x125c.jpg",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #3 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_3",
					"std" => "http://www.woothemes.com",
					"type" => "text");

$options[] = array(	"name" => "Banner Ad #4 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_4",
					"std" => "http://www.woothemes.com/ads/125x125d.jpg",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #4 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_4",
					"std" => "http://www.woothemes.com",
					"type" => "text");																														
                                                   

update_option('woo_template',$options);      
update_option('woo_themename',$themename);   
update_option('woo_shortname',$shortname);
update_option('woo_manual',$manualurl);

                                     
// Woo Metabox Options
                    

$woo_metaboxes = array(

        "image" => array (
            "name" => "image",
            "label" => "Image",
            "type" => "upload",
            "desc" => "Upload file here..."
        ),
        "embed" => array (
            "name"  => "embed",
            "std"  => "",
            "label" => "Embed Code",
            "type" => "textarea",
            "desc" => "Enter the video embed code for your video"
        )
    );
    
update_option('woo_custom_template',$woo_metaboxes);      

/*
function woo_update_options(){
        $options = get_option('woo_template',$options);  
        foreach ($options as $option){
            update_option($option['id'],$option['std']);
        }   
}

function woo_add_options(){
        $options = get_option('woo_template',$options);  
        foreach ($options as $option){
            update_option($option['id'],$option['std']);
        }   
}


//add_action('switch_theme', 'woo_update_options'); 
if(get_option('template') == 'wooframework'){       
    woo_add_options();
} // end function 
*/


}

add_action('init','woo_options');  

?>