<?php
// WooThemes Admin Interface

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- WooThemes Admin Interface - woothemes_add_admin
- Framework options panel - woothemes_options_page
- Framework Settings page - woothemes_framework_settings_page
- woo_load_only
- Ajax Save Action - woo_ajax_callback
- Generates The Options - woothemes_machine
- WooThemes Uploader - woothemes_uploader_function
- Woothemes Theme Version Checker - woothemes_version_checker

-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* WooThemes Admin Interface - woothemes_add_admin */
/*-----------------------------------------------------------------------------------*/

/* 
Not made it into the framework just yet
function woo_option_hook($name) {
		$new_value = get_option('themename_' . $name );
        add_filter('option_' . $name, create_function('$a', 'return "' . addslashes($new_value) . '";'));
}

woo_options_hook('woo_logo');
*/

function woothemes_add_admin() {

    global $query_string;
    $options =  get_option('woo_template');      
    $themename =  get_option('woo_themename');      
    $shortname =  get_option('woo_shortname'); 
    
    if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'woothemes' ) {
	
		if (isset($_REQUEST['woo_save']) && 'reset' == $_REQUEST['woo_save']) {
			global $wpdb;
			$query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'woo_%'";
			$wpdb->query($query);
			header("Location: admin.php?page=woothemes&reset=true");
			die;
		}

    } 
   
    // Check all the Options, then if the no options are created for a relative sub-page... it's not created.
    if(function_exists('add_object_page'))
    {
        add_object_page ('Page Title', $themename, 8,'woothemes', 'woothemes_options_page', get_bloginfo('template_url'). '/functions/images/woo-icon.png');
    }
    else
    {
        add_menu_page ('Page Title', $themename, 8,'woothemes_home', 'woothemes_options_page', get_bloginfo('template_url'). '/functions/images/woo-icon.png'); 
    }
    $woopage = add_submenu_page('woothemes', $themename, 'Theme Options', 8, 'woothemes','woothemes_options_page'); // Default
	$wooframeworksettings = add_submenu_page('woothemes', 'Framework Settings', 'Framework Settings', 8, 'woothemes_framework_settings', 'woothemes_framework_settings_page');
    //Checks to prevent 2.9 bugs from wrecking the options panel - will re-activate on 2.9.1
    $update_core = get_transient('update_core');
    $core_local_wp_version = $update_core->version_checked;
    if($core_local_wp_version != '2.9') {
         $woothemepage = add_submenu_page('woothemes', 'Available WooThemes', 'Buy Themes', 8, 'woothemes_themes', 'woothemes_more_themes_page');    
    }

	//Woothemes Custom Navigation Menu	
	//add_submenu_page('woothemes', 'Custom Navigation', 'Custom Navigation', 8, 'custom_navigation', 'woo_custom_navigation');
  	
	// Add framework functionaily to the head individually
	//add_action("admin_head",'woo_add_admin');
	add_action("admin_print_scripts-$woopage", 'woo_load_only');
	add_action("admin_print_scripts-$wooframeworksettings", 'woo_load_only');
	add_action("admin_print_scripts-$woothemepage", 'woo_load_only' );
     
} 



/*-----------------------------------------------------------------------------------*/
/* Framework options panel - woothemes_options_page */
/*-----------------------------------------------------------------------------------*/

