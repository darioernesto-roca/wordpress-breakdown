<?php
/**
 * Example: Custom REST Search Endpoint (+ frontend JS)
 * ----------------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, sections 18.21 & 18.22.
 *
 * Real-life use case:
 *   A live "search as you type" box. Instead of reloading a full WordPress
 *   search page, JavaScript hits a lightweight REST endpoint and gets back a
 *   small JSON list of matching posts/pages to render in a dropdown.
 *
 *     GET /wp-json/rocadev/v1/search?term=seo
 *
 * How to use:
 *   1. Load this file in a plugin/functions.php (registers the endpoint).
 *   2. Enqueue the JS at the bottom (see examples/enqueue-styles-and-scripts.php),
 *      or paste the markup+script into a template while testing.
 *
 * Security notes:
 *   - 'term' is required and sanitized with sanitize_text_field.
 *   - Reading published content is public, so __return_true is acceptable here.
 *   - Output that gets rendered into the DOM is set via textContent (JS), not
 *     innerHTML, to avoid injecting markup from titles.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register the /search endpoint.
 */
add_action( 'rest_api_init', 'rocadev_register_search_endpoint' );
function rocadev_register_search_endpoint() {
	register_rest_route(
		'rocadev/v1',
		'/search',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'rocadev_rest_search',
			'permission_callback' => '__return_true',
			'args'                => array(
				'term' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}

/**
 * Search posts + pages and return a slim JSON array.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response
 */
function rocadev_rest_search( WP_REST_Request $request ) {
	$term = $request->get_param( 'term' );

	$query = new WP_Query(
		array(
			'post_type'      => array( 'post', 'page' ),
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			's'              => $term,
			'no_found_rows'  => true,
		)
	);

	$results = array();

	foreach ( $query->posts as $post ) {
		$results[] = array(
			'title' => get_the_title( $post ),
			'url'   => get_permalink( $post ),
			'type'  => get_post_type( $post ),
		);
	}

	return rest_ensure_response( $results );
}

/*
 * --------------------------------------------------------------------------
 * Frontend markup + JavaScript (reference)
 * --------------------------------------------------------------------------
 * Put the markup in a template and load this JS as a real file. It debounces
 * input and renders results safely with textContent.
 *
 * <input type="search" id="site-search" placeholder="Search..." autocomplete="off" />
 * <div id="search-results"></div>
 *
 * <script>
 * (function () {
 *   const input = document.getElementById('site-search');
 *   const box   = document.getElementById('search-results');
 *   let timer;
 *
 *   input.addEventListener('input', function () {
 *     clearTimeout(timer);
 *     const term = input.value.trim();
 *
 *     if (term.length < 3) { box.innerHTML = ''; return; }
 *
 *     // Debounce: wait 250ms after typing stops before calling the API.
 *     timer = setTimeout(function () {
 *       fetch('/wp-json/rocadev/v1/search?term=' + encodeURIComponent(term))
 *         .then(r => r.json())
 *         .then(function (results) {
 *           box.innerHTML = '';
 *           results.forEach(function (item) {
 *             const a = document.createElement('a');
 *             a.href = item.url;
 *             a.textContent = item.title;   // safe: no HTML injection
 *             box.appendChild(a);
 *             box.appendChild(document.createElement('br'));
 *           });
 *         })
 *         .catch(err => console.error('Search failed:', err));
 *     }, 250);
 *   });
 * })();
 * </script>
 */
