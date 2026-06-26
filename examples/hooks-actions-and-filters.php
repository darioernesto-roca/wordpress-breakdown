<?php
/**
 * Example: Hooks — Actions and Filters
 * ------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 5 (Hooks).
 *
 * Real-life use case:
 * On a small business / agency site you want to, without editing WordPress core:
 *   - Drop a hidden marker in the footer for QA or tracking (ACTION).
 *   - Enqueue your theme assets the correct way (ACTION).
 *   - Add a call-to-action box after every single blog post (FILTER).
 *   - Shorten the auto-generated excerpt length (FILTER).
 *   - Expose your own custom hook so other developers can extend your plugin.
 *
 * How to use:
 *   Copy the functions you need into your theme's functions.php or a small plugin.
 *   This whole file is safe to load as-is from a child theme functions.php while learning.
 *
 * Quick reminder:
 *   - ACTION = "do something" at a specific moment (returns nothing).
 *   - FILTER = "modify this value" and RETURN it.
 */

// Prevent direct access when used inside a real WordPress install.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* ACTIONS                                                                    */
/* -------------------------------------------------------------------------- */

/**
 * Print a hidden comment in the footer.
 * Useful as a QA marker to confirm the theme/plugin is active on the page.
 */
add_action( 'wp_footer', 'rocadev_custom_footer_marker' );
function rocadev_custom_footer_marker() {
	echo "\n<!-- Custom footer marker for QA or tracking -->\n";
}

/**
 * Enqueue theme styles and scripts the correct WordPress way.
 * (See examples/enqueue-styles-and-scripts.php for the dedicated, fuller version.)
 */
add_action( 'wp_enqueue_scripts', 'rocadev_theme_assets' );
function rocadev_theme_assets() {
	wp_enqueue_style(
		'rocadev-theme-style',
		get_stylesheet_uri(),
		array(),
		'1.0.0'
	);

	wp_enqueue_script(
		'rocadev-theme-main-js',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),       // dependencies, e.g. array( 'jquery' )
		'1.0.0',
		true           // load in footer
	);
}

/* -------------------------------------------------------------------------- */
/* FILTERS                                                                    */
/* -------------------------------------------------------------------------- */

/**
 * Append a CTA box to the content of single blog posts only.
 * Filters MUST return the (possibly modified) value.
 */
add_filter( 'the_content', 'rocadev_add_cta_after_blog_post' );
function rocadev_add_cta_after_blog_post( $content ) {
	if ( is_single() && 'post' === get_post_type() && is_main_query() ) {
		$cta = '<div class="post-cta">Need help with your website? Contact us today.</div>';
		$content .= $cta;
	}

	return $content;
}

/**
 * Shorten the auto-generated excerpt to 25 words.
 */
add_filter( 'excerpt_length', 'rocadev_custom_excerpt_length' );
function rocadev_custom_excerpt_length( $length ) {
	return 25;
}

/* -------------------------------------------------------------------------- */
/* CUSTOM HOOKS (let other developers extend your code)                       */
/* -------------------------------------------------------------------------- */

/**
 * Pretend this runs after your custom form is processed.
 * By calling do_action(), you create an extension point others can hook into.
 *
 * @param int $submission_id ID of the stored submission.
 */
function rocadev_process_form_submission( $submission_id ) {
	// ... your save logic would run here ...

	// Fire the custom hook so other code can react to the submission.
	do_action( 'rocadev_after_form_submit', $submission_id );
}

/**
 * Another developer (or another part of your plugin) can hook into it
 * without ever touching rocadev_process_form_submission().
 */
add_action( 'rocadev_after_form_submit', 'rocadev_send_internal_notification' );
function rocadev_send_internal_notification( $submission_id ) {
	// e.g. wp_mail() to the site admin, or error_log() for debugging.
	error_log( 'New form submission received: #' . (int) $submission_id );
}
