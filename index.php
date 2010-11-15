<?php get_header(); ?>

		<div class="col1">

			<?php 
       			 if (get_option('woo_show_carousel') == 'true') { include (TEMPLATEPATH . "/includes/featured.php");}
        	?>
            
            <?php 
       			if (get_option('woo_home') == "true") 
				include (TEMPLATEPATH . '/layouts/blog.php'); 
				else			 
				include (TEMPLATEPATH . '/layouts/default.php'); 			
			?>
			
			<?php 
       			if (get_option('woo_show_video') == 'true') { include (TEMPLATEPATH . "/includes/video.php");}
        	?>

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>