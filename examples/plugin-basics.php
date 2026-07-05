<?php
/**
 * Plugin Name:       Rocadev Maintenance Notice
 * Plugin URI:        https://example.com/plugins/rocadev-maintenance-notice
 * Description:       Shows a dismissible site-wide notice banner (e.g. "Maintenance tonight 22:00"). Demonstrates plugin anatomy: header, lifecycle hooks, Settings API, escaped output.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Rocadev
 * License:           GPL-2.0-or-later
 * Text Domain:       rocadev-maintenance-notice
 *
 * --------------------------------------------------------------------------
 * Example: Plugin Basics — a complete, working single-file plugin
 * --------------------------------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 21 (Plugins Basics).
 *
 * What it does (real-life use case):
 *   Site owner needs to warn visitors about scheduled maintenance. Instead of
 *   editing the theme (lost on theme switch — section 21.5), a tiny plugin adds:
 *     - Settings > Maintenance Notice: enable checkbox + message field.
 *     - A styled banner at the top of every frontend page while enabled.
 *
 * What it demonstrates, in order:
 *   1. Plugin Header (above)            -> what makes this file a plugin (21.2)
 *   2. ABSPATH guard                    -> block direct URL execution (21.6a)
 *   3. Activation / deactivation hooks  -> lifecycle (21.3)
 *   4. Settings API options page        -> register_setting + sanitize (21.6e)
 *   5. Escaped frontend output          -> wp_body_open banner (21.6c)
 *   6. Uninstall cleanup                -> commented uninstall.php (21.3c)
 *
 * How to use:
 *   Copy this file into wp-content/plugins/ (as-is, single file) and activate
 *   it under Plugins. Then open Settings > Maintenance Notice, tick "Enable",
 *   write a message, save, and visit the frontend.
 *
 * Everything global is prefixed rocadev_mnotice_ — rename to your own prefix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return; // 2. Never run outside WordPress.
}

/* -------------------------------------------------------------------------- */
/* 3. LIFECYCLE — activation sets defaults, deactivation leaves data alone     */
/* -------------------------------------------------------------------------- */

register_activation_hook( __FILE__, 'rocadev_mnotice_activate' );
/**
 * Runs ONCE on "Activate". Only setup work belongs here.
 * add_option() (unlike update_option()) does nothing if the option already
 * exists, so re-activating keeps the user's saved settings.
 */
function rocadev_mnotice_activate() {
	add_option(
		'rocadev_mnotice_settings',
		array(
			'enabled' => 0,
			'message' => 'Scheduled maintenance tonight at 22:00. The site may be briefly unavailable.',
		)
	);
}

register_deactivation_hook( __FILE__, 'rocadev_mnotice_deactivate' );
/**
 * Runs ONCE on "Deactivate". Pause, don't destroy: the user may reactivate
 * tomorrow and expects their message back, so we do NOT delete the option here.
 * (Deleting belongs in uninstall — see the bottom of this file.)
 */
function rocadev_mnotice_deactivate() {
	// Nothing to pause in this small plugin. Typical real-world tasks here:
	// wp_clear_scheduled_hook( 'rocadev_mnotice_daily_task' );
	// flush_rewrite_rules(); // if the plugin registered a CPT.
}

/* -------------------------------------------------------------------------- */
/* 4. SETTINGS API — one options page, one array option, one sanitize gate     */
/* -------------------------------------------------------------------------- */

add_action( 'admin_menu', 'rocadev_mnotice_add_options_page' );
function rocadev_mnotice_add_options_page() {
	add_options_page(
		'Maintenance Notice',            // <title> of the settings screen.
		'Maintenance Notice',            // Label under the Settings menu.
		'manage_options',                // Capability required to see it.
		'rocadev-mnotice',               // Page slug.
		'rocadev_mnotice_render_page'    // Callback that prints the page.
	);
}

add_action( 'admin_init', 'rocadev_mnotice_register_settings' );
function rocadev_mnotice_register_settings() {
	/*
	 * register_setting() whitelists our option for the Settings API form and
	 * attaches the sanitize callback — EVERY save passes through it, so this is
	 * the single choke point where input gets cleaned (section 19.3).
	 */
	register_setting(
		'rocadev_mnotice_group',
		'rocadev_mnotice_settings',
		array( 'sanitize_callback' => 'rocadev_mnotice_sanitize' )
	);

	add_settings_section(
		'rocadev_mnotice_main',
		'Banner settings',
		'__return_false',                // No intro text needed for the section.
		'rocadev-mnotice'
	);

	add_settings_field(
		'rocadev_mnotice_enabled',
		'Enable banner',
		'rocadev_mnotice_field_enabled',
		'rocadev-mnotice',
		'rocadev_mnotice_main'
	);

	add_settings_field(
		'rocadev_mnotice_message',
		'Message',
		'rocadev_mnotice_field_message',
		'rocadev-mnotice',
		'rocadev_mnotice_main'
	);
}

