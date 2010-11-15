<div class="box">

	<?php
     if (is_paged()) $is_paged = true;
        
     $featposts = get_option('woo_show_carousel'); // Number of featured entries to be shown
     $ex_feat = "-" . get_cat_id(get_option('woo_featured_category'));
     
     $showvideo = get_option('woo_show_video');
     $ex_vid = "-" . get_cat_id(get_option('woo_video_category'));
     
     if($featposts == "true"){ $exclude[] = $ex_feat;}
     if($showvideo == "true"){ $exclude[] = $ex_vid; }
     if(!empty($exclude)){
        $ex = implode(',',$exclude);
     }
     
	 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("cat=$ex&paged=$paged");
     
	 if (have_posts()) : $counter = 0; 
	 while (have_posts()) : the_post(); $counter++;
     ?>

		<div class="post-alt blog">

			<?php if ( get_option('woo_resize') ) { if ( get_post_meta($post->ID, 'image', true) ) { ?> <!-- DISPLAYS THE IMAGE URL SPECIFIED IN THE CUSTOM FIELD -->
						
						<a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=57&amp;w=100&amp;zc=1&amp;q=95" alt="<?php the_title(); ?>" class="th" /></a>			
				
			<?php } } ?> 		
			
			<h2><a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<p class="post_date"><?php _e('Posted on',woothemes); ?> <?php the_time('d F Y'); ?>. <span class="singletags"><?php if (function_exists('the_tags')) { ?><?php the_tags('Tags: ', ', ', ''); ?><?php } ?></span></p>
            

			<div class="entry">
				<?php the_content(__('<span class="continue">Continue Reading</span>',woothemes)); ?>
			</div>

			 <p class="posted"><?php _e('Posted in',woothemes); ?> <?php the_category(', ') ?><span class="comments"><?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?></span></p>
		
		</div><!--/post-->		

	<?php endwhile; ?>	
    <?php endif; ?>	
	
	<div class="fix"></div>
	
    <div class="more_entries">
        <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
            <div class="fl"><?php previous_posts_link(__('&laquo; Newer Entries ',woothemes)) ?></div>
            <div class="fr"><?php next_posts_link(__(' Older Entries &raquo;',woothemes)) ?></div>
            <br class="fix" />
        <?php } ?> 
    </div>		
    
    <div class="fix" style="height:15px"></div>

</div>