function woothemes_options_page(){

    $options =  get_option('woo_template');      
    $themename =  get_option('woo_themename');      
    $shortname =  get_option('woo_shortname');
    $manualurl =  get_option('woo_manual'); 
    
    //Version in Backend Head
    $theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
    $local_version = $theme_data['Version'];
    
    
    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php');
	
	$pos = strpos($manualurl, 'documentation');
	$theme_slug = str_replace("/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation

	//add filter to make the rss read cache clear every 4 hours
	add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );
	
    //Check for latest version of the theme
    //Checks to prevent 2.9 bugs from wrecking the options panel - will re-activate on 2.9.1      
    $update_core = get_transient('update_core');
    $core_local_wp_version = $update_core->version_checked;
    if($core_local_wp_version != '2.9' && get_option('woo_updater') == true) {
        $update_message = woothemes_version_checker ($local_version);
    }

?>
<div class="wrap" id="woo_container">
<div id="woo-popup-save" class="woo-save-popup"><div class="woo-save-save">Options Updated</div></div>
<div id="woo-popup-reset" class="woo-save-popup"><div class="woo-save-reset">Options Reset</div></div>
    <?php // <form method="post"  enctype="multipart/form-data"> ?>
    <form action="" enctype="multipart/form-data" id="wooform">
        <div id="header">
			<div class="logo"><img alt="WooThemes" src="<?php echo bloginfo('template_url'); ?>/functions/images/logo.png"/></div>
			<div class="theme-info">
				<span class="theme"><?php echo $themename; ?> <?php echo $local_version; ?></span>
				<span class="framework">Framework <?php echo get_option('woo_framework_version'); ?></span>
			</div>
			<div class="clear"></div>
		</div>
        <?php 
		// Rev up the Options Machine
        
        $return = woothemes_machine($options);
        ?>
		<div id="support-links">
			<ul>
				<li class="changelog"><a title="Theme Changelog" href="<?php echo $manualurl; ?>#Changelog">View Changelog</a></li>
				<li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>">View Themedocs</a></li>
				<li class="forum"><a href="http://forum.woothemes.com" target="blank">Visit Forum</a></li>
                <li class="right"><img style="display:none" src="<?php echo bloginfo('template_url'); ?>/functions/images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." /><a href="#" id="expand_options">[+]</a> <input type="submit" value="Save All Changes" class="button submit-button" /></li>
			</ul>
		</div>
		
        <?php /* Legecy
        <?php if ( $_REQUEST['saved'] ) { ?><div class="happy"><?php echo $themename; ?>'s Options has been updated!</div><?php } ?>
        <?php if ( $_REQUEST['reset'] ) { ?><div class="warning"><?php echo $themename; ?>'s Options has been reset!</div><?php } ?> */ ?>   
		
        <div id="main">
	        <div id="woo-nav">
				<ul>
					<?php echo $return[1] ?>
				</ul>		
			</div>
			<div id="content">
	         <?php echo $return[0]; /* Settings */ ?>
	        </div>
	        <div class="clear"></div>
	        
        </div>
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo bloginfo('template_url'); ?>/functions/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
            
        <input type="submit" value="Save All Changes" class="button submit-button" />
        <?php /* <input type="hidden" name="woo_save" value="save" /> */ // Legacy ?>
        
        
        </form>
     
        <form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="wooform-reset">
            <span class="submit-footer-reset">
            <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
            <input type="hidden" name="woo_save" value="reset" /> 
            </span>
        </form>
       
            
        </div>
        <?php  echo $update_message; ?>    
        <?php  //wp_nonce_field('reset_options'); echo "\n"; // Legacy ?>


<div style="clear:both;"></div>    
</div><!--wrap-->

 <?php
}



/*-----------------------------------------------------------------------------------*/
/* Framework Settings page - woothemes_framework_settings_page */
/*-----------------------------------------------------------------------------------*/

