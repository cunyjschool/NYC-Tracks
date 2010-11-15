<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- woothemes_more_themes_setup
- woothemes_more_themes_page
- woothemes_more_themes_head

-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* woothemes_more_themes_setup */
/*-----------------------------------------------------------------------------------*/

function woothemes_more_themes_setup() {

    add_menu_page("WooThemes", "WooThemes", 'edit_themes', 'more-woothemes', 'woothemes_more_themes_page', 'http://www.woothemes.com/favicon.ico');

}



/*-----------------------------------------------------------------------------------*/
/* woothemes_more_themes_page */
/*-----------------------------------------------------------------------------------*/

function woothemes_more_themes_page(){
        ?>
        <div class="wrap themes-page">
          <h2>More WooThemes</h2>
          <div class="info">
          <a href="http://www.woothemes.com/the-woothemes-club/">Join the WooThemes Club</a>
          <a href="http://www.woothemes.com/themes">Online Themes Gallery</a>
          <a href="http://showcase.woothemes.com/">Theme Showcase</a>
          </div>
          
          
            <?php // Get RSS Feed(s)
            include_once(ABSPATH . WPINC . '/feed.php');
            $rss = fetch_feed('http://www.woothemes.com/?feed=more_themes');
            $maxitems = $rss->get_item_quantity(30); 
            $items = $rss->get_items(0, 30);
			
            ?>
            <ul class="themes">
            <?php if (empty($items)) echo '<li>No items</li>';
            else
            foreach ( $items as $item ) : ?>
                <li class="theme">
                    <?php echo $item->get_description();?>
                </li>
            <?php 
			endforeach; ?>
            </ul>
            
            </div>
         
         <?php

};



/*-----------------------------------------------------------------------------------*/
/* woothemes_more_themes_head */
/*-----------------------------------------------------------------------------------*/

function woothemes_more_themes_head() { 
         $style = $_REQUEST[style];
     if ($style != '') {
          ?> <link href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $style; ?>.css" rel="stylesheet" type="text/css" /><?php 
     } else { 
          $stylesheet = get_option('woo_alt_stylesheet');
          if($stylesheet != ''){
               ?><link href="<?php bloginfo('template_directory'); ?>/styles/<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" /><?php         
          }
     }     
}


?>