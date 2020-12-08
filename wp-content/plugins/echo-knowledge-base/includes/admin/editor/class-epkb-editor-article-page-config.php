<?php

/**
 * Configuration for the front end editor
 */
 
class EPKB_Editor_Article_Page_Config extends EPKB_Editor_Base_Config {

	/** SEE DOCUMENTATION IN THE BASE CLASS **/

	/**
	 * Article Page zone
	 * @return array
	 */
	private static function page_zone() {

		$settings = [

			// Content Tab

			// Style Tab

			// Features Tab
			'article-container-desktop-width-units-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'units',
				'reload' => 1
			],
			'article-container-desktop-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'postfix' => 'article-container-desktop-width-units-v2',
				'styles' => [
					'#eckb-article-page-container-v2' => 'width',
					//'#eckb-article-body' => 'width',
				]
			],
			
			'article-container-tablet-width-units-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'units',
				'reload' => 1
			],
			'article-container-tablet-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => 1
			],
			
			'article-container-breakpoint-header'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header',
				'content' => __( 'Screen Breakpoints', 'echo-knowledge-base' ),
			],
			'article-tablet-break-point-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article-mobile-break-point-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],

			'article-body-header'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header_desc',
				'title' => __( 'Body Container', 'echo-knowledge-base' ),
				'desc' => __( 'The container for the Left / Right Sidebars and the center content', 'echo-knowledge-base' ),
			],
			'article-body-desktop-width-units-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'units',
				'reload' => 1
			],
			'article-body-desktop-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'postfix' => 'article-body-desktop-width-units-v2',
				'styles' => [
					'#eckb-article-page-container-v2 #eckb-article-body' => 'width',
				]
			],

			'article-body-tablet-width-units-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'units',
				'reload' => 1
			],
			'article-body-tablet-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'postfix' => 'article-body-tablet-width-units-v2',
				'styles' => [
					'#eckb-article-page-container-v2 #eckb-article-body' => 'width',
				]
			],


			// Advanced Tab
			'kb_article_page_layout' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],
			'templates_for_kb_article_padding_group' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'subfields' => [
					'templates_for_kb_article_padding_left' => [
						'style_name' => 'padding-left',
					],
					'templates_for_kb_article_padding_top' => [
						'style_name' => 'padding-top',
					],
					'templates_for_kb_article_padding_right' => [
						'style_name' => 'padding-right',
					],
					'templates_for_kb_article_padding_bottom' => [
						'style_name' => 'padding-bottom',
					],
				]
			],
			'templates_for_kb_article_margin_group' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Margin', 'echo-knowledge-base' ),
				'subfields' => [
					'templates_for_kb_article_margin_left' => [
						'style_name' => 'margin-left',
					],
					'templates_for_kb_article_margin_top' => [
						'style_name' => 'margin-top',
					],
					'templates_for_kb_article_margin_right' => [
						'style_name' => 'margin-right',
					],
					'templates_for_kb_article_margin_bottom' => [
						'style_name' => 'margin-bottom',
					],
				]
			],

		];

		return [
			'article_page' => [
				'title'     =>  __( 'Page Content', 'echo-knowledge-base' ),
				'classes'   => '#eckb-article-page-container-v2',
				'settings'  => $settings,
				'parent_zone_tab_title' => __( 'Page Content', 'echo-knowledge-base' )
			]];
	}

	/**
	 * Serach Box zone
	 * @return array
	 */
	
	private static function article_search_box_zone() {

		$settings = [
			'article_search_layout' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'reload' => '1',
			],
			'article_search_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-doc-search-container',
				'style_name' => 'background-color'
			],
			'article_search_box_padding' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'units' => 'px',
				'subfields' => [
					'article_search_box_padding_left' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'padding-left',
						'postfix' => 'px'
					],
					'article_search_box_padding_top' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'padding-top',
						'postfix' => 'px'
					],
					'article_search_box_padding_right' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'padding-right',
						'postfix' => 'px'
					],
					'article_search_box_padding_bottom' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'padding-bottom',
						'postfix' => 'px'
					],
				]
			],
			'article_search_box_margin' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Margin', 'echo-knowledge-base' ),
				'units' => 'px',
				'subfields' => [
					'article_search_box_margin_top' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'margin-top',
						'postfix' => 'px'
					],
					'article_search_box_margin_bottom' => [
						'target_selector' => '.epkb-doc-search-container',
						'style_name' => 'margin-bottom',
						'postfix' => 'px'
					],
				]
			],
		];

		return [
			'article_search_box' => [
				'title'     =>  __( 'Search Box', 'echo-knowledge-base' ),
				'classes'   => '.epkb-doc-search-container',
				'settings'  => $settings,
				'disabled_settings' => [
					'article_search_layout' => 'epkb-search-form-0'
				] 
			]];
	}

	/**
	 * Search Title zone
	 * @return array
	 */
	private static function article_search_title_zone() {

		$settings = [
			'article_search_title' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.epkb-doc-search-container__title',
				'target_attr' => 'value',
				'text' => 1
			],
			'article_search_title_html_tag' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.epkb-doc-search-container__title',
				'reload' => 1,
				'text_style' => 'inline'
			],
			'article_search_title_font_size' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-doc-search-container__title',
				'style_name' => 'font-size',
				'style' => 'slider',
				'postfix' => 'px'
			],
			'article_search_title_font_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-doc-search-container__title',
				'style_name' => 'color'
			],
		];

		return [
			'article_search_title_zone' => [
				'title'     =>  __( 'Search Title', 'echo-knowledge-base' ),
				'classes'   => '.epkb-doc-search-container__title',
				'settings'  => $settings
			]];
	}

	/**
	 * Search Input box zone
	 * @return array
	 */
	private static function article_search_input_zone() {

		$settings = [
			'article_search_box_hint' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '#epkb_search_terms',
				'target_attr' => 'placeholder|aria-label',
			],
			'article_search_results_msg' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
			],
			'no_results_found' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
			],
			'min_search_word_size_msg' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
			],
			'article_search_input_border_width' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-search-box input[type=text]',
				'style_name' => 'border-width',
				'postfix' => 'px'
			],
			'article_search_text_input_border_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-search-box input[type=text]',
				'style_name' => 'border-color'
			],
			'article_search_text_input_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-search-box input[type=text]',
				'style_name' => 'background-color'
			],
			'article_search_box_results_style' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'target_selector' => '#epkb_article_search_results',
			],
			'article_search_box_input_width' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'target_selector' => '#epkb_search_form',
				'style_name' => 'width',
				'postfix' => '%'
			],
		];

		return [
			'article_search_input_zone' => [
				'title'     =>  __( 'Search Input Box', 'echo-knowledge-base' ),
				'classes'   => '.epkb-doc-search-container input',
				'settings'  => $settings
			]];
	}

	/**
	 * Serach Button zone
	 * @return array
	 */
	private static function article_search_button_zone() {

		$settings = [
			'article_search_button_name' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '#epkb-search-kb',
				'target_attr' => 'value',
				'text' => 1
			],
			'article_search_btn_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-search-box button',
				'style_name' => 'background-color'
			],
			'article_search_btn_border_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-search-box button',
				'style_name' => 'border-color'
			],
		];

		return [
			'article_search_button_zone' => [
				'title'     =>  __( 'Search Button', 'echo-knowledge-base' ),
				'classes'   => '.epkb-search-box button',
				'settings'  => $settings
			]];
	}

	/**
	 * Left Sidebar zone
	 * @param $kb_config
	 * @return array
	 */
	private static function left_sidebar_zone( $kb_config ) {

		$options = array(
			'0' => __( 'Not displayed', 'echo-knowledge-base' ),
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5'
		);
		$options2 = array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5'
		);

		$settings = [

			// Content Tab
			'article-left-sidebar-toggle'                => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'reload' => '1'
			],
			
			// Style Tab
			'article-left-sidebar-background-color-v2' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '#eckb-article-left-sidebar',
				'style_name' => 'background-color'
			],

			// Features Tab
			'article-left-sidebar-match'                => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article-left-sidebar-starting-position'    => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'target_selector' => '#eckb-article-page-container-v2 #eckb-article-left-sidebar',
				'style_name' => 'margin-top',
				'postfix' => 'px',
				'reload' => '1',
				'style_important' => 0
			],
			'article-left-sidebar-header-desktopWidth'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header',
				'content' => 'Sidebar Width'
			],
			'article-left-sidebar-desktop-width-v2'     => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'style' => 'slider',
				'max' => 40
			],
			'article-left-sidebar-tablet-width-v2'      => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],

			// Advanced Tab
			'article-left-sidebar-padding' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'units' => 'px',
				'subfields' => [
					'article-left-sidebar-padding-v2_left' => [
						'target_selector' => '#eckb-article-left-sidebar',
						'style_name' => 'padding-left',
						'postfix' => 'px'
					],
					'article-left-sidebar-padding-v2_top' => [
						'target_selector' => '#eckb-article-left-sidebar',
						'style_name' => 'padding-top',
						'postfix' => 'px'
					],
					'article-left-sidebar-padding-v2_right' => [
						'target_selector' => '#eckb-article-left-sidebar',
						'style_name' => 'padding-right',
						'postfix' => 'px'
					],
					'article-left-sidebar-padding-v2_bottom' => [
						'target_selector' => '#eckb-article-left-sidebar',
						'style_name' => 'padding-bottom',
						'postfix' => 'px'
					],
				]
			],

			// sidebar components priority
			'article-left-sidebar-header-locations'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header_desc',
				'title' => 'Element Locations',
				'desc' => 'The higher the number the higher up on the sidebar it will be located.'
			],
			'categories_left' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'Category Layout Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'options'    => $options,
				'default'     => '0',
				'reload' => '1'
			],
			'elay_sidebar_left' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'      => __( 'Navigation Menu', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => ( $kb_config['kb_main_page_layout'] == 'Sidebar' ? $options2 : $options ),
				'default'     => '1',
				'reload' => '1'
			],
			'toc_left' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'TOC Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => $options,
				'default'     => '0',
				'reload' => '1'
			],
			'kb_sidebar_left' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'Widgets Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => $options,
				'default'     => '0',
				'reload' => '1'
			],
		];

		return [
			'left_sidebar' => [
				'title'     =>  __( 'Left Sidebar', 'echo-knowledge-base' ),
				'classes'   => '#eckb-article-left-sidebar',
				'settings'  => $settings,
				'disabled_settings' => [
					'article-left-sidebar-toggle' => 'off'
				] 
			]];
	}

	/**
	 * Article Content (Center Content) zone
	 * @return array
	 */
	private static function article_content_zone() {
		
		$options = array(
			'0' => __( 'Not displayed', 'echo-knowledge-base' ),
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5'
		);

		$settings = [
			'article-content-background-color-v2' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '#eckb-article-content',
				'style_name' => 'background-color'
			],

			'toc_content' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'Display of TOC', 'echo-knowledge-base' ),
				'default'     => '0',
				'reload' => '1',
				'type'        => EPKB_Input_Filter::SELECTION,
				'options'    => $options,
			],
			'article-content-desktop-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'hidden'
			],
			'article-content-tablet-width-v2' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'hidden'
			],
			'article-content-padding-v2' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'target_selector' => '#eckb-article-page-container-v2 #eckb-article-content',
				'style_name' => 'padding',
				'style' => 'slider',
				'postfix' => 'px'
			],

			'articles_comments_global' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
				'description' => '<a href="https://www.echoknowledgebase.com/documentation/wordpress-enabling-article-comments/" target="_blank" class="epkb-comment-link">' . __( 'Enable Article Comments', 'echo-knowledge-base' ) . ' <span class="epkbfa epkbfa-external-link"></span></a>'

			],

		];

		return [
			'article_content' => [
				'title'     =>  __( 'Article Content', 'echo-knowledge-base' ),
				'classes'   => '#eckb-article-content',
				'settings'  => $settings
			]];
	}

	/**
	 * Right Sidebar zone
	 * @return array
	 */
	private static function right_sidebar_zone() {

		$options = array(
			'0' => __( 'Not displayed', 'echo-knowledge-base' ),
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5'
		);

		$settings = [

			// Content Tab
			'article-right-sidebar-toggle'                => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'reload' => '1'
			],
			
			// Style Tab
			'article-right-sidebar-background-color-v2' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '#eckb-article-right-sidebar',
				'style_name' => 'background-color'
			],

			// Features Tab
			'article-right-sidebar-match'               => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
			],
			
			'article-right-sidebar-starting-position'   => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
				'target_selector' => '#eckb-article-page-container-v2 #eckb-article-right-sidebar',
				'style_name' => 'margin-top',
				'postfix' => 'px',
				'style_important' => 0
			],
			'article-left-sidebar-header-desktopWidth'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header',
				'content' => 'Sidebar Width'
			],
			'article-right-sidebar-desktop-width-v2'    => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'style' => 'slider',
				'max' => 40
			],
			'article-right-sidebar-tablet-width-v2'     => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
			],

			// Advanced Tab
			'article-right-sidebar-padding' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'units' => 'px',
				'subfields' => [
					'article-right-sidebar-padding-v2_left' => [
						'target_selector' => '#eckb-article-right-sidebar',
						'style_name' => 'padding-left',
						'postfix' => 'px'
					],
					'article-right-sidebar-padding-v2_top' => [
						'target_selector' => '#eckb-article-right-sidebar',
						'style_name' => 'padding-top',
						'postfix' => 'px'
					],
					'article-right-sidebar-padding-v2_right' => [
						'target_selector' => '#eckb-article-right-sidebar',
						'style_name' => 'padding-right',
						'postfix' => 'px'
					],
					'article-right-sidebar-padding-v2_bottom' => [
						'target_selector' => '#eckb-article-right-sidebar',
						'style_name' => 'padding-bottom',
						'postfix' => 'px'
					],
				]
			],

			// sidebar components priority
			'article-right-sidebar-header-locations'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header_desc',
				'title' => 'Element Locations',
				'desc' => 'The higher the number the higher up on the sidebar it will be located.'
			],
			'categories_right' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'Category Layout Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => $options,
				'default'     => '0',
				'reload' => '1'
			],
			'toc_right' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'TOC Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => $options,
				'default'     => '1',
				'reload' => '1'
			],
			'kb_sidebar_right' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'label'            => __( 'Widgets Location', 'echo-knowledge-base' ),
				'type'        => EPKB_Input_Filter::SELECTION,
				'style'       => 'small',
				'options'    => $options,
				'default'     => '0',
				'reload' => '1'
			],
		];

		return [
			'right_sidebar' => [
				'title'     =>  __( 'Right Sidebar', 'echo-knowledge-base' ),
				'parent_zone_tab_title' => __( 'Sidebar', 'echo-knowledge-base' ), // example
				'classes'   => '#eckb-article-right-sidebar',
				'settings'  => $settings,
				'disabled_settings' => [
					'article-right-sidebar-toggle' => 'off'
				] 
			]];
	}

	/**
	 * Meta Data HEADER zone
	 * @return array
	 */
	private static function meta_data_header_zone() {

		$settings = [

			// setup
			'meta-data-header-toggle'   => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'last_udpated_on_header_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'created_on_header_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'author_header_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article_meta_icon_on' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],

			// text
			'last_udpated_on_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__date-updated__text',
				'target_attr' => 'value',
				'text' => '1',
			],
			'created_on_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__date-created__text',
				'target_attr' => 'value',
				'text' => '1',
			],
			'author_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__author__text',
				'target_attr' => 'value',
				'text' => '1',
			],
		];

		return [
			'metadata_header' => [
				'title'     =>  __( 'Top Author and Dates', 'echo-knowledge-base' ),
				'classes'   => '.eckb-article-content-header__article-meta',
				'settings'  => $settings,
				'disabled_settings' => [
					'meta-data-header-toggle' => 'off',
				]
			]];
	}

	/**
	 * Meta Data FOOTER zone
	 * @return array
	 */
	private static function meta_data_footer_zone() {

		$settings = [

			// setup
			'meta-data-footer-toggle'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'last_udpated_on_footer_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'created_on_footer_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'author_footer_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article_meta_icon_on' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],

			// text
			'last_udpated_on_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__date-updated__text',
				'target_attr' => 'value',
				'text' => '1',
			],
			'created_on_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__date-created__text',
				'target_attr' => 'value',
				'text' => '1',
			],
			'author_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-ach__article-meta__author__text',
				'target_attr' => 'value',
				'text' => '1',
			],
		];

		return [
			'metadata_footer' => [
				'title'     =>  __( 'Bottom Author and Dates', 'echo-knowledge-base' ),
				'classes'   => '.eckb-article-content-footer__article-meta',
				'settings'  => $settings,
				'disabled_settings' => [
					'meta-data-footer-toggle' => 'off'
				]
			]];
	}

	/**
	 * Breadcrumb zone
	 * @return array
	 */
	private static function breadcrumb_zone() {

		$settings = [
			'breadcrumb_description_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-breadcrumb-label',
				'target_attr' => 'value',
				'text' => '1',
			],
			'breadcrumb_home_text' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-breadcrumb-nav li:first-child a span, .eckb-breadcrumb-nav li:first-child .eckb-breadcrumb-link span:first-child',
				'target_attr' => 'value',
				'text' => '1',
			],
			'breadcrumb_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-breadcrumb-link span:not(.eckb-breadcrumb-link-icon)',
				'style_name' => 'color'
			],
			'breadcrumb_toggle' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'breadcrumb_icon_separator' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'breadcrumb_font_size' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'reload' => '1'
			],
			'breadcrumb_padding_group' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'subfields' => [
					'breadcrumb_padding_left' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'padding-left',
					],
					'breadcrumb_padding_top' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'padding-top',
					],
					'breadcrumb_padding_right' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'padding-right',
					],
					'breadcrumb_padding_bottom' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'padding-bottom',
					],
				]
			],
			'breadcrumb_margin_group' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Margin', 'echo-knowledge-base' ),
				'subfields' => [
					'breadcrumb_margin_left' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'margin-left',
					],
					'breadcrumb_margin_top' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'margin-top',
					],
					'breadcrumb_margin_right' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'margin-right',
					],
					'breadcrumb_margin_bottom' => [
						'target_selector' => '.eckb-breadcrumb',
						'postfix' => 'px',
						'style_name' => 'margin-bottom',
					],
				]
			],

		];

		return [
			'breadcrumb' => [
				'title'     =>  __( 'Breadcrumb', 'echo-knowledge-base' ),
				'classes'   => '.eckb-breadcrumb',
				'settings'  => $settings,
				'disabled_settings' => [
					'breadcrumb_toggle' => 'off'
				]
			]];
	}

	/**
	 * Back Navigation zone
	 * @return array
	 */
	private static function back_navigation_zone() {

		$settings = [

			// Content Tab
			'back_navigation_text'          => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-navigation-button',
				'target_attr' => 'value',
				'text' => '1',
			],

			// Style Tab
			'back_navigation_text_color'    => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-navigation-back .eckb-navigation-button a, .eckb-navigation-back .eckb-navigation-button',
				'style_name' => 'color'
			],
			'back_navigation_bg_color'      => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-navigation-back .eckb-navigation-button a, .eckb-navigation-back .eckb-navigation-button',
				'style_name' => 'background-color'
			],
			'back_navigation_border_color'  => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-navigation-back .eckb-navigation-button a, .eckb-navigation-back .eckb-navigation-button',
				'style_name' => 'border-color'
			],

			// Features Tab
			'back_navigation_toggle'        => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'back_navigation_mode'          => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
			],
			'back_navigation_font_size'     => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'target_selector' => '.eckb-navigation-button a, .eckb-navigation-button',
				'style_name' => 'font-size',
				'postfix' => 'px'
			],

			'back_navigation_border'        => [
				'editor_tab'        => self::EDITOR_TAB_FEATURES,
				'reload'            => '1',
				'separator_above'   => 'yes'
			],
			'back_navigation_border_radius' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
			],
			'back_navigation_border_width'  => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
			],

			// Advanced Tab
			'back_navigation_padding_group' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Padding', 'echo-knowledge-base' ),
				'subfields' => [
					'back_navigation_padding_left' => [
						'style_name' => 'padding-left',
					],
					'back_navigation_padding_top' => [
						'style_name' => 'padding-top',
					],
					'back_navigation_padding_right' => [
						'style_name' => 'padding-right',
					],
					'back_navigation_padding_bottom' => [
						'style_name' => 'padding-bottom',
					],
				]
			],
			'back_navigation_margin_group'  => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'group_type' => self::EDITOR_GROUP_DIMENSIONS,
				'label' => __( 'Margin', 'echo-knowledge-base' ),
				'subfields' => [
					'back_navigation_margin_left' => [
						'style_name' => 'margin-left',
					],
					'back_navigation_margin_top' => [
						'style_name' => 'margin-top',
					],
					'back_navigation_margin_right' => [
						'style_name' => 'margin-right',
					],
					'back_navigation_margin_bottom' => [
						'style_name' => 'margin-bottom',
					],
				]
			],

		];

		return [
			'back_navigation' => [
				'title'     =>  __( 'Back Navigation', 'echo-knowledge-base' ),
				'classes'   => '.eckb-navigation-button',
				'settings'  => $settings,
				'disabled_settings' => [
					'back_navigation_toggle' => 'off'
				]
			]];
	}

	/**
	 * Categories List zone
	 * @return array
	 */
	private static function categories_layout_list_zone() {

		$settings = [
			'category_box_title_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => ' .eckb-acll__title',
				'style_name' => 'color'
			],
			'category_box_container_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-article-cat-layout-list',
				'style_name' => 'background-color'
			],
			'category_box_category_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-acll__cat-item__name',
				'style_name' => 'color'
			],
			'category_box_count_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-acll__cat-item__count',
				'style_name' => 'background-color'
			],
			'category_box_count_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-acll__cat-item__count',
				'style_name' => 'color'
			],
			'category_box_count_border_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-acll__cat-item__count',
				'style_name' => 'border-color'
			],
			'categories_layout_list_mode' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
			],
			'categories_box_font_size' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
		];

		return [
			'categories_list' => [
				'title'     =>  __( 'Categories List', 'echo-knowledge-base' ),
				'classes'   => '',
				'settings'  => $settings
			]];
	}

	/**
	 * TOC zone
	 * @return array
	 */
	private static function toc_zone() {

		$settings = [

			// Content Tab
			'article_toc_title' => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.eckb-article-toc__title',
				'target_attr' => 'value',
				'text' => '1',
			],

			// Style Tab
			'article_toc_background_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-article-toc__inner',
				'style_name' => 'background-color'
			],
			'article_toc_border_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.eckb-article-toc__inner',
				'style_name' => 'border-color'
			],
			'article_toc_header' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'type' => 'header',
				'content' => 'Article'
			],
			'article_toc_text_color' => [
				'editor_tab'        => self::EDITOR_TAB_STYLE,
				'target_selector'   => '.eckb-article-toc__inner a',
				'style_name'        => 'color',
			],
			'article_toc_active_bg_color' => [
				'editor_tab'        => self::EDITOR_TAB_STYLE,
				'target_selector'   => '#eckb-article-body .eckb-article-toc ul a.active',
				'style_name' => 'background-color'
			],
			'article_toc_active_text_color' => [
				'editor_tab'        => self::EDITOR_TAB_STYLE,
				'target_selector'   => '#eckb-article-body .eckb-article-toc ul a.active',
				'style_name' => 'color'
			],
			'article_toc_cursor_hover_bg_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '#eckb-article-body .eckb-article-toc ul a:hover',
				'style_name' => 'background-color'
			],
			'article_toc_cursor_hover_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '#eckb-article-body .eckb-article-toc ul a:hover',
				'style_name' => 'color'
			],

			// Features Tab
			'article_toc_font_size'     => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article_toc_border_mode'   => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article_toc_lvl_header'    => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'type' => 'header',
				'content' => 'Header Range'
			],
			'article_toc_hx_level'      => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
			],
			'article_toc_hy_level'      => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],
			'article_toc_scroll_offset' => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1',
				'separator_above'   => 'yes',
			],

			//Advanced Tab
			'article_toc_exclude_class' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'reload' => '1'
			],

			'article_toc_width_header'  => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'type' => 'header',
				'content' => 'Container Width'
			],
			'article_toc_width_1'       => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],
			'article_toc_width_2'       => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],

			'article_toc_resolutions_header'  => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
				'type' => 'header',
				'content' => 'Starting Resolutions for Widths'
			],

			'article_toc_media_1' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],

			'article_toc_media_2' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],
			'article_toc_media_3' => [
				'editor_tab' => self::EDITOR_TAB_ADVANCED,
			],
		];

		return [
			'toc' => [
				'title'     =>  __( 'TOC', 'echo-knowledge-base' ),
				'zone_tab_title'     =>  __( 'TOC', 'echo-knowledge-base' ), // example
				'classes'   => '.eckb-article-toc',
				'settings'  => $settings
			]];
	}

	/**
	 * Prev/Next Navigation zone
	 * @return array
	 */
	private static function prev_next_zone() {

		$settings = [

			// setup
			'prev_next_navigation_enable'           => [
				'editor_tab' => self::EDITOR_TAB_FEATURES,
				'reload' => '1'
			],

			'prev_navigation_text'                  => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.epkb-article-navigation__previous a .epkb-article-navigation__label',
				'target_attr' => 'value',
				'text' => '1',
			],
			'next_navigation_text'                  => [
				'editor_tab' => self::EDITOR_TAB_CONTENT,
				'target_selector' => '.epkb-article-navigation__next a .epkb-article-navigation__label',
				'target_attr' => 'value',
				'text' => '1',
			],
			'prev_next_navigation_text_color'       => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-article-navigation-container a',
				'style_name' => 'color'
			],
			'prev_next_navigation_bg_color'         => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-article-navigation-container a',
				'style_name' => 'background-color'
			],
			'prev_next_navigation_hover_text_color' => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-article-navigation-container a:hover',
				'style_name' => 'color'
			],
			'prev_next_navigation_hover_bg_color'   => [
				'editor_tab' => self::EDITOR_TAB_STYLE,
				'target_selector' => '.epkb-article-navigation-container a:hover',
				'style_name' => 'background-color'
			],
		];

		return [
			'prev_next' => [
				'title'     =>  __( 'Prev/Next Navigation', 'echo-knowledge-base' ),
				'classes'   => '.epkb-article-navigation-container',
				'settings'  => $settings,
				'disabled_settings' => [
					'prev_next_navigation_enable' => 'off'
				]
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

		// Advanced Search has its own search box settings so exclude the KB core ones
		if ( ! $this->is_asea ) {
			$editor_config += self::article_search_box_zone();
			$editor_config += self::article_search_title_zone();
			$editor_config += self::article_search_input_zone();
			$editor_config += self::article_search_button_zone();
		}
		
		$editor_config += self::page_zone();

		// Content
		$editor_config += self::left_sidebar_zone( $kb_config );

		$editor_config += self::meta_data_header_zone();
		$editor_config += self::breadcrumb_zone();
		$editor_config += self::back_navigation_zone();

		$editor_config += self::article_content_zone();

		$editor_config += self::meta_data_footer_zone();

		$editor_config += self::prev_next_zone();

		$editor_config += self::right_sidebar_zone();

		$editor_config += self::categories_layout_list_zone();
		$editor_config += self::toc_zone();

		$unset_settings = [];

		// Hide Settings Based on condition
		if ( ! $this->is_categories_main_page ) {
			$unset_settings = array_merge($unset_settings, [
				'categories_left',
				'categories_right',
			]);
		}

		if ( ! EPKB_Utilities::is_elegant_layouts_enabled() ) {
			$unset_settings = array_merge($unset_settings, [
				'elay_sidebar_left',
			]);
		}
		
		return self::get_editor_config( $kb_config, $editor_config, $unset_settings, 'article-page' );
	}
}