function woothemes_framework_settings_page(){

    $options =  get_option('woo_settings_template');      
    $themename =  get_option('woo_themename');      
    $shortname =  get_option('woo_shortname');
    $manualurl =  get_option('woo_manual'); 
    
    //Version in Backend Head
    $theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
    $local_version = $theme_data['Version'];
    
    
    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php');

	$pos = strpos($manualurl, 'documentation');
	$theme_slug = str_replace("/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation
	
    //add filter to make the rss read cache clear every 4 hours
    add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );
    
    //Check for latest version of the theme
    //Checks to prevent 2.9 bugs from wrecking the options panel - will re-activate on 2.9.1      
    $update_core = get_transient('update_core');
    $core_local_wp_version = $update_core->version_checked;
    if($core_local_wp_version != '2.9' && get_option('woo_updater') == true) {
        $update_message = woothemes_version_checker ($local_version);
    }

?>
<div class="wrap" id="woo_container">
<div id="woo-popup-save" class="woo-save-popup"><div class="woo-save-save">Options Updated</div></div>
<div id="woo-popup-reset" class="woo-save-popup"><div class="woo-save-reset">Options Reset</div></div>
    <?php // <form method="post"  enctype="multipart/form-data"> ?>
    <form action="" enctype="multipart/form-data" id="wooform">
        <div id="header">
			<div class="logo"><img alt="WooThemes" src="<?php echo bloginfo('template_url'); ?>/functions/images/logo.png"/></div>
			<div class="theme-info">
				<span class="theme"><?php echo $themename; ?> <?php echo $local_version; ?></span>
				<span class="framework">Framework <?php echo get_option('woo_framework_version'); ?></span>
			</div>
			<div class="clear"></div>
		</div>
		<div id="support-links">
	
			<ul>
				<li class="changelog"><a title="Theme Changelog" href="<?php echo $manualurl; ?>#Changelog">View Changelog</a></li>
				<li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>">View Themedocs</a></li>
				<li class="forum"><a href="http://forum.woothemes.com" target="blank">Visit Forum</a></li>
                <li class="right"><img style="display:none" src="<?php echo bloginfo('template_url'); ?>/functions/images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." /><a href="#" id="expand_options">[+]</a> <input type="submit" value="Save All Changes" class="button submit-button" /></li>
			</ul>
	
		</div>
        
        <?php /* Legecy
        <?php if ( $_REQUEST['saved'] ) { ?><div class="happy"><?php echo $themename; ?>'s Options has been updated!</div><?php } ?>
        <?php if ( $_REQUEST['reset'] ) { ?><div class="warning"><?php echo $themename; ?>'s Options has been reset!</div><?php } ?> */ ?>   
		
        <div id="main">
	        <div id="woo-nav">
				<ul>
					<li class="current"><a href="#importoptions">Import Options</a></li>
					<li class=""><a href="#exportoptions">Export Options</a></li>
				</ul>		
			</div>
			<div id="content">
	         	<div id="importoptions" class="group" style="display: block;">
					<h2>Import Options</h2>
					<div class="section">
                        <h3 class="heading">Import options from another WooThemes instance.</h3>
                        <div class="option">
                            <div class="controls">
                            <textarea rows="8" cols="" id="woo_import_options" name="woo_import_options" class="woo-input"></textarea>
                            <br/>
                            </div>
                            <div class="explain">
                                You can transfer options from another WooThemes (same theme) to this one by copying the export code and adding it here. Works best if it's imported from identical themes.
                            </div>
                            <div class="clear"/></div>
                            </div>
                        </div>
                  </div>
                  <div id="exportoptions" class="group" style="display: block;"> 
                     <h2>Export Options</h2>
                     <div class="section">
                        <h3 class="heading">Use the code below to export this themes settings to another theme.</h3>
                        <div class="option">
                            <div class="controls">
                            <?php
                            //Create, Encrypt and Update the Saved Settings
							global $wpdb;
							$query = "SELECT * FROM $wpdb->options WHERE option_name LIKE 'woo_%' AND NOT option_name = 'woo_template' AND NOT option_name = 'woo_custom_template' AND NOT option_name = 'woo_settings_encode' AND NOT option_name = 'woo_export_options' AND NOT option_name = 'woo_import_options' AND NOT option_name = 'woo_framework_version' AND NOT option_name = 'woo_manual' AND NOT option_name = 'woo_shortname'";
							$results = $wpdb->get_results($query);
							
							foreach ($results as $result){
							
                       			 $output[$result->option_name] = $result->option_value;
							
							}
							$output = serialize($output);
							?>
                            <textarea rows="8" cols="" class="woo-input"><?php echo base64_encode($output); ?></textarea>
                            <br/>
                            </div>
                            <div class="explain">
                                You can transfer options from another WooThemes (same theme) to this one by copying the export code and adding it here. Works best if it's imported from identical themes.
                            </div>
                            <div class="clear"/></div>
                            </div>
                        </div>
                    </div>
                    </div>
	        	<div class="clear"></div>
          
	        
        </div>
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo bloginfo('template_url'); ?>/functions/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
        <input type="submit" value="Save All Changes" class="button submit-button" />
        <?php /* <input type="hidden" name="woo_save" value="save" /> */ // Legacy ?>
        
        
        </form>
     
        <form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="wooform-reset">
            <span class="submit-footer-reset">
            <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
            <input type="hidden" name="woo_save" value="reset" /> 
            </span>
        </form>
        
        </div>
        <?php  echo $update_message; ?>    
        <?php  //wp_nonce_field('reset_options'); echo "\n"; // Legacy ?>


<div style="clear:both;"></div>    
</div><!--wrap-->

 <?php
}



/*-----------------------------------------------------------------------------------*/
/* woo_load_only */
/*-----------------------------------------------------------------------------------*/

