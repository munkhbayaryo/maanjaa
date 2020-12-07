<?php
/* ================================================== */
/*    |               Include                    |    */
/* ================================================== */
require_once  get_parent_theme_file_path().'/includes/enqueue.php';
require_once  get_parent_theme_file_path().'/includes/maanjaa-class-wp-bootstrap-navwalker.php';







/* ================================================== */
/*    |         Register Nav Menus               |    */
/* ================================================== */
add_action( 'after_setup_theme', 'maanjaa_theme_menu_setup' );
if ( ! function_exists( 'maanjaa_theme_menu_setup' ) ):
function maanjaa_theme_menu_setup() {  
    register_nav_menu('primary-menu', esc_attr( 'Primary Menu', 'maanjaa' ));
    register_nav_menu('footer-menu', esc_attr( 'Footer Menu', 'maanjaa' ));
} endif;





/* ================================================== */
/*    |           Register Sidebar               |    */
/* ================================================== */
function maanjaa_widgets_init() {
  register_sidebar( array(
    'name'          => esc_attr('Right Sidebar', 'maanjaa'),
    'id'            => 'primary-sidebar',
    'description'   => esc_attr('Main Right Sidebar', 'maanjaa'),
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-header">',
    'after_title'   => '</h3>',
  ) );
  register_sidebar( array(
    'name'          => esc_attr('Canvas Sidebar', 'maanjaa'),
    'id'            => 'canvas-sidebar',
    'description'   => esc_attr('Canvas Filter Sidebar', 'maanjaa'),
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-header">',
    'after_title'   => '</h3>',
  ) );
}
add_action( 'widgets_init', 'maanjaa_widgets_init' );




/* ================================================== */
/*    |       Styling Default Search Form        |    */
/* ================================================== */
function maanjaa_theme_search_form( $form ) { 
  $form = '<form class="searchform" role="search" method="get" id="search-form" action="' . esc_url(home_url( '/' )) . '" >
 <label class="screen-reader-text" for="s"></label>
  <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search ..." />
  <input type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'maanjaa') .'" />
  </form>';
  return $form;
}

add_filter( 'get_search_form', 'maanjaa_theme_search_form' );





/* ================================================== */
/*    |               Menu Walkers               |    */
/* ================================================== */
class maanjaa_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"submenu\">\n";
  }
}




add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}       


/* ================================================== */
/*    |         Theme Features                   |    */
/* ================================================== */
if ( ! function_exists('maanjaa_theme_features') ) {
// Register Theme Features
function maanjaa_theme_features()  {

  // Add theme support for Post Thumbnails
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 300, 300, true );
  // Add theme support for Automatic Feed Links
  add_theme_support( 'automatic-feed-links' );
  // Add theme support for Title Tag
  add_theme_support( "title-tag" );
  // Add theme support for WooCommerce

  add_theme_support( 'wc-product-gallery-zoom' );
  add_theme_support( 'wc-product-gallery-lightbox' );
  add_theme_support( 'wc-product-gallery-slider' );
  /* Post Thumbnail Sizes */
  add_image_size( 'maanjaa-thumb', 260 );
  add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
  );
  // Add theme support for Gutenberg
  add_theme_support( 'wp-block-styles' );
  // Add support for editor styles.
  add_theme_support( 'editor-styles' );
  // Add support for editor styles.
  add_theme_support( 'editor-styles' );
  
  // Enqueue for Custom Editor Styles
  add_editor_style( 'css/editor-style.css' );
}
add_action( 'after_setup_theme', 'maanjaa_theme_features' );

}








// Set content width value based on the theme's design
if ( ! isset( $content_width ) )
  $content_width = 1140;







/* ================================================== */
/*    |             Post Paginations             |    */
/* ================================================== */
function maanjaa_theme_pagination() {
  global $wp_query;
  $big = 12345678;
  $page_format = paginate_links( array(
      'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format' => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total' => $wp_query->max_num_pages,
      'type'  => 'array',
      'prev_next' => false,
  ) );
  if( is_array($page_format) ) {
    $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
    echo '<nav class="pagination-outer"><ul class="list-inline pagination unstyled">';
    foreach ( $page_format as $page ) {
      echo "<li class='page-item list-inline-item'>$page</li>";
    }
      echo '</ul></nav>';
  }
}






/**
 * Proper ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
   while ( @ob_end_flush() );
} );

// add_filter( 'woocommerce_enqueue_styles', '__return_false' );






/**
 * Add a custom product data tab
 */
// add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
// function woo_new_product_tab( $tabs ) {
	
// 	// Adds the new tab
	
// 	$tabs['test_tab'] = array(
// 		'title' 	=> __( 'New Product Tab', 'woocommerce' ),
// 		'priority' 	=> 50,
// 		'callback' 	=> 'woo_new_product_tab_content'
// 	);

// 	return $tabs;

// }
// function woo_new_product_tab_content() {

// 	// The new tab content

// 	echo '<h2>New Product Tab</h2>';
// 	echo '<p>Here\'s your new product tab.</p>';
	
// }








/**
 * Add Cart icon and count to header if WC is active
 */
function maanjaa_theme_wc_cart_icon() {
 
  if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

      $cart_link = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();

      $count = WC()->cart->cart_contents_count;
      ?><a class="cart-contents" href="<?php echo esc_url($cart_link); ?>" title="<?php _e( 'View your shopping cart', 'maanjaa' ); ?>"><?php
          ?>
          <button class="d-inline ml-3 cart-button align-middle">
          <?php if ( $count > 0 ) { ?>
            <span class="count"><?php echo esc_html( $count ); ?></span>
          <?php } ?>
            <i class="ti-shopping-cart"></i>
          </button>
          <?php
              ?></a><?php
  }

}
add_action( 'maanjaa_theme_cart_icon', 'maanjaa_theme_wc_cart_icon' );


/**
* Ensure cart contents update when products are added to the cart via AJAX
*/
function maanjaa_header_add_to_cart_fragment( $fragments ) {

  ob_start();
  $count = WC()->cart->cart_contents_count;
  $cart_link = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
  ?><a class="cart-contents" href="<?php echo esc_url($cart_link); ?>" title="<?php _e( 'View your shopping cart', 'maanjaa' ); ?>"><?php
      ?>
      <button class="d-inline ml-3 cart-button align-middle">
      <?php if ( $count > 0 ) { ?>
        <span class="count"><?php echo esc_html( $count ); ?></span>
      <?php } ?>
        <i class="ti-shopping-cart"></i>
      </button>
      <?php            
      ?></a><?php

  $fragments['a.cart-contents'] = ob_get_clean();
   
  return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'maanjaa_header_add_to_cart_fragment' );










add_action( 'express_shop_title', 'maanjaa_show_express_shop_term' );

function maanjaa_show_express_shop_term() {
	global $post;
	$attribute_names = array( 'pa_patype' );

	foreach ( $attribute_names as $attribute_name ) {
		$taxonomy = get_taxonomy( $attribute_name );

		if ( $taxonomy && ! is_wp_error( $taxonomy ) ) {
			$terms = wp_get_post_terms( $post->ID, $attribute_name );
			$terms_array = array();

	        if ( ! empty( $terms ) ) {
		        foreach ( $terms as $term ) {
              echo '<span class="express-shop-badge">' . $term->name . '</span>';
		        }
	        }
    	}
    }
}