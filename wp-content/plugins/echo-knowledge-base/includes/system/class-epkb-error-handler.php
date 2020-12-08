<?php
/**
 * Notices for js errors 
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_Error_Handler {

	public function __construct() {

		// add script to the page
		add_action( 'admin_enqueue_scripts', [ $this, 'add_assets' ] );
	    add_action( 'wp_enqueue_scripts', [ $this, 'add_assets' ] );

	    // add message to the page
		add_action( 'admin_footer', [ $this, 'add_template' ] );
	    add_action( 'wp_footer', [ $this, 'add_template' ] );
   }
	
	public function add_assets() { ?>
		<link rel="stylesheet" id="epkb-js-error-handlers-css" href="<?php echo Echo_Knowledge_Base::$plugin_url . 'css/error-handlers.css'; ?>" media="all">
		<script src="<?php echo Echo_Knowledge_Base::$plugin_url . 'js/error-handlers.js'; ?>" type="text/javascript"></script><?php
	}
	
	public function add_template() {
		echo '
			<div style="display:none;" class="epkb-js-error-notice">
				<div class="epkb-js-error-close">&times;</div>
				<div class="epkb-js-error-title">' . __( 'We found JS Error on this page caused by a plugin:', 'epkb-knowledge-base' ) . '</div>
				<div class="epkb-js-error-body">
					<div class="epkb-js-error-msg"></div>' .
					__( 'in', 'epkb-knowledge-base' ) . '<span class="epkb-js-error-url"></span>' . __( 'file', 'epkb-knowledge-base' ) . '
				</div>
				<div class="epkb-js-error-about">' . __( 'Check console for more information (F12)', 'epkb-knowledge-base' ) . '</div>
			</div>';
	}
}