/**
 * Sanitize on the way IN (escape happens later, on the way OUT).
 *
 * @param array $input Raw values from the form.
 * @return array Clean values that are safe to store.
 */
function rocadev_mnotice_sanitize( $input ) {
	$clean            = array();
	$clean['enabled'] = empty( $input['enabled'] ) ? 0 : 1;
	$clean['message'] = isset( $input['message'] )
		? sanitize_text_field( $input['message'] )
		: '';
	return $clean;
}

function rocadev_mnotice_field_enabled() {
	$settings = get_option( 'rocadev_mnotice_settings', array() );
	$enabled  = ! empty( $settings['enabled'] );
	?>
	<label>
		<input type="checkbox"
			name="rocadev_mnotice_settings[enabled]"
			value="1" <?php checked( $enabled ); ?> />
		Show the notice on the frontend
	</label>
	<?php
}

function rocadev_mnotice_field_message() {
	$settings = get_option( 'rocadev_mnotice_settings', array() );
	$message  = isset( $settings['message'] ) ? $settings['message'] : '';
	?>
	<input type="text" class="regular-text"
		name="rocadev_mnotice_settings[message]"
		value="<?php echo esc_attr( $message ); ?>" />
	<p class="description">Plain text; shown to every visitor at the top of the site.</p>
	<?php
}

function rocadev_mnotice_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return; // Capability check even though the menu already gated it.
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// settings_fields() prints the nonce + option-group hidden inputs;
			// options.php verifies them before our sanitize callback runs.
			settings_fields( 'rocadev_mnotice_group' );
			do_settings_sections( 'rocadev-mnotice' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------- */
/* 5. FRONTEND OUTPUT — escaped banner via wp_body_open                        */
/* -------------------------------------------------------------------------- */

add_action( 'wp_body_open', 'rocadev_mnotice_render_banner' );
function rocadev_mnotice_render_banner() {
	$settings = get_option( 'rocadev_mnotice_settings', array() );

	if ( empty( $settings['enabled'] ) || empty( $settings['message'] ) ) {
		return; // Do real work only when needed — the hook runs on every page.
	}

	/*
	 * Extension point (21.6g): other code can tweak the final message with
	 * add_filter( 'rocadev_mnotice_message', ... ) without editing this plugin.
	 */
	$message = apply_filters( 'rocadev_mnotice_message', $settings['message'] );

	// Escape on the way OUT, at the exact moment of output.
	echo '<div class="rocadev-mnotice" role="status">'
		. esc_html( $message )
		. '</div>';
}

add_action( 'wp_enqueue_scripts', 'rocadev_mnotice_styles' );
function rocadev_mnotice_styles() {
	$settings = get_option( 'rocadev_mnotice_settings', array() );
	if ( empty( $settings['enabled'] ) ) {
		return;
	}

	/*
	 * A few CSS rules don't justify shipping a .css file, but they must still go
	 * through the enqueue system (section 8): register an empty handle and
	 * attach inline styles to it. For real stylesheets use wp_enqueue_style()
	 * with plugin_dir_url( __FILE__ ) . 'public/banner.css'.
	 */
	wp_register_style( 'rocadev-mnotice', false, array(), '1.0.0' );
	wp_enqueue_style( 'rocadev-mnotice' );
	wp_add_inline_style(
		'rocadev-mnotice',
		'.rocadev-mnotice{background:#b32d2e;color:#fff;text-align:center;padding:.75em 1em;font-size:.95rem;}'
	);
}

/* -------------------------------------------------------------------------- */
/* 6. UNINSTALL — goes in a separate uninstall.php, shown here as reference    */
/* -------------------------------------------------------------------------- */

/*
 * Create wp-content/plugins/rocadev-maintenance-notice/uninstall.php with:
 *
 *   <?php
 *   // WordPress loads this file only when the plugin is DELETED from the
 *   // Plugins screen (not on deactivate). The constant guard makes sure it
 *   // can't be triggered any other way.
 *   if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
 *       exit;
 *   }
 *   delete_option( 'rocadev_mnotice_settings' );
 *
 * Goal: leave the database as if the plugin was never installed.
 */
