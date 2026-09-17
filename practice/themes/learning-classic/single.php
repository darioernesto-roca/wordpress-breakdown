<?php
/**
 * Single post template.
 *
 * @package RocadevLearningClassic
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h1><?php the_title(); ?></h1>
				<p><?php echo esc_html( get_the_date() ); ?></p>
			</header>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