function woo_load_only() {

add_action('admin_head', 'woo_admin_head');
	
	function woo_admin_head() { 
	
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin-style.css" media="screen" />';
	
	 // COLOR Picker ?>
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/functions/js/colorpicker/css/colorpicker.css" />
	<script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/functions/js/colorpicker/js/colorpicker.js"></script>
	<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){
		//Color Picker
		<?php $options = get_option('woo_template');
		
		foreach($options as $option){ 
		if($option['type'] == 'color' OR $option['type'] == 'typography'){
			if($option['type'] == 'typography'){
				$option_id = $option['id'] . '_color';
			}
			else {
				$option_id = $option['id'];
			}
			?>
			 jQuery('#<?php echo $option_id; ?>').children('div').css('backgroundColor', '<?php echo get_option($option_id); ?>');    
			 jQuery('#<?php echo $option_id; ?>').ColorPicker({
				color: '<?php echo get_option($option_id); ?>',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					//jQuery(this).css('border','1px solid red');
					jQuery('#<?php echo $option_id; ?>').children('div').css('backgroundColor', '#' + hex);
					jQuery('#<?php echo $option_id; ?>').next('input').attr('value','#' + hex);
					
				}
			  });
		  <?php } } ?>
	 
	});
	
	</script> 
	<?php
	//AJAX Upload
	?>
	<script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/functions/js/ajaxupload.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
		
		var flip = 0;
			
		jQuery('#expand_options').click(function(){
			if(flip == 0){
				flip = 1;
				jQuery('#woo_container #woo-nav').hide();
				jQuery('#woo_container #content').width(755);
				jQuery('#woo_container .group').add('#woo_container .group h2').show();

				jQuery(this).text('[-]');
				
			} else {
				flip = 0;
				jQuery('#woo_container #woo-nav').show();
				jQuery('#woo_container #content').width(595);
				jQuery('#woo_container .group').add('#woo_container .group h2').hide();
				jQuery('#woo_container .group:first').show();
				jQuery('#woo_container #woo-nav li').removeClass('current');
				jQuery('#woo_container #woo-nav li:first').addClass('current');
				
				jQuery(this).text('[+]');
			
			}
		
		});
		
			jQuery('.group').hide();
			jQuery('.group:first').fadeIn();
			jQuery('#woo-nav li:first').addClass('current');
			jQuery('#woo-nav li a').click(function(evt){
			
					jQuery('#woo-nav li').removeClass('current');
					jQuery(this).parent().addClass('current');
					
					var clicked_group = jQuery(this).attr('href');
	 
					jQuery('.group').hide();
					
						jQuery(clicked_group).fadeIn();
	
					evt.preventDefault();
					
				});
			
			if('<?php if(isset($_REQUEST['reset'])) { echo $_REQUEST['reset'];} else { echo 'false';} ?>' == 'true'){
				
				var reset_popup = jQuery('#woo-popup-reset');
				reset_popup.fadeIn();
				window.setTimeout(function(){
					   reset_popup.fadeOut();                        
					}, 2000);
					//alert(response);
				
			}
				
		//Update Message popup
		jQuery.fn.center = function () {
			this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
			this.css("left", 250 );
			return this;
		}
	
		
		jQuery('#woo-popup-save').center();
		jQuery('#woo-popup-reset').center();
		jQuery(window).scroll(function() { 
		
			jQuery('#woo-popup-save').center();
			jQuery('#woo-popup-reset').center();
		
		});
		
		
	
		//AJAX Upload
		jQuery('.image_upload_button').each(function(){
		
		var clickedObject = jQuery(this);
		var clickedID = jQuery(this).attr('id');	
		new AjaxUpload(clickedID, {
			  action: '<?php echo admin_url("admin-ajax.php"); ?>',
			  name: clickedID, // File upload name
			  data: { // Additional data to send
					action: 'woo_ajax_post_action',
					type: 'upload',
					data: clickedID },
			  autoSubmit: true, // Submit file after selection
			  responseType: false,
			  onChange: function(file, extension){},
			  onSubmit: function(file, extension){
					clickedObject.text('Uploading'); // change button text, when user selects file	
					this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
					interval = window.setInterval(function(){
						var text = clickedObject.text();
						if (text.length < 13){	clickedObject.text(text + '.'); }
						else { clickedObject.text('Uploading'); } 
					}, 200);
			  },
			  onComplete: function(file, response) {
			   
			  	window.clearInterval(interval);
				clickedObject.text('Upload Image');	
				this.enable(); // enable upload button
				
				// If there was an error
			  	if(response.search('Upload Error') > -1){
					var buildReturn = '<span class="upload-error">' + response + '</span>';
					jQuery(".upload-error").remove();
					clickedObject.parent().after(buildReturn);
				
				}
				else{
					var buildReturn = '<img class="hide woo-option-image" id="image_'+clickedID+'" src="'+response+'" width="300" alt="" />';
//					var buildReturn = '<img class="hide" id="image_'+clickedID+'" src="<?php bloginfo('template_url') ?>/thumb.php?src='+response+'&w=345" alt="" />';
					jQuery(".upload-error").remove();
					jQuery("#image_" + clickedID).remove();	
					clickedObject.parent().after(buildReturn);
					jQuery('img#image_'+clickedID).fadeIn();
					clickedObject.next('span').fadeIn();
					clickedObject.parent().prev('input').val(response);
				}
			  }
			});
		
		});
		
		//AJAX Remove (clear option value)
		jQuery('.image_reset_button').click(function(){
		
				var clickedObject = jQuery(this);
				var clickedID = jQuery(this).attr('id');
				var theID = jQuery(this).attr('title');	

				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
			
				var data = {
					action: 'woo_ajax_post_action',
					type: 'image_reset',
					data: theID
				};
				
				jQuery.post(ajax_url, data, function(response) {
					var image_to_remove = jQuery('#image_' + theID);
					var button_to_hide = jQuery('#reset_' + theID);
					image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
					button_to_hide.fadeOut();
					clickedObject.parent().prev('input').val('');
					
					
					
				});
				
				return false; 
				
			});   	 	


	
		//Save everything else
		jQuery('#wooform').submit(function(){
			
				function newValues() {
				  var serializedValues = jQuery("#wooform").serialize();
				  return serializedValues;
				}
				jQuery(":checkbox, :radio").click(newValues);
				jQuery("select").change(newValues);
				jQuery('.ajax-loading-img').fadeIn();
				var serializedReturn = newValues();
				 
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
			
				 //var data = {data : serializedReturn};
				var data = {
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'woothemes_framework_settings'){ ?>
					type: 'framework',
					<?php } ?>
					action: 'woo_ajax_post_action',
					data: serializedReturn
				};
				
				jQuery.post(ajax_url, data, function(response) {
					var success = jQuery('#woo-popup-save');
					var loading = jQuery('.ajax-loading-img');
					loading.fadeOut();  
					success.fadeIn();
					window.setTimeout(function(){
					   success.fadeOut(); 
					   
					                        
					}, 2000);
				});
				
				return false; 
				
			});   	 	
			
		});
	</script>
	
