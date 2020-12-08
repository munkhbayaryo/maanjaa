<?php

/**
 * Configuration for the front end editor
 */
 
class EPKB_Editor_Archive_Page_Config extends EPKB_Editor_Base_Config {

	/** SEE DOCUMENTATION IN THE BASE CLASS **/

	/**
	 * Archive zone
	 * @return array
	 */
	private static function archive_zone() {
		// TODO check config, only templates_for_kb_category_archive_page_heading_description tested
		$settings = [

			// Content Tab
			'category_focused_menu_heading_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-acll__title',
				'text' => 1
			],
			'templates_for_kb_category_archive_page_heading_description' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-category-archive-title-desc',
				'text' => 1
			],
			'templates_for_kb_category_archive_read_more' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-article-read-more',
				'text' => 1
			],

			// Style Tab
			'templates_for_kb_category_archive_page_style' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'reload' => '1'
			],

			// Features Tab

			// Advanced Tab
			'archive-container-width-units-v2' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'type' => 'units',
				'reload' => '1'
			],
			'archive-container-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'postfix' => 'archive-container-width-units-v2',
				'style'       => 'small',
				'styles' => [
					'#eckb-categories-archive-container-v2' => 'width',
				]
			],
			'archive-content-padding-v2' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'reload' => '1',
				'style'       => 'small',

			],







		];

		return [
			'archive' => [
				'title'     =>  __( 'Archive', 'echo-knowledge-base' ),
				'classes'   => '#eckb-categories-archive__body',
				'settings'  => $settings
			]];
	}

	/**
	 * Retrieve Editor configuration
	 * @param $kb_config
	 * @return array
	 */
	public function get_config( $kb_config ) {

		// Result config
		$editor_config = [];
		$editor_config += self::archive_zone();

		return self::get_editor_config( $kb_config, $editor_config, [], 'archive-page' );
	}
}