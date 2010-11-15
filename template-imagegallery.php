<?php
/*
Template Name: Image Gallery
*/
?>

<?php get_header(); ?>

		<div class="col1">
		
			<div id="archivebox">
				
					<h2><?php the_title(); ?></h2>        
			
			</div><!--/archivebox-->
			
			<div class="imagegallery">
			
						<?php query_posts('showposts=30'); ?>
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>				
                            <?php $wp_query->is_home = false; ?>
    
                            <?php woo_get_image('image','80','80','','thumbnail gallery'); ?>
                        
                        <?php endwhile; endif; ?>	
			
			</div><!--/imagegallery-->															

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>