<?php }
}



/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - woo_ajax_callback */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_woo_ajax_post_action', 'woo_ajax_callback');

function woo_ajax_callback() {
	global $wpdb; // this is how you get access to the database
	$themename = get_option('template') . "_";
	//Uploads
	if($_POST['type'] == 'upload'){
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
		$override['test_form'] = false;
	    $override['action'] = 'wp_handle_upload';    
	    $uploaded_file = wp_handle_upload($filename,$override);
		 
	            $upload_tracking[] = $clickedID;
	            update_option( $clickedID , $uploaded_file['url'] );
				//update_option( $themename . $clickedID , $uploaded_file['url'] );
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
		 else { echo $uploaded_file['url']; } // Is the Response
	}

	elseif($_POST['type'] == 'image_reset'){
			
			$id = $_POST['data']; // Acts as the name
		    global $wpdb;
            $query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$id'";
            $wpdb->query($query);
            //die;
	
	}
	elseif($_POST['type'] == 'framework'){
	
		$data = $_POST['data'];
		parse_str($data,$output);

		foreach($output as $id => $value){
		
			if($id == 'woo_import_options'){
				
				//Decode and over write options.
				$new_import = base64_decode($value);
				$new_import = unserialize($new_import);
				print_r($new_import);
				
				foreach($new_import as $id2 => $value2){
					
					update_option($id2,$value2);
				
				}

			}
		
		}

	}

	else {
		$data = $_POST['data'];
		parse_str($data,$output);
		
		print_r($output);
        
		$options =  get_option('woo_template');
		
		foreach($options as $option_array){
				
				$id = $option_array['id'];
				$old_value = get_option($id);
				$new_value = $output[$option_array['id']];
				$type = $option_array['type'];
				
				
                if ( is_array($type)){
                            foreach($type as $array){
                                if($array['type'] == 'text'){
                                    $id = $array['id'];
                                    $new_value = $output[$id];
                                    update_option( $id, stripslashes($new_value));
                                }
                            }                 
                }
				elseif($new_value == null && $type == 'checkbox'){ // Checkbox Save
					
					update_option($id,'false');
					//update_option($themename . $id,'false');
					
					
				}
				elseif ($new_value == 'true' && $type == 'checkbox'){ // Checkbox Save
					
					update_option($id,'true');
					//update_option($themename . $id,'true');
					
				}
				elseif($type == 'multicheck'){ // Multi Check Save
					
					$options = $option_array['options'];
					
					foreach ($options as $options_id => $options_value){
						
						$multicheck_id = $id . "_" . $options_id;
						
						if($output[$multicheck_id] == null){
						  update_option($multicheck_id,'false');
						  //update_option($themename . $multicheck_id,'false');    
						}
						else{
						   update_option($multicheck_id,'true'); 
						   //update_option($themename . $multicheck_id,'true'); 
						}
						
					}
	
				} 
				
				elseif($type == 'typography'){
						
					$typography_array = array();	
					
					/* Size */
					$typography_array['size'] = $output[$option_array['id'] . '_size'];
						
					/* Unit  */
					$typography_array['unit'] = $output[$option_array['id'] . '_unit'];
						
					/* Face  */
					$typography_array['face'] = $output[$option_array['id'] . '_face'];
						
					/* Style  */
					$typography_array['style'] = $output[$option_array['id'] . '_style'];
						
					/* Color  */
					$typography_array['color'] = $output[$option_array['id'] . '_color'];
						
					update_option($id,$typography_array);
						
						
				}
				elseif($new_value != $old_value && $type != 'upload_min'){
				
					update_option($id,stripslashes($new_value));
					//update_option($themename . $id,$new_value);
				}

		}
		//echo 'non_upload';
	}
	
	
	//Create, Encrypt and Update the Saved Settings
	global $wpdb;
	
	$query = "SELECT * FROM $wpdb->options WHERE option_name LIKE 'woo_%' AND NOT option_name = 'woo_template' AND NOT option_name = 'woo_custom_template' AND NOT option_name = 'woo_settings_encode' AND NOT option_name = 'woo_export_options' AND NOT option_name = 'woo_import_options' AND NOT option_name = 'woo_framework_version' AND NOT option_name = 'woo_manual' AND NOT option_name = 'woo_shortname'";
	
	$results = $wpdb->get_results($query);
	
	$output = "<ul>";
	foreach ($results as $result){
			$output .= '<li><strong>' . $result->option_name . '</strong> - ' . $result->option_value . '</li>';
	}
	$output .= "</ul>";
	$output = base64_encode($output);
	update_option('woo_settings_encode',$output);



  die();

}



