<?php
/**
 * Example: Custom Fields via a Metabox (the manual alternative to ACF)
 * --------------------------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 20
 *          (Theme Customization > Custom Fields / Metabox / ACF).
 *
 * Real-life use case:
 *   A "Case Study" needs an extra field the editor fills in: "Client name".
 *   You want a clean labelled input on the edit screen (not the raw built-in
 *   Custom Fields panel), and you want to print that value in the template.
 *
 * Why this matters:
 *   - "Custom fields" = post meta (key/value rows in wp_postmeta).
 *   - A "metabox" is just the UI box on the edit screen that reads/writes meta.
 *   - ACF (Advanced Custom Fields) and Metabox.io are plugins that GENERATE this
 *     same UI + storage for you, with no code. This file shows what they do under
 *     the hood so you understand the security steps they handle: nonce, capability
 *     check, sanitize on save, escape on output.
 *
 * How to use:
 *   Copy into a plugin or functions.php. Pairs with register-custom-post-type.php
 *   (the 'case_study' CPT), but works on any post type listed in add_meta_box().
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

const ROCADEV_CLIENT_META_KEY = '_rocadev_client_name';

/* -------------------------------------------------------------------------- */
/* 1. RENDER the metabox UI on the edit screen                                 */
/* -------------------------------------------------------------------------- */

add_action( 'add_meta_boxes', 'rocadev_add_client_metabox' );
function rocadev_add_client_metabox() {
	add_meta_box(
		'rocadev_client_box',          // box id
		'Case Study Details',          // title shown on the edit screen
		'rocadev_render_client_box',   // render callback
		'case_study',                  // post type (change to 'post'/'page' to reuse)
		'side',                        // context: normal | side | advanced
		'default'                      // priority
	);
}

/**
 * Print the metabox fields. Receives the current post object.
 *
 * @param WP_Post $post The post being edited.
 */
function rocadev_render_client_box( $post ) {
	// A nonce proves the save request really came from this form (CSRF protection).
	wp_nonce_field( 'rocadev_save_client', 'rocadev_client_nonce' );

	$value = get_post_meta( $post->ID, ROCADEV_CLIENT_META_KEY, true );
	?>
	<p>
		<label for="rocadev_client_name"><strong>Client name</strong></label>
		<input
			type="text"
			id="rocadev_client_name"
			name="rocadev_client_name"
			value="<?php echo esc_attr( $value ); ?>"
			class="widefat"
		/>
	</p>
	<?php
}

/* -------------------------------------------------------------------------- */
/* 2. SAVE the value (validate first, then sanitize, then store)               */
/* -------------------------------------------------------------------------- */

add_action( 'save_post', 'rocadev_save_client_meta' );
function rocadev_save_client_meta( $post_id ) {
	// 2a. Ignore autosaves — they aren't a real "Save" click.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 2b. Verify the nonce. Missing/invalid means: do nothing.
	if (
		! isset( $_POST['rocadev_client_nonce'] ) ||
		! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rocadev_client_nonce'] ) ),
			'rocadev_save_client'
		)
	) {
		return;
	}

	// 2c. Capability check — only users allowed to edit THIS post may save.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// 2d. Sanitize input before storing it.
	$client = isset( $_POST['rocadev_client_name'] )
		? sanitize_text_field( wp_unslash( $_POST['rocadev_client_name'] ) )
		: '';

	if ( '' === $client ) {
		delete_post_meta( $post_id, ROCADEV_CLIENT_META_KEY );
	} else {
		update_post_meta( $post_id, ROCADEV_CLIENT_META_KEY, $client );
	}
}

/* -------------------------------------------------------------------------- */
/* 3. READ + OUTPUT the value in a template (escape on the way out)            */
/* -------------------------------------------------------------------------- */

/**
 * Return the client name for a post, ready to print.
 *
 * In a single.php / single-case_study.php template you would do:
 *   echo esc_html( rocadev_get_client_name( get_the_ID() ) );
 *
 * With ACF the same read would be: the_field('client_name');
 *
 * @param int $post_id Post ID.
 * @return string The stored client name (unescaped — caller must escape).
 */
function rocadev_get_client_name( $post_id ) {
	return (string) get_post_meta( $post_id, ROCADEV_CLIENT_META_KEY, true );
}
