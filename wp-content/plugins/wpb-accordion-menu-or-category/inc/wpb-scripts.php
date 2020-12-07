<?php

/*
	WPB Menu And Category Accordion
	By WPBean
	
*/

defined( 'ABSPATH' ) || exit;


/**
 * Plugin Scripts
 */

function wpb_wmca_adding_scripts() {
	$cookie = apply_filters( 'wpb_wmca_jquery_cookie', true );
	if( $cookie == true ){
		wp_enqueue_script('wpb_wmca_jquery_cookie', plugins_url('../assets/js/jquery.cookie.js', __FILE__) , array('jquery'), '1.0', false);
	}
	wp_enqueue_script('wpb_wmca_accordion_script', plugins_url('../assets/js/jquery.navgoco.min.js', __FILE__) , array('jquery'), '1.0', false);
	wp_enqueue_script('wpb_wmca_accordion_init', plugins_url('../assets/js/accordion-init.js', __FILE__), array( 'jquery' ), '1.0', true);
	wp_enqueue_style( 'wpb_wmca_accordion_style', plugins_url('../assets/css/wpb_wmca_style.css', __FILE__), '', '1.0' );
}
add_action( 'wp_enqueue_scripts', 'wpb_wmca_adding_scripts' );