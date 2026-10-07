<?php
/**
 * Page not found.
 *
 * @package VilleResiliente
 */

get_header();
?>

<section class="article">
	<span class="mono cat-tag">404</span>
	<h1 class="title"><?php esc_html_e( 'Cette page a pris la lagune.', 'ville-resiliente' ); ?></h1>
	<div class="body no-dropcap" style="margin-top: 28px;">
		<p><?php esc_html_e( "Le lien est peut-être ancien, ou le récit a changé d'adresse.", 'ville-resiliente' ); ?></p>
	</div>
	<div class="cta-row" style="margin-top: 12px;">
		<a class="pill solid" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour au carnet', 'ville-resiliente' ); ?> →</a>
	</div>
	<div class="ornament">
		<?php echo vr_illustration( 'lagune', 220, 'var(--sage-2)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>

<?php
get_footer();
