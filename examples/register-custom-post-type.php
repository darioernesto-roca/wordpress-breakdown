<?php
/**
 * Example: Register a Custom Post Type (CPT)
 * ------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 9 (Custom Post Types).
 *
 * Real-life use case:
 *   An agency website needs structured "Case Studies" that are NOT regular blog
 *   posts: their own admin menu, their own URL base (/case-studies/), their own
 *   archive page, and exposure to the block editor + REST API.
 *
 * How to use:
 *   Copy into a plugin (preferred for CPTs) or functions.php.
 *   After activating, go to wp-admin — a new "Case Studies" menu appears.
 *
 * IMPORTANT:
 *   After registering (or changing the 'rewrite' slug) you must flush rewrite
 *   rules once, or the /case-studies/ URLs will 404. The cleanest way is to
 *   visit Settings > Permalinks and click "Save". The activation hook at the
 *   bottom does this automatically when used inside a plugin.
 *
 * Pairs with: examples/register-taxonomy.php (adds the "Industry" taxonomy).
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register the "Case Studies" custom post type.
 * Must run on the 'init' hook.
 */
add_action( 'init', 'rocadev_register_case_study_cpt' );
function rocadev_register_case_study_cpt() {
	$labels = array(
		'name'               => 'Case Studies',
		'singular_name'      => 'Case Study',
		'menu_name'          => 'Case Studies',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Case Study',
		'edit_item'          => 'Edit Case Study',
		'new_item'           => 'New Case Study',
		'view_item'          => 'View Case Study',
		'search_items'       => 'Search Case Studies',
		'not_found'          => 'No case studies found',
		'not_found_in_trash' => 'No case studies found in Trash',
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,                       // enables /case-studies/ archive
		'menu_icon'    => 'dashicons-portfolio',
		'menu_position' => 20,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'      => array( 'slug' => 'case-studies' ),
		'show_in_rest' => true,                        // Gutenberg + REST API support
		// 'rest_base' => 'case-studies',              // optional: customize REST route
	);

	register_post_type( 'case_study', $args );
}

/**
 * Flush rewrite rules on plugin activation so the new URLs work immediately.
 *
 * NOTE: Only meaningful when this file is the main file of an activated plugin.
 * If you paste this into a theme's functions.php instead, skip this and just
 * re-save your permalinks once under Settings > Permalinks.
 */
function rocadev_case_study_activate() {
	rocadev_register_case_study_cpt(); // register before flushing
	flush_rewrite_rules();
}
// register_activation_hook( __FILE__, 'rocadev_case_study_activate' );
