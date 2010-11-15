<?php get_header(); ?>

		<div class="col1">

		<?php if (have_posts()) : ?>
		
		<div id="archivebox">
        	
            	<h2><em>Tag Archive |</em> "<?php single_tag_title("", true); ?>"</h2>        
		
		</div><!--/archivebox-->	

			<?php while (have_posts()) : the_post(); ?>		

				<div class="post-alt blog" id="post-<?php the_ID(); ?>">
		
					<?php if ( !get_option('woo_resize') ) { if ( get_post_meta($post->ID, 'image', true) ) { ?> <!-- DISPLAYS THE IMAGE URL SPECIFIED IN THE CUSTOM FIELD -->
						
						<img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=57&amp;w=100&amp;zc=1&amp;q=80" alt="<?php the_title(); ?>" class="th" />			
						
					<?php } } ?> 		
					
					<h2><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<p class="post_date">Posted on <?php the_time('d F Y'); ?>. <span class="singletags"><?php if (function_exists('the_tags')) { ?><?php the_tags('Tags: ', ', ', ''); ?><?php } ?></span></p>
                    <hr style="clear:both;" />
		
					<div class="entry">
						<?php the_content('<span class="continue">Read the full story</span>'); ?> 
					</div>
		
					 <p class="posted">Posted in <?php the_category(', ') ?><span class="comments"><?php comments_popup_link('Comments (0)', 'Comments (1)', 'Comments (%)'); ?></span></p>
				
				</div><!--/post-->

		<?php endwhile; ?>
		
        <div class="more_entries">
            <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
            <div class="alignleft"><?php previous_posts_link('&laquo; Newer Entries ') ?></div>
            <div class="alignright"><?php next_posts_link(' Older Entries &raquo;') ?></div>
            <br class="fix" />
            <?php } ?> 
        </div>		
	
	<?php endif; ?>							

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>