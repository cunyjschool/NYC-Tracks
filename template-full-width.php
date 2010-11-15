<?php
/*
Template Name: Full width
*/
?>

<?php get_header(); ?>

		<div class="col1 full">

		<?php if (have_posts()) : ?>
		
		<div id="archivebox">
        	
            	<h2><?php the_title(); ?></h2>        
		
		</div><!--/archivebox-->
	
			<?php while (have_posts()) : the_post(); ?>		

				<div class="post-alt blog" id="post-<?php the_ID(); ?>">
		
					<div class="entry">
						<?php the_content(__('<span class="continue">Continue Reading</span>',woothemes)); ?>
					</div>
				
				</div><!--/post-->

			<?php endwhile; ?>
	
		<?php endif; ?>							

		</div><!--/col1-->

<?php get_footer(); ?>