/*-----------------------------------------------------------------------------------*/
/* Generates The Options - woothemes_machine */
/*-----------------------------------------------------------------------------------*/

function woothemes_machine($options) {
        
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		//Start Heading
		 if ( $value['type'] != "heading" )
		 {
			//$output .= '<div class="section section-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
			$output .= '<div class="section section-'.$value['type'].'">'."\n";
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

		 } 
		 //End Heading
		$select_value = '';                                   
		switch ( $value['type'] ) {
		case 'text':
			$val = $value['std'];
			if ( get_settings( $value['id'] ) != "") { $val = get_settings($value['id']); }
			$output .= '<input class="woo-input" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
		break;
		case 'select':

			$output .= '<select class="woo-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
		
			$select_value = get_settings( $value['id']);
			 
			foreach ($value['options'] as $option) {
				
				$selected = '';
				
				   if($select_value != '') {
						if ( $select_value == $option) { $selected = ' selected="selected"';} 
				   } else {
					if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				   }
				  
				$output .= '<option'. $selected .'>';
				$output .= $option;
				$output .= '</option>';
			 } 
			 $output .= '</select>';

			
		break;
		case 'textarea':
		
			if(isset($value['options']) && isset($value['std'])) {
				$ta_options = $value['options'];
				$cols = $ta_options['cols'];
				$ta_value = $value['std']; 
			} else {
				$cols = '8';
				$ta_value = '';		
			}
			
				if( get_settings($value['id']) != "") { $ta_value = stripslashes(get_settings($value['id'])); }
				$output .= '<textarea class="woo-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
			
			
		break;
		case "radio":
			
			 $select_value = get_settings( $value['id']);
				   
			 foreach ($value['options'] as $key => $option) 
			 { 

				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; } 
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="woo-input woo-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			
			}
			 
		break;
		case "checkbox": 
		
		   $std = $value['std'];  
		   
		   $saved_std = get_option($value['id']);
		   
		   $checked = '';
			
			if(!empty($saved_std)) {
				if($saved_std == 'true') {
				$checked = 'checked="checked"';
				}
				else{
				   $checked = '';
				}
			}
			elseif( $std == 'true') {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$output .= '<input type="checkbox" class="checkbox woo-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';

		break;
		case "multicheck":
		
			$std =  $value['std'];         
			
			foreach ($value['options'] as $key => $option) {
											 
			$woo_key = $value['id'] . '_' . $key;
			$saved_std = get_option($woo_key);
					
			if(!empty($saved_std)) 
			{ 
				  if($saved_std == 'true'){
					 $checked = 'checked="checked"';  
				  } 
				  else{
					  $checked = '';     
				  }    
			} 
			elseif( $std == $key) {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';                                                                                    }
			$output .= '<input type="checkbox" class="checkbox woo-input" name="'. $woo_key .'" id="'. $woo_key .'" value="true" '. $checked .' /><label for="'. $woo_key .'">'. $option .'</label><br />';
										
			}
		break;
		case "upload":
			
			$output .= woothemes_uploader_function($value['id'],$value['std'],null);
			
		break;
		case "upload_min":
			
			$output .= woothemes_uploader_function($value['id'],$value['std'],'min');
			
		break;
		case "color":
			$val = $value['std'];
			$stored  = get_settings( $value['id'] );
			if ( $stored != "") { $val = $stored; }
			$output .= '<div id="' . $value['id'] . '" class="colorSelector"><div></div></div>';
			$output .= '<input class="woo-color" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
		break;   
		
		case "typography":
		
			$default = $value['std'];
			$typography_stored = get_settings( $value['id'] );
			
			/* Font Size */
			$val = $default['size'];
			if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
			$output .= '<select class="woo-typography woo-typography-size" name="'. $value['id'].'_size" id="'. $value['id'].'_size">';
				for ($i = 9; $i < 19; $i++){ 
					if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
					$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'</option>'; }
			$output .= '</select>';
			
			/* Font Unit */
			$val = $default['unit'];
			if ( $typography_stored['unit'] != "") { $val = $typography_stored['unit']; }
				$em = ''; $px = '';
			if($val == 'em'){ $em = 'selected="selected"'; }
			if($val == 'px'){ $px = 'selected="selected"'; }
			$output .= '<select class="woo-typography woo-typography-unit" name="'. $value['id'].'_unit" id="'. $value['id'].'_unit">';
			$output .= '<option value="px '. $px .'">px</option>';
			$output .= '<option value="em" '. $em .'>em</option>';
			$output .= '</select>';
			
			/* Font Face */
			$val = $default['face'];
			if ( $typography_stored['face'] != "") { $val = $typography_stored['face']; }
				$font01 = ''; $font02 = ''; $font03 = ''; $font04 = '';
			if($val == 'arial'){ $font01 = 'selected="selected"'; }
			if($val == 'verdana'){ $font02 = 'selected="selected"'; }
			if($val == 'georgia'){ $font03 = 'selected="selected"'; }
			if($val == 'helvetica'){ $font04 = 'selected="selected"'; }
			
			$output .= '<select class="woo-typography woo-typography-face" name="'. $value['id'].'_face" id="'. $value['id'].'_face">';
			$output .= '<option value="arial" '. $font01 .'>Arial</option>';
			$output .= '<option value="verdana" '. $font02 .'>Verdana</option>';
			$output .= '<option value="georgia" '. $font03 .'>Georgia</option>';
			$output .= '<option value="helvetica" '. $font04 .'>Helvetica</option>';
			$output .= '</select>';
			
			/* Font Weight */
			$val = $default['style'];
			if ( $typography_stored['style'] != "") { $val = $typography_stored['style']; }
				$normal = ''; $italic = ''; $bold = ''; $bolditalic = '';
			if($val == 'normal'){ $normal = 'selected="selected"'; }
			if($val == 'italic'){ $italic = 'selected="selected"'; }
			if($val == 'bold'){ $bold = 'selected="selected"'; }
			if($val == 'bold italic'){ $bolditalic = 'selected="selected"'; }
			
			$output .= '<select class="woo-typography woo-typography-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
			$output .= '<option value="normal" '. $normal .'>Normal</option>';
			$output .= '<option value="italic" '. $italic .'>Italic</option>';
			$output .= '<option value="bold" '. $bold .'>Bold</option>';
			$output .= '<option value="bold italic" '. $bolditalic .'>Bold/Italic</option>';
			$output .= '</select>';
			
			/* Font Color */
			$val = $default['color'];
			if ( $typography_stored['color'] != "") { $val = $typography_stored['color']; }			
			$output .= '<div id="' . $value['id'] . '_color" class="colorSelector"><div></div></div>';
			$output .= '<input class="woo-color woo-typography woo-typography-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';

		break;                               
		
		case "heading":
			
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['name']) );
			$jquery_click_hook = "woo-option-" . $jquery_click_hook;
//			$jquery_click_hook = "woo-option-" . str_replace("&","",str_replace("/","",str_replace(".","",str_replace(")","",str_replace("(","",str_replace(" ","",strtolower($value['name'])))))));
			$menu .= '<li><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;                                  
		} 
		
		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){
			
					$id =   $array['id']; 
					$std =   $array['std'];
					$saved_std = get_option($id);
					if($saved_std != $std && !empty($saved_std) ){$std = $saved_std;} 
					$meta =   $array['meta'];
					
					if($array['type'] == 'text') { // Only text at this point
						 
						 $output .= '<input class="input-text-small woo-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
						 $output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
		}
		if ( $value['type'] != "heading" ) { 
			if ( $value['type'] != "checkbox" ) 
				{ 
				$output .= '<br/>';
				}
			$output .= '</div><div class="explain">'. $value['desc'] .'</div>'."\n";
			$output .= '<div class="clear"></div></div></div>'."\n";
			}
	   
	}
    $output .= '</div>';
    return array($output,$menu);

}



