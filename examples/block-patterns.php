<?php
/**
 * Example: Block Patterns + Pattern Category (Block Themes / Gutenberg)
 * --------------------------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 20 (Block Themes >
 *          Block Patterns).
 *
 * What is a block pattern?
 *   A pre-built layout of blocks the user can insert with one click from the
 *   editor's "Patterns" tab, then edit freely. Great for "Hero", "Call to action",
 *   "Pricing table", etc. — consistent design without rebuilding it each time.
 *
 * Two ways to register patterns in a block theme:
 *   A) FILE-BASED (preferred, no PHP): drop a file in /patterns/ with a header
 *      comment. WordPress auto-registers it. Example shown at the bottom.
 *   B) CODE-BASED (this file): register_block_pattern() — useful in a plugin, or
 *      when the pattern is generated dynamically.
 *
 * How to use:
 *   Copy into a plugin or functions.php. After loading, open any page in the
 *   editor > Patterns tab > "Rocadev" category > "Call to action".
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* 1. Register a custom pattern CATEGORY so your patterns group together       */
/* -------------------------------------------------------------------------- */

add_action( 'init', 'rocadev_register_pattern_category' );
function rocadev_register_pattern_category() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return; // Older WP without the pattern API.
	}
	register_block_pattern_category(
		'rocadev',
		array( 'label' => 'Rocadev' )
	);
}

/* -------------------------------------------------------------------------- */
/* 2. Register a pattern via CODE                                              */
/* -------------------------------------------------------------------------- */

add_action( 'init', 'rocadev_register_cta_pattern' );
function rocadev_register_cta_pattern() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	/*
	 * 'content' is block markup (the HTML-comment syntax Gutenberg saves to
	 * post_content). The easiest way to author this: build the layout once in the
	 * editor, then "Copy" it from the list view and paste it here.
	 */
	$content = '
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"brand","textColor":"paper","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-paper-color has-brand-background-color has-text-color has-background" style="padding-top:4rem;padding-bottom:4rem">
	<!-- wp:heading {"textAlign":"center","level":2} -->
	<h2 class="wp-block-heading has-text-align-center">Ready to start your project?</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center">Tell us what you need and we will get back to you within one business day.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/contact">Get a quote</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->';

	register_block_pattern(
		'rocadev/call-to-action',
		array(
			'title'       => 'Call to action',
			'description' => 'Full-width brand-colored CTA with heading, text, and a button.',
			'categories'  => array( 'rocadev' ),
			'keywords'    => array( 'cta', 'banner', 'contact' ),
			'content'     => $content,
		)
	);
}

/* -------------------------------------------------------------------------- */
/* 3. The FILE-BASED alternative (no PHP needed) — for reference               */
/* -------------------------------------------------------------------------- */

/**
 * In a block theme you can skip all of the above by creating:
 *   wp-content/themes/your-theme/patterns/call-to-action.php
 *
 * ...whose ENTIRE contents are a header comment + raw block markup:
 *
 *   <?php
 *   /**
 *    * Title: Call to action
 *    * Slug: your-theme/call-to-action
 *    * Categories: rocadev
 *    * Keywords: cta, banner, contact
 *    * /
 *   ?>
 *   <!-- wp:group ... -->  ...same block markup as $content above...  <!-- /wp:group -->
 *
 * WordPress scans /patterns/ automatically and registers each file. This is the
 * recommended approach for block themes — patterns live with the theme and need
 * no hooks.
 */
