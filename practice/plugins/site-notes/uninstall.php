<?php
/**
 * Optional permanent cleanup for Site Notes.
 *
 * @package RocadevSiteNotes
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Learning notes are user content, so uninstall intentionally preserves them.
// A production plugin could offer an explicit, opt-in deletion policy.
