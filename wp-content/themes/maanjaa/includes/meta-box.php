<?php

add_action( 'cmb2_admin_init', 'portfolio_metabox_register' );

function portfolio_metabox_register() {
	$prefix = 'maanjaa_';

	$portfolio_type = new_cmb2_box( array(
		'id'            => $prefix . 'type_metabox',
		'title'         => esc_attr__( 'Portfolio Type', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$portfolio_type->add_field( array(
		'name'    => esc_attr__('Portfolio type', 'maanjaa' ),
		'id'      => 'portfolio_type',
		'desc'	  => esc_attr__( 'Select your portfolio type and fill your selected fields. (Note: Make sure leave empty other field boxes)', 'maanjaa' ),
		'type'    => 'radio_inline',
		'options' => array(
			'image' => esc_attr__( 'Single image', 'maanjaa' ),
			'content' => esc_attr__( 'Content', 'maanjaa' ),
			'gallery'   => esc_attr__( 'Gallery', 'maanjaa' ),
			'video'     => esc_attr__( 'Video', 'maanjaa' ),
			'soundcloud'     => esc_attr__( 'Soundcloud', 'maanjaa' ),
			'link'     => esc_attr__( 'Link', 'maanjaa' ),
		),
		'default' => 'image',
	) );

	$single_type = new_cmb2_box( array(
		'id'            => $prefix . 'single_type_metabox',
		'title'         => esc_attr__( 'Single image', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$single_type->add_field( array(
		'name'    => esc_attr__('Image file', 'maanjaa' ),
		'desc'    => esc_attr__('Upload an image', 'maanjaa' ),
		'id'      => 'single_image',
		'type'    => 'file',
		'options' => array(
			'url' => false,
		),
		'query_args' => array(
			'type' => array(
				'image/gif',
				'image/jpeg',
				'image/png',
				'image/svg'
			),
		),
		'text'    => array(
			'add_upload_file_text' => esc_attr__('Add File', 'maanjaa' ),
		),
		'preview_size' => 'large',
	) );

	$content_type = new_cmb2_box( array(
		'id'            => $prefix . 'content_type_metabox',
		'title'         => esc_attr__( 'Content', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$content_type->add_field( array(
		'name'    => esc_attr__('Content', 'maanjaa' ),
		'id'      => 'single_content',
		'type'    => 'wysiwyg',
		'options' => array(),
	) );

	$gallery_type = new_cmb2_box( array(
		'id'            => $prefix . 'gallery_type_metabox',
		'title'         => esc_attr__( 'Gallery', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$gallery_type->add_field( array(
		'name' => esc_attr__( 'Upload images', 'maanjaa' ),
		'id'   => 'single_gallery',
		'type' => 'file_list',
	) );

	$video_type = new_cmb2_box( array(
		'id'            => $prefix . 'video_type_metabox',
		'title'         => esc_attr__( 'Video', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$video_type->add_field( array(
		'name' => esc_attr__( 'Video URL', 'maanjaa' ),
		'desc' => esc_attr__( 'Enter a Youtube, Vimeo URL.', 'maanjaa' ),
		'id'   => 'single_video',
		'type' => 'oembed',
	) );

	$soundcloud_type = new_cmb2_box( array(
		'id'            => $prefix . 'soundcloud_type_metabox',
		'title'         => esc_attr__( 'Soundcloud', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$soundcloud_type->add_field( array(
		'name' => esc_attr__( 'Soundcloud URL', 'maanjaa' ),
		'desc' => esc_attr__( 'Enter a Soundcloud URL.', 'maanjaa' ),
		'id'   => 'single_soundcloud',
		'type' => 'oembed',
	) );

	$link_type = new_cmb2_box( array(
		'id'            => $prefix . 'link_type_metabox',
		'title'         => esc_attr__( 'Link', 'maanjaa' ),
		'object_types'  => array( 'portfolio' ),
	) );

	$link_type->add_field( array(
		'name' => esc_attr__( 'Enter URL', 'maanjaa' ),
		'id'   => 'single_link',
		'type' => 'text_url',
	) );
}