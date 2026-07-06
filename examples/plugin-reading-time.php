<?php
/**
 * Plugin Name:       Rocadev Reading Time
 * Plugin URI:        https://example.com/plugins/rocadev-reading-time
 * Description:       Prepends an "X min read" badge to single posts. Demonstrates content filters, post-meta caching on save, and filter extension points.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Rocadev
 * License:           GPL-2.0-or-later
 * Text Domain:       rocadev-reading-time
 *
 * --------------------------------------------------------------------------
 * Example: "Reading Time" — a filter-driven plugin with meta caching
 * --------------------------------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 21 (Plugins Basics)
 *          and section 5 (Hooks: filters as the core plugin mechanism).
 *
 * What it does (real-life use case):
 *   Blogs show "5 min read" under the title so visitors know what they're
 *   committing to. This plugin computes it from the post's word count and
 *   prepends a badge to the content of single posts — no theme edits, so it
 *   survives a theme switch (section 21.5).
 *
 * What it demonstrates, in order:
 *   1. A FILTER as the plugin's engine     -> the_content + main-query guards
 *   2. Caching in post meta on save_post   -> compute once, not on every view
 *   3. Lazy backfill                       -> old posts get meta on first view
 *   4. Extension points                    -> 'rocadev_rt_*' filters, marked
 *                                             "Extension point" inline in 1./2.
 *   5. Conditional inline styles           -> enqueue only where the badge shows
 *   6. Uninstall cleanup                   -> commented uninstall.php reference
 *
 * Note on lifecycle hooks (section 21.3): this plugin needs NO activation or
 * deactivation hook — it has no options, no cron, no rewrite rules. Don't add
 * lifecycle code you don't need; the lazy backfill in (3) covers existing posts.
 *
 * How to use:
 *   Copy this file into wp-content/plugins/ and activate it. Open any single
 *   blog post: the badge appears above the content. Re-save a post to see the
 *   cached word count under the hood (post meta '_rocadev_rt_word_count').
 *
 * Everything global is prefixed rocadev_rt_ — rename to your own prefix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* 1. THE ENGINE — prepend the badge via the_content                           */
/* -------------------------------------------------------------------------- */

add_filter( 'the_content', 'rocadev_rt_prepend_badge' );
function rocadev_rt_prepend_badge( $content ) {
	/*
	 * the_content runs in many places you DON'T want the badge: excerpts on
	 * archive pages, RSS feeds, widgets, other plugins calling apply_filters.
	 * These three guards limit it to the main content of a single post view.
	 */
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content; // A filter must ALWAYS return its value (section 5).
	}

	$minutes = rocadev_rt_get_minutes( get_the_ID() );

	/*
	 * Extension point (21.6g): themes can restyle or reword the whole badge
	 * with add_filter( 'rocadev_rt_badge_html', ... ) without touching this file.
	 */
	$badge = apply_filters(
		'rocadev_rt_badge_html',
		'<p class="rocadev-rt-badge">' . esc_html( $minutes . ' min read' ) . '</p>',
		$minutes
	);

	return $badge . $content;
}

/* -------------------------------------------------------------------------- */
/* 2. + 3. THE CACHE — word count stored in post meta, backfilled lazily       */
/* -------------------------------------------------------------------------- */

/**
 * Reading time for a post, from cached word count when available.
 *
 * The underscore prefix in '_rocadev_rt_word_count' hides the meta key from
 * the editor's "Custom Fields" panel — it's internal plugin state, not content.
 *
 * @param int $post_id Post ID.
 * @return int Whole minutes, minimum 1.
 */
function rocadev_rt_get_minutes( $post_id ) {
	$words = (int) get_post_meta( $post_id, '_rocadev_rt_word_count', true );

	if ( $words <= 0 ) {
		/*
		 * Lazy backfill: posts published BEFORE this plugin existed have no
		 * meta yet. Compute once now, store it, and every later view is a
		 * cheap meta read. Self-healing cache — no activation-time bulk job.
		 */
		$words = rocadev_rt_count_words( get_post_field( 'post_content', $post_id ) );
		update_post_meta( $post_id, '_rocadev_rt_word_count', $words );
	}

	/*
	 * 200 words/minute is a common average for casual reading. Sites with
	 * technical content can slow it down: add_filter returning e.g. 130.
	 */
	$wpm = (int) apply_filters( 'rocadev_rt_words_per_minute', 200 );

	return max( 1, (int) ceil( $words / max( 1, $wpm ) ) );
}

add_action( 'save_post_post', 'rocadev_rt_cache_word_count', 10, 2 );
/**
 * Recount on every save so the cache never goes stale.
 *
 * 'save_post_post' = the save_post hook already narrowed to post type 'post',
 * so no post-type check is needed inside.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    The post being saved.
 */
function rocadev_rt_cache_word_count( $post_id, $post ) {
	// Autosaves and revisions carry intermediate content — skip them.
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	update_post_meta(
		$post_id,
		'_rocadev_rt_word_count',
		rocadev_rt_count_words( $post->post_content )
	);
}

/**
 * Count words in post content.
 *
 * Strips block markup comments, shortcodes, and HTML tags first, then splits
 * on whitespace. preg_split (not str_word_count) so accented and non-Latin
 * words count correctly.
 *
 * @param string $content Raw post_content.
 * @return int Word count.
 */
function rocadev_rt_count_words( $content ) {
	$text = strip_shortcodes( $content );
	$text = excerpt_remove_blocks( $text ); // Drop non-text blocks (embeds etc.).
	$text = wp_strip_all_tags( $text );
	$text = trim( $text );

	if ( '' === $text ) {
		return 0;
	}

	return count( preg_split( '/\s+/u', $text ) );
}

/* -------------------------------------------------------------------------- */
/* 5. STYLES — enqueue only where the badge actually renders                   */
/* -------------------------------------------------------------------------- */

add_action( 'wp_enqueue_scripts', 'rocadev_rt_styles' );
function rocadev_rt_styles() {
	if ( ! is_singular( 'post' ) ) {
		return; // No badge on this view -> ship no CSS (section 8 discipline).
	}

	wp_register_style( 'rocadev-rt', false, array(), '1.0.0' );
	wp_enqueue_style( 'rocadev-rt' );
	wp_add_inline_style(
		'rocadev-rt',
		'.rocadev-rt-badge{display:inline-block;padding:.2em .8em;border-radius:999px;background:#f0f0f1;color:#3c434a;font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;}'
	);
}

/* -------------------------------------------------------------------------- */
/* 6. UNINSTALL — goes in a separate uninstall.php, shown here as reference    */
/* -------------------------------------------------------------------------- */

/*
 * Create wp-content/plugins/rocadev-reading-time/uninstall.php with:
 *
 *   <?php
 *   if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
 *       exit;
 *   }
 *   // Remove the cached word counts from ALL posts in one query.
 *   delete_post_meta_by_key( '_rocadev_rt_word_count' );
 *
 * Goal: leave the database as if the plugin was never installed (21.3c).
 */