/*-----------------------------------------------------------------------------------*/
/* WooThemes Uploader - woothemes_uploader_function */
/*-----------------------------------------------------------------------------------*/

function woothemes_uploader_function($id,$std,$mod){

    //$uploader .= '<input type="file" id="attachement_'.$id.'" name="attachement_'.$id.'" class="upload_input"></input>';
    //$uploader .= '<span class="submit"><input name="save" type="submit" value="Upload" class="button upload_save" /></span>';
    
	$uploader = '';
    $upload = get_option($id);
	
	if($mod != 'min') { 
			$val = $std;
            if ( get_settings( $id ) != "") { $val = get_settings($id); }
            $uploader .= '<input class="woo-input" name="'. $id .'" id="'. $id .'_upload" type="text" value="'. $val .'" />';
	}
	
	$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">Upload Image</span>';
	
	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
	
	$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
	$uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
	if(!empty($upload)){
		$upload = cleanSource($upload);
    	$uploader .= '<a class="woo-uploaded-image" href="'. $upload . '">';
    	$uploader .= '<img id="image_'.$id.'" class="woo-option-image" src="'.$upload.'" alt="" />';
//    	$uploader .= '<img id="image_'.$id.'" src="'.get_bloginfo('template_url').'/thumb.php?src='.$upload.'&w=345" alt="" />';
    	$uploader .= '</a>';
		}
	$uploader .= '<div class="clear"></div>' . "\n"; 


return $uploader;
}



