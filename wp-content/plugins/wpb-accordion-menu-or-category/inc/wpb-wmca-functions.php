<?php

/*
	WPB Menu & Category Accordion
	By WPBean
	
*/

defined( 'ABSPATH' ) || exit;



/* ==========================================================================
   Text Widget Shortcode Support
   ========================================================================== */

add_filter('widget_text', 'do_shortcode');



/* ==========================================================================
   WPB Category Walker
   ========================================================================== */


class WPB_WCMA_Category_Walker extends Walker_Category {

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

		$wpb_wmca_cat_count = '';

		if ( ! empty( $args['show_count'] ) ) {
			$wpb_wmca_cat_count = '<span class="wpb-wmca-cat-count">' . number_format_i18n( $category->count ) . '</span>';
		}

		$link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= $cat_name . $wpb_wmca_cat_count. '</a>';



		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);

			$termchildren = get_term_children( $category->term_id, $category->taxonomy );

            if( count($termchildren)>0 ){
                $css_classes[] =  'cat-item-have-child';
            }

			if ( ! empty( $args['current_category'] ) ) {
				$_current_category = get_term( $args['current_category'], $category->taxonomy );
				if ( $category->term_id == $args['current_category'] ) {
					$css_classes[] = 'current-cat';
				} elseif ( $category->term_id == $_current_category->parent ) {
					$css_classes[] = 'wpb-wmca-current-cat-parent';
				}
			}

			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

			$output .=  ' class="' . $css_classes . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}


/**
 * Pro version discount
 */


function wpb_wmca_pro_discount_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpb_wmca_pro_discount_dismissed' ) ){
        printf('<div class="wpb-fp-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'wpb-accordion-menu-or-category' ), 'https://wpbean.com/downloads/wpb-accordion-menu-category-pro/', esc_html__( 'WPB Accordion Menu or Category', 'wpb-accordion-menu-or-category' ), esc_html__( 'Use discount code - ', 'wpb-accordion-menu-or-category' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-wmca-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', 'wpb-accordion-menu-or-category' ));
    }
}


function wpb_wmca_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-wmca-pro-discount-admin-notice-dismissed'] ) ){
        add_user_meta( $user_id, 'wpb_wmca_pro_discount_dismissed', 'true', true );
    }
}

/**
 * Plugin Deactivation
 */

function wpb_wmca_lite_plugin_deactivation() {
  $user_id = get_current_user_id();
  if ( get_user_meta( $user_id, 'wpb_wmca_pro_discount_dismissed' ) ){
  	delete_user_meta( $user_id, 'wpb_wmca_pro_discount_dismissed' );
  }
}



/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function wpb_wmca_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'wpb_wmca_pro_features', esc_html__( 'WPB Accordion Menu or Category Pro', 'wpb-accordion-menu-or-category' ), 'wpb_wmca_pro_features_dashboard_widget_render' ); 
}

function wpb_wmca_pro_features_dashboard_widget_render() {
    ?>
    <ul class="wpb-wmca-dash-widget-feature">
		<li><span class="dashicons dashicons-yes-alt"></span>Showing any custom taxonomy or menu in the Accordion.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Custom widgets for menu and category Accordion.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Five different predefined skins for accordion.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Color customization option in settings.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>WooCommerce product categories and tags support.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Showing posts/custom post types in category accordion as child item.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Auto open first level parent category or menu.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Feature for keep open selected menu items accordion.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Feature for keep open current menu or category accordion.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Custom icon picker both for menu &amp; categories.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>PNG icons upload feature for categories.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>FontAwesome and Themify icons included.</li>
	</ul>
	<div class="wpb-wmca-dash-widget-upgrade-btns">
		<a class="wpb-wmca-dash-widget-btn wpb-wmca-dash-widget-upgrade-btn" href="https://wpbean.com/downloads/wpb-accordion-menu-category-pro/" target="_blank">Upgrade to Pro</a>
		<a class="wpb-wmca-dash-widget-btn wpb-wmca-dash-widget-demo-btn" href="http://demo2.wpbean.com/?page_id=103" target="_blank">Live Demo</a>
	</div>
	<style>
		.wpb-wmca-dash-widget-btn {
			border-radius: 5px;
			margin-right: 7px;
			color: #fff;
			display: inline-block;
			margin-top: 10px;
			margin-bottom: 15px;
			padding: 15px 28px 17px;
			text-decoration: none;
			font-weight: 700;
			line-height: normal;
			font-size: 15px;
			-webkit-font-smoothing: antialiased;
			-webkit-transition: all .3s linear;
			-moz-transition: all .3s linear;
			-ms-transition: all .3s linear;
			-o-transition: all .3s linear;
			transition: all .3s linear;
		}
		.wpb-wmca-dash-widget-upgrade-btn{
			background: #f2295b;
		}
		.wpb-wmca-dash-widget-upgrade-btn:hover, .wpb-wmca-dash-widget-upgrade-btn:focus { 
			background: #c71843;
		}
		.wpb-wmca-dash-widget-demo-btn{
			background: #007cf5;
		}
		.wpb-wmca-dash-widget-demo-btn:hover, .wpb-wmca-dash-widget-demo-btn:focus { 
			background: #126dca;
		}
		.wpb-wmca-dash-widget-btn:hover, .wpb-wmca-dash-widget-btn:focus {
			color: #fff;
			-webkit-box-shadow: 0 7px 12px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
			-moz-box-shadow: 0 7px 12px rgba(50,50,93,.1),0 3px 6px rgba(0,0,0,.08);
			box-shadow: 0 7px 12px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
		}
		.wpb-wmca-dash-widget-feature li {
		    margin-bottom: 15px;
		}
		.wpb-wmca-dash-widget-feature .dashicons {
			color: #f2295b;
			margin-right: 10px;
		}
		.rtl .wpb-wmca-dash-widget-feature .dashicons {
			margin-right: 0;
			margin-left: 10px;
		}
		.rtl .wpb-wmca-dash-widget-btn {
			margin-right: 0;
			margin-left: 7px;
		}
	</style>
    <?php
}