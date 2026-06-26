<?php
/**
 * Example: Register a Custom Taxonomy
 * -----------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 10 (Taxonomies).
 *
 * Real-life use case:
 *   The "Case Studies" CPT needs to be grouped by "Industry" (Healthcare,
 *   E-commerce, Finance...). A hierarchical taxonomy behaves like Categories
 *   and gives you archive URLs such as /case-studies/industry/healthcare/.
 *
 * How to use:
 *   Load this alongside examples/register-custom-post-type.php (it attaches the
 *   "Industry" taxonomy to the 'case_study' post type). Both run on 'init'.
 *
 * Hierarchical vs non-hierarchical:
 *   - hierarchical = true  -> behaves like Categories (parent/child, checkboxes).
 *   - hierarchical = false -> behaves like Tags (flat, free-form input).
 *
 * Reminder: flush permalinks once (Settings > Permalinks > Save) after adding
 * a taxonomy with a custom rewrite slug, or the term archives may 404.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register the "Industry" taxonomy for Case Studies.
 */
add_action( 'init', 'rocadev_register_industry_taxonomy' );
function rocadev_register_industry_taxonomy() {
	$labels = array(
		'name'              => 'Industries',
		'singular_name'     => 'Industry',
		'search_items'      => 'Search Industries',
		'all_items'         => 'All Industries',
		'parent_item'       => 'Parent Industry',
		'parent_item_colon' => 'Parent Industry:',
		'edit_item'         => 'Edit Industry',
		'update_item'       => 'Update Industry',
		'add_new_item'      => 'Add New Industry',
		'new_item_name'     => 'New Industry Name',
		'menu_name'         => 'Industries',
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true, // category-like
		'show_admin_column' => true, // show the column in the Case Studies list table
		'show_in_rest'      => true, // expose in the block editor + REST API
		'rewrite'           => array( 'slug' => 'case-studies/industry' ),
	);

	// Attach to the 'case_study' CPT. You can pass an array of post types here.
	register_taxonomy( 'industry', array( 'case_study' ), $args );
}

/**
 * (Optional) Seed a few default industry terms on activation so the editor
 * isn't starting from an empty list. Uncomment the register_activation_hook
 * line when using this inside a plugin.
 */
function rocadev_seed_industry_terms() {
	rocadev_register_industry_taxonomy(); // ensure taxonomy exists first

	$defaults = array( 'Healthcare', 'E-commerce', 'Finance', 'Education' );

	foreach ( $defaults as $term ) {
		if ( ! term_exists( $term, 'industry' ) ) {
			wp_insert_term( $term, 'industry' );
		}
	}

	flush_rewrite_rules();
}
// register_activation_hook( __FILE__, 'rocadev_seed_industry_terms' );
