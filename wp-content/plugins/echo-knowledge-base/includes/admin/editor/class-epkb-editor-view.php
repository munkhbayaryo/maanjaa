<?php

/**
 * Output panels for the front-end editor for KB page configuration
 */
class EPKB_Editor_View {

	// Add hidden wp editor to the footer to use it in editor popup and loader html
	public static function get_editor_html() {       ?>

		<div class="epkb-editor-popup" style="display: none;">
			<div class="epkb-editor-popup__header"></div>
			<div class="epkb-editor-popup__body"><?php

				// no _ and - in id, only letters in lowercase to prevent wp conflicts
				wp_editor( '', 'epkbeditormce', $settings = array(
					'wpautop' => false,
					'media_buttons' => true,
				) ); ?>

			</div>
			<div class="epkb-editor-popup__footer">
				<button id="epkb-editor-popup__button-update"><?php _e( 'Update', 'echo-knowledge-base' ); ?>
					<button id="epkb-editor-popup__button-cancel"><?php _e( 'Cancel', 'echo-knowledge-base' ); ?>
			</div>

		</div>

		<div class="epkb-frontend-loader" style="display: none;">
			<div class="epkb-frontend-loader-icon epkbfa epkbfa-hourglass-half"></div>
		</div>		<?php
	}

	public static function get_editor_settings_html() {
		ob_start(); ?>

		<div class="epkb-editor-settings-panel-container" id="epkb-editor-settings-templates">
			<div class="epkb-editor-settings-accordeon-item__title"><?php _e( 'Choose Theme for KB', 'echo-knowledge-base' ); ?></div>
			<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-image-select">
				<label class="epkb-editor-settings-control-image-select" data-name="templates_for_kb">
					<input type="radio" name="templates_for_kb" value="current_theme_templates">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/Current-theme-option.jpg'; ?>">
						<span><?php _e( 'Current Theme', 'echo-knowledge-base' ); ?></span>
					</div>
				</label>

				<label class="epkb-editor-settings-control-image-select" data-name="templates_for_kb">
					<input type="radio" name="templates_for_kb" value="kb_templates">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/KB-Template-option.jpg'; ?>">
						<span><?php _e( 'Knowledge Base Theme', 'echo-knowledge-base' ); ?></span>
					</div>
				</label>
			</div>
			<div class="epkb-editor-settings-accordeon-item__description"><?php _e( 'You can change the theme any time. Save to apply it.', 'echo-knowledge-base' ); ?></div>

			<a class="epkb-editor-settings-accordeon-item__learn-more" href="https://www.echoknowledgebase.com/documentation/current-theme-vs-kb-theme/" target="_blank"><?php _e( 'Learn More', 'echo-knowledge-base' ); ?></a>
		</div>

		<div class="epkb-editor-settings-panel-container"  id="epkb-editor-settings-layouts">
			<div class="epkb-editor-settings-accordeon-item__title"><?php _e( 'Choose a Layout and save it', 'echo-knowledge-base' ); ?></div>
			<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-image-select">
				<label class="epkb-editor-settings-control-image-select" data-name="kb_main_page_layout">
					<input type="radio" name="kb_main_page_layout" value="Basic">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/basic.jpg'; ?>">
						<span><?php _e( 'Basic', 'echo-knowledge-base' ); ?></span>
					</div>
				</label>

				<label class="epkb-editor-settings-control-image-select" data-name="kb_main_page_layout">
					<input type="radio" name="kb_main_page_layout" value="Tabs">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/tabs.jpg'; ?>">
						<span><?php _e( 'Tabs', 'echo-knowledge-base' ); ?></span>
					</div>
				</label>

				<label class="epkb-editor-settings-control-image-select" data-name="kb_main_page_layout">
					<input type="radio" name="kb_main_page_layout" value="Categories">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/category-focused.jpg'; ?>">
						<span><?php _e( 'Category Focused', 'echo-knowledge-base' ); ?></span>
					</div>
				</label><?php

				if ( EPKB_Utilities::is_elegant_layouts_enabled() ) { ?>

					<label class="epkb-editor-settings-control-image-select" data-name="kb_main_page_layout">
						<input type="radio" name="kb_main_page_layout" value="Grid">

						<div class="epkb-editor-settings-control-image-select--label">
							<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/grid.jpg'; ?>">
							<span><?php _e( 'Grid', 'echo-knowledge-base' ); ?></span>
						</div>
					</label>

					<label class="epkb-editor-settings-control-image-select" data-name="kb_main_page_layout">
					<input type="radio" name="kb_main_page_layout" value="Sidebar">

					<div class="epkb-editor-settings-control-image-select--label">
						<img src="<?php echo Echo_Knowledge_Base::$plugin_url.'img/editor/sidebar.jpg'; ?>">
						<span><?php _e( 'Sidebar', 'echo-knowledge-base' ); ?></span>
					</div>
					</label><?php

				} ?>

			</div>
		</div> <?php

		return ob_get_clean();
	}

