<?php get_header(); ?>

		<div class="col1">

		<?php if (have_posts()) : ?>
		
		<div id="archivebox">
		
        		<?php if (is_category()) { ?>
        	
            	<h3><em><?php _e('Archive',woothemes); ?> |</em> <?php echo single_cat_title(); ?></h3>        
            	
            	<div class="archivefeed"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">RSS feed for this section</a>'; ?></div>
            	
				<?php } elseif (is_day()) { ?>
				<h3><?php _e('Archive',woothemes); ?> | <?php the_time('F jS, Y'); ?></h3>

				<?php } elseif (is_month()) { ?>
				<h3><?php _e('Archive',woothemes); ?> | <?php the_time('F, Y'); ?></h3>

				<?php } elseif (is_year()) { ?>
				<h3><?php _e('Archive',woothemes); ?> | <?php the_time('Y'); ?></h3>
				
				<?php } ?>
		
		</div><!--/archivebox-->
	
			<?php while (have_posts()) : the_post(); ?>		

				<div class="post-alt blog" id="post-<?php the_ID(); ?>">
		
					<?php if ( get_option('woo_resize') == "true" ) { if ( get_post_meta($post->ID, 'image', true) ) { ?> <!-- DISPLAYS THE IMAGE URL SPECIFIED IN THE CUSTOM FIELD -->
						
						<img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=57&amp;w=100&amp;zc=1&amp;q=80" alt="<?php the_title(); ?>" class="th" />			
						
					<?php } } ?> 		
					
					<h2><a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<p class="post_date"><?php _e('Posted on',woothemes); ?> <?php the_time('d F Y'); ?>. <span class="singletags"><?php if (function_exists('the_tags')) { ?><?php the_tags('Tags: ', ', ', ''); ?><?php } ?></span></p>
		
					<div class="entry">
						<?php the_content(__('<span class="continue">Continue Reading</span>',woothemes)); ?> 
					</div>
		
					 <p class="posted">Posted in <?php the_category(', ') ?><span class="comments"><?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?></span></p>
				
				</div><!--/post-->

		<?php endwhile; ?>
		
        <div class="more_entries">
            <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
            <div class="fl"><?php previous_posts_link(__('&laquo; Newer Entries ',woothemes)) ?></div>
            <div class="fr"><?php next_posts_link(__(' Older Entries &raquo;',woothemes)) ?></div>
            <br class="fix" />
            <?php } ?> 
        </div>		
	
	<?php endif; ?>							

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>