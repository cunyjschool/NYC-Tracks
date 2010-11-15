<div id="mpu_banner" class="wrap widget">
	
	<?php if (get_option('woo_ad_mpu_adsense') <> "") { echo stripslashes(get_option('woo_ad_mpu_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_mpu_url'); ?>"><img src="<?php echo get_option('woo_ad_mpu_image'); ?>" width="300" height="250" alt="<?php _e('Advert',woothemes); ?>" /></a>
		
	<?php } ?>	

</div>