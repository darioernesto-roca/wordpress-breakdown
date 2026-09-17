<?php
/**
 * Site header.
 *
 * @package RocadevLearningClassic
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'rocadev-learning-classic' ); ?></a>
<header class="site-header">
	<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => 'nav',
			'container_aria_label' => __( 'Primary navigation', 'rocadev-learning-classic' ),
			'fallback_cb'    => false,
		)
	);
	?>
</header>
