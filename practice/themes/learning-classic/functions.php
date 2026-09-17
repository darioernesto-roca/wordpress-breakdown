<?php
/**
 * Theme setup and assets.
 *
 * @package RocadevLearningClassic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configures theme-supported features.
 *
 * @return void
 */
function rocadev_learning_classic_setup() {
	load_theme_textdomain( 'rocadev-learning-classic', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'rocadev-learning-classic' ),
		)
	);
}
add_action( 'after_setup_theme', 'rocadev_learning_classic_setup' );

/**
 * Loads the public stylesheet through the WordPress dependency API.
 *
 * @return void
 */
function rocadev_learning_classic_assets() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'rocadev-learning-classic',
		get_stylesheet_uri(),
		array(),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'rocadev_learning_classic_assets' );