	public static function get_editor_madal_menu_lnks( $page_type, $kb_config ) {

		$editor_urls = EPKB_Utilities::get_editor_urls( $kb_config );

		if ( $page_type == 'main-page' ) {
			$editor_url = $editor_urls['article_page_url'];
			$menu_name = __( 'Article Page Editor', 'echo-knowledge-base' );
		} else {
			$editor_url = $editor_urls['main_page_url'];
			$menu_name = __( 'Main Page Editor', 'echo-knowledge-base' );
		}

		ob_start();	?>

		<div class="epkb-editor-settings-menu-container">
			<div class="epkb-editor-settings-menu__inner">
				<div class="epkb-editor-settings-menu__group-container">
					<div class="epkb-editor-settings-menu__group__title"><?php _e( 'Other Pages', 'echo-knowledge-base' ); ?></div>
					<div class="epkb-editor-settings-menu__group-items-container">
						<a href="<?php echo $editor_url; ?>" class="epkb-editor-settings-menu__group-item-container" target="_blank">
							<div class="epkb-editor-settings-menu__group-item__icon epkbfa epkbfa-file-text-o"></div>
							<div class="epkb-editor-settings-menu__group-item__title"><?php echo $menu_name; ?></div>
						</a>
						<a href="<?php echo admin_url( 'edit.php?post_type=' . EPKB_KB_Handler::KB_POST_TYPE_PREFIX . $kb_config['id'] . '&page=epkb-manage-kb' ); ?>" class="epkb-editor-settings-menu__group-item-container" target="_blank">
							<div class="epkb-editor-settings-menu__group-item__icon epkbfa epkbfa-cubes"></div>
							<div class="epkb-editor-settings-menu__group-item__title"><?php _e( 'Manage KBs', 'echo-knowledge-base' ); ?></div>
						</a>
					</div>
					<div class="epkb-editor-settings-menu__group__title"><?php _e( 'Help', 'echo-knowledge-base' ); ?></div>
					<div class="epkb-editor-settings-menu__group-items-container">
						<a href="https://www.echoknowledgebase.com/documentation/" class="epkb-editor-settings-menu__group-item-container" target="_blank">
							<div class="epkb-editor-settings-menu__group-item__icon epkbfa epkbfa-graduation-cap"></div>
							<div class="epkb-editor-settings-menu__group-item__title"><?php _e( 'KB Documentation', 'echo-knowledge-base' ); ?></div>
						</a>
						<a href="https://www.echoknowledgebase.com/technical-support/" class="epkb-editor-settings-menu__group-item-container" target="_blank">
							<div class="epkb-editor-settings-menu__group-item__icon epkbfa epkbfa-life-ring"></div>
							<div class="epkb-editor-settings-menu__group-item__title"><?php _e( 'Support', 'echo-knowledge-base' ); ?></div>
						</a>
					</div>
				</div>
			</div>
		</div>		<?php

		return ob_get_clean();
	}

}