<?php
/**
 * Plugin Name:       Site Notes Practice Plugin
 * Description:       Registers private admin-only learning notes for development practice.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Rocadev Learning
 * License:           GPL-2.0-or-later
 * Text Domain:       rocadev-site-notes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROCADEV_SITE_NOTES_VERSION', '1.0.0' );
define( 'ROCADEV_SITE_NOTES_FILE', __FILE__ );

require_once __DIR__ . '/includes/class-site-notes.php';

register_activation_hook( __FILE__, array( 'Rocadev_Site_Notes', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Rocadev_Site_Notes', 'deactivate' ) );

Rocadev_Site_Notes::init();
