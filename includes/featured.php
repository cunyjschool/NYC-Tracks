<div id="wooslider" class="wooslider">
<div class="slider-container">

	<?php 
		$featposts = get_option('woo_feat_entries'); // Number of featured entries to be shown
	    $ex_feat = get_cat_id(get_option('woo_featured_category'));

		$the_query = new WP_Query('cat=' . $ex_feat . '&showposts=' . $featposts . '&orderby=post_date&order=desc');

	?>
	<div class="slider-shelf"></div>

<span class="clicker"><?php _e('Featured Stories',woothemes); ?></span>

	<div class="slider-shelf-content">
	<?php
	    $counter = 1;
	    while ($the_query->have_posts()) : $the_query->the_post(); $do_not_duplicate = $post->ID;
	?>
	<?php woo_get_image('image','70', '70','thumbnail" title="'. $counter,90,get_the_id(),'img'); ?>
	<?php $counter++; ?>
	<?php endwhile; ?>
	<div class="shelf-title">&nbsp;</div>

	</div>


<?php 
    $counter = 1;
    $slider_height = 270;
    $slider_height = get_option("woo_carousel_height");
    
	while ($the_query->have_posts()) : $the_query->the_post(); $do_not_duplicate = $post->ID;
    
?>

    <div class="slide slide-<?php echo $counter; ?>">
        

        
        <a href="<?php the_permalink() ?>" title="<?php _e('Read the full story',woothemes); ?>" class="open"><?php woo_get_image('image','595', $slider_height,'slider-img',90,get_the_id(),'img'); ?></a>   
        <div class="slide-content slide-content-<?php echo $counter; ?>">
            <div class="slide-content-inner-<?php echo $counter; ?>">
                <h3><?php the_title(); ?></h3>
                <p><?php echo strip_tags(get_the_excerpt(), '<a><strong>'); ?></p>
            </div>
        </div>
    </div>
    
<?php $counter++; ?>
<?php endwhile; ?>
</div>

	<div class="slider-nav">
	<span class="slider-left"></span>
	<span class="slider-right"></span>
	</div>

</div>
