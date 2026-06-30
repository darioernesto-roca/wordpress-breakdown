<?php
/**
 * Example: Classic Theme Setup (theme supports, menus, widgets, customizer)
 * ------------------------------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 20 (Classic Themes).
 *
 * Real-life use case:
 *   You are building (or maintaining) a classic PHP theme for a small business.
 *   You need: a primary navigation menu the client can edit under Appearance >
 *   Menus, a footer widget area, a logo + featured-image support, and one custom
 *   Customizer setting (a phone number) that the client can change live with a
 *   preview.
 *
 * How to use:
 *   Copy the functions you need into your theme's functions.php (child theme
 *   recommended). The template-tag snippet near the bottom shows how header.php /
 *   footer.php would actually OUTPUT these registered features.
 *
 * Covers, from section 20:
 *   - functions.php "theme setup" (add_theme_support)
 *   - Menus      (register_nav_menus + wp_nav_menu)
 *   - Widgets    (register_sidebar + dynamic_sidebar)
 *   - Customizer (Customizer API: setting + control + sanitize + selective refresh)
 *   - Template Tags (the_custom_logo, wp_nav_menu, dynamic_sidebar, etc.)
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* -------------------------------------------------------------------------- */
/* 1. THEME SUPPORTS + MENU + WIDGET REGISTRATION (the "theme setup" step)     */
/* -------------------------------------------------------------------------- */

/**
 * Declare theme features. Runs on 'after_setup_theme'.
 * This is where a classic theme opts in to core capabilities.
 */
add_action( 'after_setup_theme', 'rocadev_theme_setup' );
function rocadev_theme_setup() {
	// Let WordPress emit the <title> tag instead of hardcoding it in header.php.
	add_theme_support( 'title-tag' );

	// Enable featured images (post thumbnails).
	add_theme_support( 'post-thumbnails' );

	// Enable a customizable logo (outputs via the_custom_logo()).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Let WordPress generate semantic HTML5 markup for these elements.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' )
	);

	// Register navigation menu locations the client can fill under Appearance > Menus.
	register_nav_menus(
		array(
			'primary' => 'Primary Menu',
			'footer'  => 'Footer Menu',
		)
	);
}

/**
 * Register a footer widget area (a "sidebar", in WordPress terms).
 * Runs on 'widgets_init'.
 */
add_action( 'widgets_init', 'rocadev_register_widget_areas' );
function rocadev_register_widget_areas() {
	register_sidebar(
		array(
			'name'          => 'Footer',
			'id'            => 'footer-1',
			'description'   => 'Widgets shown in the site footer.',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

/* -------------------------------------------------------------------------- */
/* 2. CUSTOMIZER: a live-editable "phone number" setting                       */
/* -------------------------------------------------------------------------- */

/**
 * Add a Customizer section + setting + control.
 * The client edits this under Appearance > Customize with a live preview.
 */
add_action( 'customize_register', 'rocadev_customize_register' );
function rocadev_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'rocadev_contact',
		array(
			'title'    => 'Contact Info',
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'rocadev_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field', // ALWAYS sanitize Customizer input.
			'transport'         => 'postMessage',          // enables selective refresh (no full reload).
		)
	);

	$wp_customize->add_control(
		'rocadev_phone',
		array(
			'label'   => 'Phone number',
			'section' => 'rocadev_contact',
			'type'    => 'text',
		)
	);

	// Selective refresh: update only the phone element in the preview.
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'rocadev_phone',
			array(
				'selector'        => '.site-phone',
				'render_callback' => 'rocadev_render_phone',
			)
		);
	}
}

/**
 * Output the phone number. Used both by the template and by selective refresh.
 *
 * @return string Escaped phone markup, or empty string if unset.
 */
function rocadev_render_phone() {
	$phone = get_theme_mod( 'rocadev_phone', '' );
	if ( '' === $phone ) {
		return '';
	}
	return '<a class="site-phone" href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a>';
}

/* -------------------------------------------------------------------------- */
/* 3. TEMPLATE TAGS: how header.php / footer.php would USE the above           */
/* -------------------------------------------------------------------------- */

/**
 * The snippet below is illustrative theme-template code. In a real theme this
 * would live inside header.php and footer.php, NOT inside functions.php.
 * It is wrapped in a comment so this example file stays safe to load anywhere.
 */
/*
// --- header.php ---
<header class="site-header">
	<?php
	// Template tag: prints the custom logo registered above.
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}

	// Template tag: prints the "primary" menu the client built under Appearance > Menus.
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_class'     => 'primary-menu',
			'container'      => 'nav',
		)
	);

	// Customizer value (already escaped inside the render callback).
	echo wp_kses_post( rocadev_render_phone() );
	?>
</header>

// --- footer.php ---
<footer class="site-footer">
	<?php
	// Template tag: prints whatever widgets the client dropped into "Footer".
	if ( is_active_sidebar( 'footer-1' ) ) {
		dynamic_sidebar( 'footer-1' );
	}
	?>
</footer>
*/
