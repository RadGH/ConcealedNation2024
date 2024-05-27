<?php
/**
 * This file adds functions to the concealednation2024 WordPress theme.
 *
 * @package concealednation2024
 * @author  Mike McAlister
 * @license GNU General Public License v2 or later
 * @link    https://concealednation2024wp.com
 */

/**
 * Set up theme defaults and register various WordPress features.
 */
function cn_setup() {

	// Enqueue editor styles and fonts.
	add_editor_style( 'style.css' );

	// Remove core block patterns.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'cn_setup' );


/**
 * Enqueue styles.
 */
function cn_enqueue_theme_assets() {
	// Check if host ends in .radgh.com, if so, load cache-busting version
	if ( str_contains( $_SERVER['HTTP_HOST'], '.radgh.com' ) ) {
		$v = max(
			filemtime( get_template_directory() . '/style.css' ),
			filemtime( get_template_directory() . '/assets/main.js' )
		);
	} else {
		$v = wp_get_theme()->get( 'Version' );
	}
	
	wp_enqueue_style( 'concealednation2024', get_template_directory_uri() . '/style.css', array(), $v );
	
	wp_enqueue_script( 'concealednation2024', get_template_directory_uri() . '/assets/main.js', array(), $v );
}
add_action( 'wp_enqueue_scripts', 'cn_enqueue_theme_assets' );

/**
 * Enqueue admin styles
 */
function cn_enqueue_admin_styles() {
	wp_enqueue_style( 'cn-admin', get_template_directory_uri() . '/assets/admin.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'admin_enqueue_scripts', 'cn_enqueue_admin_styles' );


/**
 * Add Dashicons for use with block styles.
 */
function cn_enqueue_block_dashicons() {
	wp_enqueue_style( 'dashicons' );
}
add_action( 'enqueue_block_assets', 'cn_enqueue_block_dashicons' );


/**
 * Add block style variations.
 */
function cn_register_block_styles() {

	$block_styles = array(
		'core/button'                    => array(
			'secondary-button' => __( 'Secondary', 'concealednation2024' ),
		),
		'core/list'                      => array(
			'list-check'        => __( 'Check', 'concealednation2024' ),
			'list-check-circle' => __( 'Check Circle', 'concealednation2024' ),
			'list-boxed'        => __( 'Boxed', 'concealednation2024' ),
		),
		'core/query-pagination-next'     => array(
			'wp-block-button__link' => __( 'Button', 'concealednation2024' ),
		),
		'core/query-pagination-previous' => array(
			'wp-block-button__link' => __( 'Button', 'concealednation2024' ),
		),
		'core/code'                      => array(
			'dark-code' => __( 'Dark', 'concealednation2024' ),
		),
		'core/cover'                     => array(
			'blur-image-less' => __( 'Blur Image Less', 'concealednation2024' ),
			'blur-image-more' => __( 'Blur Image More', 'concealednation2024' ),
			'rounded-cover'   => __( 'Rounded', 'concealednation2024' ),
		),
		'core/column'                    => array(
			'column-box-shadow' => __( 'Box Shadow', 'concealednation2024' ),
		),
		'core/post-excerpt'              => array(
			'excerpt-truncate-2' => __( 'Truncate 2 Lines', 'concealednation2024' ),
			'excerpt-truncate-3' => __( 'Truncate 3 Lines', 'concealednation2024' ),
			'excerpt-truncate-4' => __( 'Truncate 4 Lines', 'concealednation2024' ),
		),
		'core/group'                     => array(
			'column-box-shadow' => __( 'Box Shadow', 'concealednation2024' ),
		),
		'core/separator'                 => array(
			'separator-dotted' => __( 'Dotted', 'concealednation2024' ),
			'separator-thin'   => __( 'Thin', 'concealednation2024' ),
		),
		'core/image'                     => array(
			'rounded-full' => __( 'Rounded Full', 'concealednation2024' ),
			'media-boxed'  => __( 'Boxed', 'concealednation2024' ),
		),
		'core/preformatted'              => array(
			'preformatted-dark' => __( 'Dark Style', 'concealednation2024' ),
		),
		'core/post-terms'                => array(
			'term-button' => __( 'Button Style', 'concealednation2024' ),
		),
		'core/video'                     => array(
			'media-boxed' => __( 'Boxed', 'concealednation2024' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', 'cn_register_block_styles' );


/**
 * Load custom block styles only when the block is used.
 */
function cn_enqueue_custom_block_styles() {

	// Scan our styles folder to locate block styles.
	$files = glob( get_template_directory() . '/assets/styles/*.css' );

	foreach ( $files as $file ) {

		// Get the filename and core block name.
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "concealednation2024-block-{$filename}",
				'src'    => get_theme_file_uri( "assets/styles/{$filename}.css" ),
				'path'   => get_theme_file_path( "assets/styles/{$filename}.css" ),
			)
		);
	}
}
add_action( 'init', 'cn_enqueue_custom_block_styles' );


/**
 * Register pattern categories.
 */
function cn_pattern_categories() {

	$block_pattern_categories = array(
		'concealednation2024/card'           => array(
			'label' => __( 'Cards', 'concealednation2024' ),
		),
		'concealednation2024/call-to-action' => array(
			'label' => __( 'Call To Action', 'concealednation2024' ),
		),
		'concealednation2024/features'       => array(
			'label' => __( 'Features', 'concealednation2024' ),
		),
		'concealednation2024/hero'           => array(
			'label' => __( 'Hero', 'concealednation2024' ),
		),
		'concealednation2024/pages'          => array(
			'label' => __( 'Pages', 'concealednation2024' ),
		),
		'concealednation2024/posts'          => array(
			'label' => __( 'Posts', 'concealednation2024' ),
		),
		'concealednation2024/pricing'        => array(
			'label' => __( 'Pricing', 'concealednation2024' ),
		),
		'concealednation2024/testimonial'    => array(
			'label' => __( 'Testimonials', 'concealednation2024' ),
		),
	);

	foreach ( $block_pattern_categories as $name => $properties ) {
		register_block_pattern_category( $name, $properties );
	}
}
add_action( 'init', 'cn_pattern_categories', 9 );


/**
 * Remove last separator on blog/archive if no pagination exists.
 */
function cn_is_paginated() {
	global $wp_query;
	if ( $wp_query->max_num_pages < 2 ) {
		echo '<style>.blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .archive .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .search .wp-block-post-template .wp-block-post:last-child .wp-block-post-excerpt + .wp-block-separator { display: none; }</style>';
	}
}
add_action( 'wp_head', 'cn_is_paginated' );


/**
 * Add a Sidebar template part area
 */
function cn_template_part_areas( array $areas ) {
	$areas[] = array(
		'area'        => 'sidebar',
		'area_tag'    => 'section',
		'label'       => __( 'Sidebar', 'concealednation2024' ),
		'description' => __( 'The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'concealednation2024' ),
		'icon'        => 'sidebar',
	);

	return $areas;
}
add_filter( 'default_wp_template_part_areas', 'cn_template_part_areas' );

/**
 * Replace the featured image with a video embed code if specified in the "Featured Video" ACF field group
 *
 * @param $value
 * @param $post_id
 * @param $display_field
 * @param $custom_field_key
 * @param $block
 *
 * @return mixed|string
 */
function cn_replace_featured_image_with_video( $value, $post_id, $display_field, $custom_field_key, $block ) {
	if ( $display_field != 'featured_image' ) return $value;
	
	// Check if video embed is provided, replace the featured image if so
	$embed_code = get_field( 'video_embed', $post_id );
	if ( !$embed_code ) return $value;
	
	// oEmbed = True if the embed is a video URL and we should generate the embed code automatically
	$use_oembed = get_field( 'video_oembed', $post_id );

	if ( $use_oembed ) {
		// Embed code was provided as a URL to an oEmbed service such as YouTube, convert to embed
		$embed_code = wp_oembed_get( $embed_code );
	}
	
	// Aspect ratio = Numeric aspect ratio such as 1.777 for 16:9
	$aspect_ratio = get_field( 'video_aspect_ratio', $post_id );
	
	$class = 'cn-featured-video-embed';
	$style = '';
	
	if ( $aspect_ratio ) {
		$class .= ' has-aspect-ratio';
		$style = 'style="aspect-ratio: ' . $aspect_ratio . ';"';
	}
	
	return '<div class="'. esc_attr($class) .'" '. $style .'>' . $embed_code . '</div>';
}
add_filter( 'rs/post_field', 'cn_replace_featured_image_with_video', 10, 5 );

/**
 * Customize the post date block to show relative date, and to show post updated date if it is different from the published date
 */
function cn_customize_post_date_block( $block_content, $block ) {
	// Only apply on single post pages
	if ( ! is_singular('post') ) return $block_content;
	
	// Check if this is the post date block
	if ( $block['blockName'] !== 'core/post-date' ) return $block_content;
	
	// Get the post ID
	$post_id = get_the_ID();
	
	// Get the post date
	$post_date = get_the_date( '', $post_id );
	
	// Get the post updated date
	$post_updated_date = get_the_modified_date( '', $post_id );
	
	// Check if the post updated date is different than the post date
	// Only applies on single post pages
	if ( $post_date !== $post_updated_date ) {
		// Show the updated date
		$block_content = '<time class="published_date" datetime="' . esc_attr( get_the_date( 'c', $post_id ) ) . '">' . esc_html( $post_date ) . '</time> <time class="updated_date" datetime="' . esc_attr( get_the_modified_date( 'c', $post_id ) ) . '">Updated ' . esc_html( $post_updated_date ) . '</time>';
	} else {
		// Show the post date
		$block_content = '<time class="published_date" datetime="' . esc_attr( get_the_date( 'c', $post_id ) ) . '">' . esc_html( $post_date ) . '</time>';
	}
	
	$block_content = '<div class="cn_post_date">' . $block_content . '</div>';
	
	return $block_content;
}
add_filter( 'render_block', 'cn_customize_post_date_block', 10, 2 );

function cn_customize_social_link_icons( $block_content, $block ) {
	if ( 'core/social-link' != $block['blockName'] ) return $block_content;
	
	// Get the service to display
	// facebook, instagram, rss (feed), x (twitter), youtube
	$service = $block['attrs']['service'];
	if ( $service == 'twitter' ) $service = 'x';
	if ( $service == 'feed' ) $service = 'rss';
	
	$before        = explode( '<svg', $block_content );
	$after         = explode( '</svg>', $before[1] );
	
	$icon_url = get_template_directory_uri() . '/assets/images/social-icons/' . $service . '.png';
	$icon = '<img src="' . esc_attr($icon_url) . '" alt="' . esc_attr($service) . ' icon" />';
	
	$block_content = $before[0] . $icon . $after[1];
	
	return $block_content;
}
add_filter( 'render_block', 'cn_customize_social_link_icons', 10, 2 );

