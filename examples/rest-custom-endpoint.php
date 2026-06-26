<?php
/**
 * Example: Custom REST API Endpoint
 * ---------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, sections 18.7 & 18.8.
 *
 * Real-life use case:
 *   The default /wp/v2/case_study response returns a LOT of fields. A headless
 *   frontend (React/Next.js) only needs id, title, excerpt, permalink, image.
 *   So you expose a slim, purpose-built endpoint:
 *
 *     GET /wp-json/rocadev/v1/case-studies
 *     GET /wp-json/rocadev/v1/case-studies?industry=healthcare   (filtered)
 *
 * How to use:
 *   Load this in a plugin/functions.php. Then open the URL in a browser or
 *   Postman. Requires the 'case_study' CPT (examples/register-custom-post-type.php)
 *   and the 'industry' taxonomy (examples/register-taxonomy.php).
 *
 * Two endpoints are registered:
 *   1. /case-studies                 -> latest published case studies.
 *   2. /case-studies/(?P<id>\d+)     -> a single case study by ID (path arg).
 *   The optional ?industry= param filters #1 via a tax_query.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register the routes. Must run on 'rest_api_init'.
 */
add_action( 'rest_api_init', 'rocadev_register_case_study_routes' );
function rocadev_register_case_study_routes() {
	// Collection: list (optionally filtered by ?industry=slug).
	register_rest_route(
		'rocadev/v1',
		'/case-studies',
		array(
			'methods'             => WP_REST_Server::READABLE, // 'GET'
			'callback'            => 'rocadev_get_case_studies',
			'permission_callback' => '__return_true',          // public data
			'args'                => array(
				'industry' => array(
					'required'          => false,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'per_page' => array(
					'required'          => false,
					'type'              => 'integer',
					'default'           => 10,
					'sanitize_callback' => 'absint',
				),
			),
		)
	);

	// Single item by numeric ID in the URL path.
	register_rest_route(
		'rocadev/v1',
		'/case-studies/(?P<id>\d+)',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'rocadev_get_single_case_study',
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array(
					'validate_callback' => function ( $value ) {
						return is_numeric( $value );
					},
				),
			),
		)
	);
}

/**
 * Callback: return a slim list of case studies.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response
 */
function rocadev_get_case_studies( WP_REST_Request $request ) {
	$industry = $request->get_param( 'industry' );
	$per_page = $request->get_param( 'per_page' );

	$args = array(
		'post_type'      => 'case_study',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page ? $per_page : 10,
		'no_found_rows'  => true,
	);

	if ( ! empty( $industry ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'industry',
				'field'    => 'slug',
				'terms'    => $industry,
			),
		);
	}

	$query   = new WP_Query( $args );
	$results = array();

	foreach ( $query->posts as $post ) {
		$results[] = rocadev_format_case_study( $post->ID );
	}

	return rest_ensure_response( $results );
}

/**
 * Callback: return a single case study, or a 404 error if missing.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function rocadev_get_single_case_study( WP_REST_Request $request ) {
	$id   = (int) $request->get_param( 'id' );
	$post = get_post( $id );

	if ( ! $post || 'case_study' !== $post->post_type || 'publish' !== $post->post_status ) {
		return new WP_Error(
			'rocadev_not_found',
			'Case study not found.',
			array( 'status' => 404 )
		);
	}

	return rest_ensure_response( rocadev_format_case_study( $id ) );
}

/**
 * Shared formatter so both endpoints return an identical shape.
 *
 * @param int $post_id Case study ID.
 * @return array
 */
function rocadev_format_case_study( $post_id ) {
	return array(
		'id'        => $post_id,
		'title'     => get_the_title( $post_id ),
		'excerpt'   => get_the_excerpt( $post_id ),
		'permalink' => get_permalink( $post_id ),
		'image'     => get_the_post_thumbnail_url( $post_id, 'medium' ) ?: '',
		'industries' => wp_get_post_terms( $post_id, 'industry', array( 'fields' => 'names' ) ),
	);
}
