<?php
    $showvideo = get_option('woo_show_video');
	$ex_vid = get_cat_id(get_option('woo_video_category'));
	
	if ($showvideo == 'true') { ?>
    
     <?php $saved = $wp_query; ?>

	<div id="video-frame">
	
	<div id="myTabs2">
	
		<div class="video-right">

		<?php query_posts('showposts=1&cat=' . $ex_vid); ?>
	
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>	
		
				<h2><?php _e('Currently Playing...',woothemes); ?></h2>
				<h3><?php the_title(); ?></h3>
                <p><?php _e('Added on',woothemes); ?> <?php the_time('d F Y'); ?></p>
		
			<?php endwhile; ?>
		
		<?php endif; ?>
        
		<h2><?php _e('View More Videos',woothemes); ?></h2>
		
		<?php query_posts('showposts=5&offset=1&cat=' . $ex_vid); ?>
	
		<?php if (have_posts()) : ?>
		
			<ul class="mootabs_title">
	
			<?php while (have_posts()) : the_post(); ?>	
		
				<li><a title="<?php _e('Permalink to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
			
			<?php endwhile; ?>
			
			</ul>	
		
		<?php endif; ?>
	
		</div><!--/video-right -->
	
	<?php query_posts('showposts=1&cat=' . $ex_vid); ?>
	
	<?php if (have_posts()) : ?>
	
		<div class="video-left">

		<?php while (have_posts()) : the_post(); ?>	
	
			<div id="video-<?php the_ID(); ?>">
				<?php echo woo_get_embed('embed',285,234)?>
			</div>
		
		<?php endwhile; ?>
		
		</div><!--/video-left -->
	
	<?php endif; ?>
	
	</div>
	
	</div><!--/video-frame -->
    
            <?php $wp_query = $saved; ?>

<?php } ?>