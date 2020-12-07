<?php

/**
 * Plugin Name:       WPB Accordion Menu or Category
 * Plugin URI:        https://wpbean.com/downloads/wpb-accordion-menu-category-pro/
 * Description:       WPB Accordion Menu or Category Plugin allow you to show WordPress menu or any category accordion with submenu / subcategory support. Specially optimized for WooCommerce or any other ecommerce categories. It's responsive and modern flat design.
 * Version:           1.3.8
 * Author:            wpbean
 * Author URI:        https://wpbean.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpb-accordion-menu-or-category
 * Domain Path:       /languages
 *
 * WC requires at least: 3.5
 * WC tested up to: 4.2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Define constants
 */

if ( ! defined( 'WPB_WAMC_FREE_INIT' ) ) {
    define( 'WPB_WAMC_FREE_INIT', plugin_basename( __FILE__ ) );
}

/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_WAMC_PREMIUM' ) ) {
    function wpb_wmca_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Accordion Menu or Category while you are using the premium one.', 'wpb-accordion-menu-or-category' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'wpb_wmca_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}




/**
 * Add plugin action links
 */

function wpb_wmca_plugin_actions( $links ) {

	$links[] = '<a href="https://wordpress.org/plugins/wpb-accordion-menu-or-category/#installation" target="_blank">'. esc_html__('Documentation', 'wpb-accordion-menu-or-category') .'</a>';
	$links[] = '<a href="https://wpbean.com/support/" target="_blank">'. esc_html__('Support', 'wpb-accordion-menu-or-category') .'</a>';

	$links[] = '<a href="https://wpbean.com/downloads/wpb-accordion-menu-category-pro/" target="_blank" style="color: #39b54a; font-weight: 700;">'. esc_html__('Go Pro', 'wpb-accordion-menu-or-category') .'</a>';
	
	return $links;
}

/**
 * Plugin Init
 */

function wpb_wmca_free_plugin_init(){
	load_plugin_textdomain( 'wpb-accordion-menu-or-category', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wmca_plugin_actions' );

	register_deactivation_hook( plugin_basename( __FILE__ ), 'wpb_wmca_lite_plugin_deactivation' );
	add_action( 'admin_notices', 'wpb_wmca_pro_discount_admin_notice' );
	add_action( 'admin_init', 'wpb_wmca_pro_discount_admin_notice_dismissed' );
	add_action( 'wp_dashboard_setup', 'wpb_wmca_add_dashboard_widgets' );

	require_once dirname( __FILE__ ) . '/inc/wpb-scripts.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wmca-functions.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wmca-shortcodes.php';

	if ( did_action( 'elementor/loaded' ) ) {
		require_once dirname( __FILE__ ) . '/elementor/wpb-wmca-elementor.php';
	}
}
add_action( 'plugins_loaded', 'wpb_wmca_free_plugin_init' );