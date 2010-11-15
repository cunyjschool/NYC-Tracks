<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Admin Backend
	- Tweaked the message on theme activate
- Theme Header ouput - wp_head()
	- Styles
	- Favicon
	- Decode	
	- Localization
	- Date Format
	- woo_head_css
- Output CSS from standarized options
	- Text title
	- Custom.css

-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* Admin Backend */
/*-----------------------------------------------------------------------------------*/
function woothemes_admin_head() { 
	
	//Tweaked the message on theme activate
	?>
    <script type="text/javascript">
    jQuery(function(){
    	
        var message = '<p>This <strong>WooTheme</strong> comes with a <a href="<?php echo admin_url('admin.php?page=woothemes'); ?>">comprehensive options panel</a>. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
    	jQuery('.themes-php #message2').html(message);
    
    });
    </script>
    <?php
}


/*-----------------------------------------------------------------------------------*/
/* Theme Header output - wp_head() */
/*-----------------------------------------------------------------------------------*/
function woothemes_wp_head() { 
    
	//Styles
     $style = $_REQUEST[style];
     if ($style != '') {
		  $GLOBALS['stylesheet'] = $style;
          echo '<link href="'. get_bloginfo('template_directory') .'/styles/'. $GLOBALS['stylesheet'] . '.css" rel="stylesheet" type="text/css" />'."\n"; 
     } else { 
          $GLOBALS[stylesheet] = get_option('woo_alt_stylesheet');
          if($GLOBALS[stylesheet] != '')
               echo '<link href="'. get_bloginfo('template_directory') .'/styles/'. $GLOBALS['stylesheet'] .'" rel="stylesheet" type="text/css" />'."\n";         
          else
               echo '<link href="'. get_bloginfo('template_directory') .'/styles/default.css" rel="stylesheet" type="text/css" />'."\n";         		  
     } 
     
	// Custom.css insert
	echo '<link href="'. get_bloginfo('template_directory') .'/custom.css" rel="stylesheet" type="text/css" />'."\n";   
	
	// Favicon
	if(get_option('woo_custom_favicon') != '') {
        echo '<link rel="shortcut icon" href="'.  get_option('woo_custom_favicon')  .'"/>'."\n";
    }    
            
    //Decode
	$decode = $_REQUEST['decode'];
	if ($decode == 'true') 
		echo '<meta name="generator" content="' . get_option('woo_settings_encode') . '" />';

	// Localization
	load_theme_textdomain(woothemes);	
	
	// Date format
	$GLOBALS['woodate'] = get_option('woo_date');	
	if ( $GLOBALS['woodate'] == "" )
		$GLOBALS['woodate'] = "d. M, Y";	
		
	// Output CSS from standarized options	
	woo_head_css();

}


/*-----------------------------------------------------------------------------------*/
/* Output CSS from standarized options */
/*-----------------------------------------------------------------------------------*/
function woo_head_css() {

	$text_title = get_option('woo_texttitle');
    $custom_css = get_option('woo_custom_css');

	// Add CSS to output
	if ($text_title == "true") {
		$output .= '#logo img { display:none; }' . "\n";
		$output .= '#logo .site-title, #logo .site-description { display:block; } ' . "\n";
	} 
	elseif ($custom_css <> '') {
		$output .= $custom_css . "\n";
	}
	
	// Output styles
	if (isset($output)) {
		$output = "<!-- Woo Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
		echo $output;
	}

}

?>