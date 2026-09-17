<?php
/**
 * Core plugin behavior.
 *
 * @package RocadevSiteNotes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the learning-note content type and maintenance event.
 */
final class Rocadev_Site_Notes {

	const POST_TYPE  = 'rocadev_note';
	const CRON_HOOK = 'rocadev_site_notes_weekly_cleanup';

	/**
	 * Registers runtime hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( self::CRON_HOOK, array( __CLASS__, 'delete_old_auto_drafts' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'add_updated_column' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_updated_column' ), 10, 2 );
	}

	/**
	 * Performs one-time activation setup.
	 *
	 * @return void
	 */
	public static function activate() {
		self::register_post_type();

		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'weekly', self::CRON_HOOK );
		}
	}

	/**
	 * Removes temporary scheduled behavior without deleting notes.
	 *
	 * @return void
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}

	/**
	 * Registers an admin-only post type.
	 *
	 * @return void
	 */
	public static function register_post_type() {
		$labels = array(
			'name'          => __( 'Site Notes', 'rocadev-site-notes' ),
			'singular_name' => __( 'Site Note', 'rocadev-site-notes' ),
			'add_new_item'  => __( 'Add Site Note', 'rocadev-site-notes' ),
			'edit_item'     => __( 'Edit Site Note', 'rocadev-site-notes' ),
			'search_items'  => __( 'Search Site Notes', 'rocadev-site-notes' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => $labels,
				'description'         => __( 'Private development and maintenance notes.', 'rocadev-site-notes' ),
				'public'              => false,
				'show_ui'             => true,
				'show_in_rest'        => false,
				'menu_icon'           => 'dashicons-welcome-write-blog',
				'supports'            => array( 'title', 'editor', 'revisions' ),
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'rewrite'             => false,
			)
		);
	}

	/**
	 * Adds a useful list-table column.
	 *
	 * @param array $columns Existing list-table columns.
	 * @return array
	 */
	public static function add_updated_column( $columns ) {
		$columns['rocadev_updated'] = __( 'Last updated', 'rocadev-site-notes' );

		return $columns;
	}

	/**
	 * Renders the last-updated value.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public static function render_updated_column( $column, $post_id ) {
		if ( 'rocadev_updated' !== $column ) {
			return;
		}

		echo esc_html( get_the_modified_date( '', $post_id ) );
	}

	/**
	 * Removes abandoned auto-drafts in a bounded batch.
	 *
	 * @return void
	 */
	public static function delete_old_auto_drafts() {
		$drafts = get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => 'auto-draft',
				'date_query'     => array(
					array(
						'before' => '30 days ago',
					),
				),
				'fields'         => 'ids',
				'posts_per_page' => 50,
				'no_found_rows'  => true,
			)
		);

		foreach ( $drafts as $post_id ) {
			wp_delete_post( $post_id, true );
		}
	}
}
