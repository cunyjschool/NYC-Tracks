<?php

// Register widgetized areas

function the_widgets_init() {
    if ( !function_exists('register_sidebars') )
        return;

    register_sidebars(1,array('name' => 'Sidebar Full Width','before_widget' => '<div id="%1$s" class="block widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
    register_sidebars(2,array('name' => 'Sidebar Sub Column %d','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="widget_title">','after_title' => '</h3>'));

}

add_action( 'init', 'the_widgets_init' );


    
?>