<?php
/**
 * Site footer.
 *
 * @package RocadevLearningClassic
 */
?>
<footer class="site-footer">
	<p>
		<?php
		printf(
			/* translators: %s: Site name. */
			esc_html__( 'Learning with %s.', 'rocadev-learning-classic' ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
	</p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
