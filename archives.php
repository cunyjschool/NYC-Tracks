<?php
/*
Template Name: Archives Page
*/
?>

<?php get_header(); ?>

		<div class="col1">
		
			<div id="archivebox">
				
					<h2><?php the_title(); ?></h2>        
			
			</div><!--/archivebox-->
			
			<div class="arclist fl">
			
				<h2><?php _e('Categories',woothemes); ?></h2>
	
				<ul>
					<?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>	
				</ul>				
			
			</div><!--/arclist-->
			
			<div class="arclist fr">
			
				<h2><?php _e('Monthly Archives',woothemes); ?></h2>
	
				<ul>
					<?php wp_get_archives('type=monthly&show_post_count=1') ?>	
				</ul>				
			
			</div><!--/arclist-->
			
			<div class="fix"></div>
			
			<?php if (function_exists('wp_tag_cloud')) { ?>
			
            <div id="archivebox">
                
                    <h2><?php _e('Popular Tags',woothemes); ?></h2>					        
            
            </div><!--/archivebox-->
            
            <ul class="list1">
                <?php wp_tag_cloud('smallest=10&largest=18'); ?>
            </ul>	
			
			<?php } ?>				
            
            <br />
            <div id="archivebox">
        
                <h2><?php _e('The Last 30 Posts',woothemes); ?></h2>

            </div><!--/archivebox-->
            
            <div class="arclist" style="width:auto;">
            <ul>
                <?php query_posts('showposts=30'); ?>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php $wp_query->is_home = false; ?>
                    <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php the_time('j F Y') ?> - <?php echo $post->comment_count ?> comments</li>
                
                <?php endwhile; endif; ?>	
            </ul>	
            </div>			
            											

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>