/*-----------------------------------------------------------------------------------*/
/* Woothemes Theme Version Checker - woothemes_version_checker */
/* @local_version is the installed theme version number */
/*-----------------------------------------------------------------------------------*/

function woothemes_version_checker ($local_version) {

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed('http://woothemes.com/?feed=updates&theme=' . get_option('template'));
	
	
	// Of the RSS is failed somehow.
	if(isset($rss->errors) && $rss->errors) {
		
		$update_message = '';
	
	} else {
	
		//Figure out how many total items there are, but limit it to 5. 
		$maxitems = $rss->get_item_quantity(1); 
			
		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items(0, $maxitems); 
		if ($maxitems == 0) $latest_version_via_rss = 0;
			else
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ) : 
			$latest_version_via_rss = $item->get_title();
			endforeach; 
		 
		//Check if version is the latest - assume standard structure x.x.x
		$pieces_rss = explode(".", $latest_version_via_rss);
		$pieces_local = explode(".", $local_version);
		//account for null values in second position x.2.x
		
		if(isset($pieces_rss[0]) && $pieces_rss[0] != 0) {
		
			if ($pieces_rss[1]) {
			
			}
			else {
				$pieces_rss[1] = '0';
			}
			
			if ($pieces_local[1]) {
			
			}
			else {
				$pieces_local[1] = '0';
			}
			//account for null values in third position x.x.3
			if ($pieces_rss[2]) {
			
			}
			else {
				$pieces_rss[2] = '0';
			}
			
			if ($pieces_local[2]) {
			
			}
			else {
				$pieces_local[2] = '0';
			}
		
		
			//do the comparisons
			$version_sentinel = false;
			if ($pieces_rss[0] > $pieces_local[0]) {
				$version_sentinel = true;
			}
			if (($pieces_rss[1] > $pieces_local[1]) AND ($version_sentinel == false) AND ($pieces_rss[0] == $pieces_local[0])) {
				$version_sentinel = true;
			}
			if (($pieces_rss[2] > $pieces_local[2]) AND ($version_sentinel == false) AND ($pieces_rss[0] == $pieces_local[0]) AND ($pieces_rss[1] == $pieces_local[1])) {
				$version_sentinel = true;
			}
			
			//set version checker message
			if ($version_sentinel == true) {
				$update_message = '<div class="update_available">Theme update is available (v.' . $latest_version_via_rss . ') - <a href="http://www.woothemes.com/amember">Get the new version</a>.</div>';
			}
			else {
				$update_message = '';
			}
		} else {
				$update_message = '';
		}
	}
	
	// OLD Version Checker	    
	/*if($local_version != $latest_version_via_rss AND $latest_version_via_rss != 0)
	 	$update_message = '<div class="update_available">Theme update is available (v.' . $latest_version_via_rss . ') - <a href="http://www.woothemes.com/amember">Get the new version</a>.</div>';
	 else{
		$update_message = '';
	 }*/
	 
	 return $update_message;

}


?>