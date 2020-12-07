<?php

/* Register Styles */
function maanjaa_theme_styles()
{
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', 'style');

	wp_enqueue_style('font-awesome-5', get_template_directory_uri() . '/css/all.min.css', 'style');

	wp_enqueue_style('slick', get_template_directory_uri() . '/css/slick.css', 'style');

	wp_enqueue_style('listnav', get_template_directory_uri() . '/css/listnav.css', 'style');

	wp_enqueue_style('themify-icons', get_template_directory_uri() . '/css/themify-icons.css', 'style');

	wp_enqueue_style('maanjaa-default-style', get_template_directory_uri() . '/css/style.css', 'style');

	wp_enqueue_style('maanjaa-style', get_template_directory_uri() . '/style.css', 'style');
	
}
add_action( 'wp_enqueue_scripts', 'maanjaa_theme_styles', 999 );




/* Register Scripts */
function maanjaa_theme_scripts()
{

	wp_enqueue_script( 'popper', get_template_directory_uri() . '/js/popper.min.js', array('jquery'),'',true );

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'),'',true );

	wp_enqueue_script( 'slick-slider', get_template_directory_uri() . '/js/slick.min.js', array('jquery'),'',true );

	wp_enqueue_script( 'listnav', get_template_directory_uri() . '/js/jquery-listnav.min.js', array('jquery'),'',true );

	wp_enqueue_script('maanjaa-custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '', true);

}
add_action( 'wp_enqueue_scripts', 'maanjaa_theme_scripts' );