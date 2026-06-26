<?php
/**
 * Example: Shortcodes
 * -------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 7 (Shortcodes).
 *
 * Real-life use case:
 *   A content editor wants to insert dynamic bits into a post/page without code:
 *     - The current year in a footer copyright line:  [current_year]
 *     - A styled button anywhere in the content:      [button url="/contact" label="Contact us"]
 *
 * How to use:
 *   Copy these functions into functions.php or a plugin.
 *   Then, in the WordPress editor, the user types the bracket syntax shown above.
 *
 * Key rules for shortcodes:
 *   - A shortcode callback must RETURN its output, never echo it
 *     (echoing prints in the wrong place on the page).
 *   - Always escape output: esc_html(), esc_url(), esc_attr().
 *   - Use shortcode_atts() to merge user attributes with safe defaults.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* [current_year]  ->  2026                                                   */
/* -------------------------------------------------------------------------- */

/**
 * Output the current year. Uses WordPress timezone-aware date functions.
 *
 * Editor usage:  © [current_year] My Company. All rights reserved.
 * Output:        © 2026 My Company. All rights reserved.
 */
add_shortcode( 'current_year', 'rocadev_current_year_shortcode' );
function rocadev_current_year_shortcode() {
	// wp_date() respects the site's configured timezone (better than date()).
	return esc_html( wp_date( 'Y' ) );
}

/* -------------------------------------------------------------------------- */
/* [button url="/contact" label="Contact us"]                                 */
/* -------------------------------------------------------------------------- */

/**
 * Render a styled anchor button with safe, escaped attributes.
 *
 * Editor usage:  [button url="/contact" label="Contact us"]
 * Output:        <a class="btn" href="/contact">Contact us</a>
 *
 * @param array $atts {
 *     @type string $url   Destination URL. Default '#'.
 *     @type string $label Visible button text. Default 'Click here'.
 *     @type string $style Optional extra CSS class. Default ''.
 * }
 */
add_shortcode( 'button', 'rocadev_button_shortcode' );
function rocadev_button_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'url'   => '#',
			'label' => 'Click here',
			'style' => '',
		),
		$atts,
		'button' // shortcode name, enables the shortcode_atts_button filter
	);

	$classes = trim( 'btn ' . $atts['style'] );

	return sprintf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr( $classes ),
		esc_url( $atts['url'] ),
		esc_html( $atts['label'] )
	);
}

/* -------------------------------------------------------------------------- */
/* Enclosing shortcode: [highlight]important text[/highlight]                 */
/* -------------------------------------------------------------------------- */

/**
 * An ENCLOSING shortcode wraps content. Note the second $content parameter.
 *
 * Editor usage:  [highlight]Read this carefully[/highlight]
 * Output:        <mark class="rocadev-highlight">Read this carefully</mark>
 *
 * @param array       $atts    Shortcode attributes (unused here).
 * @param string|null $content The text between the opening and closing tags.
 */
add_shortcode( 'highlight', 'rocadev_highlight_shortcode' );
function rocadev_highlight_shortcode( $atts, $content = null ) {
	if ( null === $content ) {
		return '';
	}

	// do_shortcode() allows nested shortcodes inside the highlighted text.
	return '<mark class="rocadev-highlight">' . do_shortcode( $content ) . '</mark>';
}
