<?php
/**
 * Example: AJAX "Load More" (admin-ajax.php)
 * ------------------------------------------
 * Maps to: wordpress-breakdown-with-examples.php, section 15.2 (AJAX).
 *
 * Real-life use case:
 *   A blog/archive shows 6 posts and a "Load More" button. Clicking it fetches
 *   the next page of posts and appends them WITHOUT a full page reload.
 *
 * Why classic admin-ajax instead of REST here?
 *   admin-ajax is still perfectly fine for small, theme-bound interactions and
 *   is what many themes/plugins already use. For structured app-like APIs prefer
 *   the REST examples (rest-custom-endpoint.php / rest-custom-search.php).
 *
 * Two hooks are required so it works for BOTH logged-in and logged-out visitors:
 *   wp_ajax_{action}        -> logged-in users
 *   wp_ajax_nopriv_{action} -> logged-out (public) visitors
 *
 * Security: the request carries a nonce; the handler verifies it before querying.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * 1. Enqueue the script and hand it the AJAX URL + a nonce.
 */
add_action( 'wp_enqueue_scripts', 'rocadev_loadmore_assets' );
function rocadev_loadmore_assets() {
	wp_enqueue_script(
		'rocadev-loadmore',
		get_template_directory_uri() . '/assets/js/load-more.js',
		array(),
		'1.0.0',
		true
	);

	wp_localize_script(
		'rocadev-loadmore',
		'rocadevLoadMore',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'rocadev_load_more' ),
		)
	);
}

/**
 * 2. The AJAX handler. 'action' in the request is 'load_more_posts',
 *    so the hooks are wp_ajax_load_more_posts / wp_ajax_nopriv_load_more_posts.
 */
add_action( 'wp_ajax_load_more_posts', 'rocadev_load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'rocadev_load_more_posts' );
function rocadev_load_more_posts() {
	// Verify the nonce; wp_send_json_error() also sets an error HTTP status.
	check_ajax_referer( 'rocadev_load_more', 'nonce' );

	$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

	$query = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'paged'          => $page,
		)
	);

	if ( ! $query->have_posts() ) {
		// No more posts: tell the JS to hide the button.
		wp_send_json_success(
			array(
				'html'     => '',
				'has_more' => false,
			)
		);
	}

	// Build the HTML for the next batch.
	ob_start();
	while ( $query->have_posts() ) :
		$query->the_post();
		?>
		<article class="post-card">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
		</article>
		<?php
	endwhile;
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html'     => $html,
			'has_more' => $page < $query->max_num_pages,
		)
	);
}

/*
 * --------------------------------------------------------------------------
 * Companion file: /assets/js/load-more.js  (reference)
 * --------------------------------------------------------------------------
 * Assumes markup like:
 *   <div id="posts-container">...initial posts...</div>
 *   <button id="load-more" data-page="1">Load More</button>
 *
 * document.addEventListener('DOMContentLoaded', function () {
 *   const button    = document.getElementById('load-more');
 *   const container = document.getElementById('posts-container');
 *   if (!button || !container) return;
 *
 *   button.addEventListener('click', function () {
 *     const nextPage = parseInt(button.dataset.page, 10) + 1;
 *
 *     const body = new FormData();
 *     body.append('action', 'load_more_posts');     // matches the PHP hook
 *     body.append('nonce', rocadevLoadMore.nonce);
 *     body.append('page', nextPage);
 *
 *     button.disabled = true;
 *
 *     fetch(rocadevLoadMore.ajaxUrl, { method: 'POST', body: body })
 *       .then(r => r.json())
 *       .then(function (res) {
 *         if (res.success) {
 *           container.insertAdjacentHTML('beforeend', res.data.html);
 *           button.dataset.page = nextPage;
 *           if (!res.data.has_more) button.style.display = 'none';
 *         }
 *       })
 *       .catch(err => console.error('Load more failed:', err))
 *       .finally(() => { button.disabled = false; });
 *   });
 * });
 */
