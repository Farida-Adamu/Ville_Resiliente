<?php
/**
 * Site header.
 *
 * @package VilleResiliente
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'ville-resiliente' ); ?></a>
<div class="paper-tex">
	<div class="wrap">

		<header class="site">
			<a class="lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php echo vr_marque( 30 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="name"><?php bloginfo( 'name' ); ?></span>
			</a>
			<?php $vr_links = vr_nav_links(); ?>
			<?php if ( $vr_links ) : ?>
				<button class="menu-toggle" type="button" aria-controls="menu-principal" aria-expanded="false">
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'ville-resiliente' ); ?></span>
					<svg viewBox="0 0 28 20" width="28" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
						<path class="bar bar-1" d="M1 2 H27"></path>
						<path class="bar bar-2" d="M1 10 H27"></path>
						<path class="bar bar-3" d="M1 18 H27"></path>
					</svg>
				</button>
				<nav class="site" id="menu-principal" aria-label="<?php esc_attr_e( 'Menu principal', 'ville-resiliente' ); ?>">
					<?php foreach ( $vr_links as $vr_link ) : ?>
						<a class="mono<?php echo $vr_link['current'] ? ' current' : ''; ?>" href="<?php echo esc_url( $vr_link['url'] ); ?>"<?php echo $vr_link['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $vr_link['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</header>

		<main id="contenu">
