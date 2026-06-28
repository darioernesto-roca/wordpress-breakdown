<?php
/**
 * Example: WordPress Coding Standards (before & after)
 * ----------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 19 (WordPress Coding Standards).
 *
 * Real-life use case:
 *   You inherit a plugin where every contributor wrote in their own style:
 *   no prefixes, random spacing, unescaped output, no nonce checks. Before a
 *   review you bring it in line with the WordPress Coding Standards so the team
 *   reads code faster and avoids common security bugs.
 *
 * This file shows the SAME small feature twice:
 *   1. "bad_" functions  -> works, but ignores the standards.
 *   2. "rocadev_" functions -> the corrected, standards-compliant version.
 *
 * Both versions are valid PHP (this file passes `php -l`), but only the second
 * follows WordPress conventions. Function names differ so nothing is redeclared.
 *
 * The standards in one line:
 *   tabs for indentation, snake_case, Yoda conditions, prefix everything,
 *   sanitize input, validate input, escape output, verify nonces, document with
 *   DocBlocks. Run phpcs/phpcbf (WordPress ruleset) before committing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/* ========================================================================== */
/* BEFORE — violates the WordPress Coding Standards                           */
/* ========================================================================== */
/*
 * Problems below:
 * - No prefix (collides with other plugins that define greet() / SaveName()).
 * - PascalCase / mixed naming instead of snake_case.
 * - Cramped spacing, missing spaces in parentheses and around operators.
 * - Assignment-style comparison risk ($x == '') instead of Yoda conditions.
 * - Reads $_POST with no sanitizing and no nonce verification.
 * - Echoes user input with NO escaping (XSS risk).
 * - No DocBlocks.
 */

function greet($Name){ // This function is not prefixed, so it could collide with other plugins that define greet(). A prefix means adding a unique identifier to the function name, e.g., rocadev_greet().
if($Name==''){$Name='Guest';} // This is a Yoda condition risk: if ($Name = '') would assign an empty string to $Name instead of comparing it. Use Yoda conditions: if ( '' === $Name ) { ... }. Yoda is a coding style where the constant is on the left side of the comparison, which prevents accidental assignment.
echo '<p>Hello, '.$Name.'</p>';
}

function SaveName(){
$n=$_POST['name'];
update_option('rocadev_demo_name',$n);
echo 'Saved: '.$n;
}


/* ========================================================================== */
/* AFTER — follows the WordPress Coding Standards                             */
/* ========================================================================== */

/**
 * Print a greeting for a given name.
 *
 * Standards applied:
 * - Prefixed, snake_case function name.
 * - Spaces inside parentheses and around operators.
 * - Yoda condition ( '' === $name ) to prevent accidental assignment.
 * - Output escaped with esc_html().
 *
 * @param string $name Visitor name. Defaults to "Guest" when empty.
 * @return void
 */
function rocadev_greet( $name = '' ) {
	if ( '' === $name ) {
		$name = 'Guest';
	}

	echo '<p>Hello, ' . esc_html( $name ) . '</p>';
}

/**
 * Save a submitted name to the options table, safely.
 *
 * Standards applied:
 * - Nonce verification before trusting the request (security).
 * - Capability check so only allowed users can write.
 * - Input sanitized with sanitize_text_field() before storage.
 * - Output escaped with esc_html() when echoed back.
 * - DocBlock describing behavior and return value.
 *
 * @return void
 */
function rocadev_save_name() {
	// 1. Verify the request really came from our form (nonce).
	if (
		! isset( $_POST['rocadev_name_nonce'] )
		|| ! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rocadev_name_nonce'] ) ),
			'rocadev_save_name'
		)
	) {
		return;
	}

	// 2. Only let capable users perform the write action.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// 3. Sanitize INPUT before using it.
	$name = isset( $_POST['name'] )
		? sanitize_text_field( wp_unslash( $_POST['name'] ) )
		: '';

	update_option( 'rocadev_demo_name', $name );

	// 4. Escape OUTPUT when echoing back.
	echo '<p>Saved: ' . esc_html( $name ) . '</p>';
}

/**
 * The matching form, demonstrating wp_nonce_field() and esc_* helpers.
 *
 * @return void
 */
function rocadev_render_name_form() {
	?>
	<form method="post">
		<?php wp_nonce_field( 'rocadev_save_name', 'rocadev_name_nonce' ); ?>
		<label for="rocadev-name">Your name</label>
		<input
			type="text"
			id="rocadev-name"
			name="name"
			value="<?php echo esc_attr( get_option( 'rocadev_demo_name', '' ) ); ?>"
		/>
		<button type="submit">Save</button>
	</form>
	<?php
}
