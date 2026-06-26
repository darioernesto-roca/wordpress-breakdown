<?php
/**
 * Example: The Loop and WP_Query
 * ------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 6 (The Loop).
 *
 * Real-life use case:
 *   - A blog archive needs to print each post's title, excerpt and link (The Loop).
 *   - A homepage section needs to show the latest 6 "case studies" — a SECONDARY
 *     query that must NOT disturb the main query (WP_Query + wp_reset_postdata()).
 *
 * How to use:
 *   The first block is what you'd put in a template like archive.php or index.php.
 *   The second block is a reusable function you can call from any template or shortcode.
 *
 * Golden rule:
 *   The main Loop uses have_posts()/the_post() directly.
 *   A CUSTOM query uses $query->have_posts()/$query->the_post() and ALWAYS ends
 *   with wp_reset_postdata() so global $post is restored.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* 1. The main Loop (drop this inside a template file)                        */
/* -------------------------------------------------------------------------- */

/**
 * Render the standard main Loop for the current archive/index page.
 * Call this from within a template, e.g. archive.php, between get_header()
 * and get_footer().
 */
function rocadev_render_main_loop() {
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="excerpt"><?php the_excerpt(); ?></div>
			</article>
			<?php
		endwhile;

		the_posts_pagination();
	else :
		echo '<p>No posts found.</p>';
	endif;
}

/* -------------------------------------------------------------------------- */
/* 2. A custom secondary query (WP_Query)                                     */
/* -------------------------------------------------------------------------- */

/**
 * Show the latest case studies using a custom WP_Query.
 * Safe to call anywhere (template, widget, shortcode) because it resets postdata.
 *
 * @param int $count How many case studies to show. Default 6.
 */
function rocadev_render_latest_case_studies( $count = 6 ) {
	$case_studies = new WP_Query(
		array(
			'post_type'      => 'case_study', // see examples/register-custom-post-type.php
			'posts_per_page' => (int) $count,
			'post_status'    => 'publish',
			'no_found_rows'  => true,          // micro-optimization: we don't paginate here
		)
	);

	if ( ! $case_studies->have_posts() ) {
		return;
	}

	echo '<div class="case-study-grid">';

	while ( $case_studies->have_posts() ) :
		$case_studies->the_post();
		?>
		<article class="case-study-card">
			<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
			<?php endif; ?>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php the_excerpt(); ?>
		</article>
		<?php
	endwhile;

	echo '</div>';

	// CRITICAL: restore the global $post so the rest of the template behaves.
	wp_reset_postdata();
}
