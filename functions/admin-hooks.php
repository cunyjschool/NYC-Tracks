<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Hook Definitions

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Hook Definitions */
/*-----------------------------------------------------------------------------------*/

// header.php
function woo_head() { do_action( 'woo_head' ); }					
function woo_top() { do_action( 'woo_top' ); }					
/* Not yet implemented
function woo_header_above() { do_action( 'woo_header_above' ); }			
function woo_header_inside() { do_action( 'woo_header_inside' ); }				
function woo_header_below() { do_action( 'woo_header_below' ); }			
function woo_nav_above() { do_action( 'woo_nav_above' ); }					
function woo_nav_inside() { do_action( 'woo_nav_inside' ); }					
function woo_nav_below() { do_action( 'woo_nav_below' ); }			
*/

// footer.php
/* Not yet implemented
function woo_footer_top() { do_action( 'woo_footer_top' ); }					
function woo_footer_above() { do_action( 'woo_footer_above' ); }					
function woo_footer_inside() { do_action( 'woo_footer_inside' ); }					
function woo_footer_below() { do_action( 'woo_footer_below' ); }	
*/
function woo_foot() { do_action( 'woo_foot' ); }					

?>