<?php
/**
 * Example: Enqueueing Styles and Scripts
 * --------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 8 (Style and Script Enqueueing).
 *
 * Real-life use case:
 *   You need a global stylesheet + a mobile-menu JS file site-wide, but you only
 *   want the heavy contact-page CSS to load on the Contact page. Enqueueing lets
 *   WordPress manage dependencies, versions, and avoid loading the same file twice.
 *
 * Why not hardcode <link>/<script> in header.php?
 *   - No dependency management (e.g. "load after jQuery").
 *   - No de-duplication (two plugins could both add the same library).
 *   - No cache-busting via version.
 *   - Plugins/other code can't reliably move or dequeue your assets.
 *
 * Companion front-end files referenced below (create these in your theme):
 *   /assets/css/main.css
 *   /assets/css/contact.css
 *   /assets/js/main.js   (example contents at the bottom of this file)
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Enqueue global assets on every front-end page.
 */
add_action( 'wp_enqueue_scripts', 'rocadev_enqueue_global_assets' );
function rocadev_enqueue_global_assets() {
	$theme_uri = get_template_directory_uri();

	// Using filemtime() as the version auto-busts the cache whenever you edit the file.
	$css_path = get_template_directory() . '/assets/css/main.css';
	$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : '1.0.0';

	wp_enqueue_style(
		'rocadev-main',
		$theme_uri . '/assets/css/main.css',
		array(),       // style dependencies
		$css_ver
	);

	wp_enqueue_script(
		'rocadev-main',
		$theme_uri . '/assets/js/main.js',
		array(),       // script dependencies, e.g. array( 'jquery' )
		'1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer', // modern WP (6.3+) supports defer/async here
		)
	);

	// Pass PHP data to your JS safely (e.g. the AJAX URL and a nonce).
	wp_localize_script(
		'rocadev-main',
		'rocadevData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'restUrl' => esc_url_raw( rest_url() ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
		)
	);
}

/**
 * Conditionally enqueue the Contact page stylesheet ONLY on that page.
 * Conditional tags like is_page() keep unused CSS off every other page.
 */
add_action( 'wp_enqueue_scripts', 'rocadev_enqueue_contact_assets' );
function rocadev_enqueue_contact_assets() {
	if ( ! is_page( 'contact' ) ) {
		return;
	}

	wp_enqueue_style(
		'rocadev-contact',
		get_template_directory_uri() . '/assets/css/contact.css',
		array( 'rocadev-main' ), // load after the main stylesheet
		'1.0.0'
	);
}

/*
 * --------------------------------------------------------------------------
 * Companion file contents (place these in your theme; shown here for reference)
 * --------------------------------------------------------------------------
 *
 * /assets/js/main.js
 * ------------------
 * document.addEventListener('DOMContentLoaded', function () {
 *   const menuButton = document.querySelector('.menu-toggle');
 *   const menu = document.querySelector('.main-menu');
 *
 *   if (menuButton && menu) {
 *     menuButton.addEventListener('click', function () {
 *       menu.classList.toggle('is-open');
 *     });
 *   }
 * });
 *
 * /assets/css/main.css
 * --------------------
 * .main-menu { display: none; }
 * .main-menu.is-open { display: block; }
 * .btn { display: inline-block; padding: 12px 20px; text-decoration: none; }
 */
