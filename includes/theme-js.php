<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
function woothemes_add_javascript( ) {
wp_enqueue_script('jquery');
wp_enqueue_script('scripts', get_bloginfo('template_directory').'/includes/js/scripts.js', array( 'jquery' ) );
wp_enqueue_script('woo_tabs', get_bloginfo('template_directory').'/includes/js/woo_tabs.js', array( 'jquery' ) );
    if(is_home()){
        wp_enqueue_script('wooslider', get_bloginfo('template_directory').'/includes/js/wooslider.js', array( 'jquery' ) );
    }
wp_enqueue_script('superfish', get_bloginfo('template_directory').'/includes/js/superfish.js', array( 'jquery' ) );
}
?>