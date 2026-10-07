<?php
/**
 * Fallback listing: blog page, category / tag / date / author archives, search results.
 *
 * @package VilleResiliente
 */

get_header();

if ( is_search() ) {
	/* translators: %s: search query. */
	$vr_heading = sprintf( __( 'Recherche : %s', 'ville-resiliente' ), get_search_query() );
	$vr_kicker  = __( 'Résultats', 'ville-resiliente' );
	$vr_desc    = '';
} elseif ( is_category() || is_tag() || is_tax() ) {
	$vr_heading = single_term_title( '', false );
	$vr_kicker  = is_category() ? __( 'Rubrique', 'ville-resiliente' ) : __( 'Mot-clé', 'ville-resiliente' );
	$vr_desc    = wp_strip_all_tags( term_description() );
} elseif ( is_author() ) {
	$vr_heading = get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) );
	$vr_kicker  = __( 'Auteur', 'ville-resiliente' );
	$vr_desc    = get_the_author_meta( 'description', (int) get_query_var( 'author' ) );
} elseif ( is_archive() ) {
	$vr_heading = wp_strip_all_tags( get_the_archive_title() );
	$vr_kicker  = __( 'Archives', 'ville-resiliente' );
	$vr_desc    = '';
} else {
	$vr_heading = __( 'Le carnet', 'ville-resiliente' );
	$vr_kicker  = __( 'Tous les récits', 'ville-resiliente' );
	$vr_desc    = '';
}
?>

<header class="archive-head">
	<span class="mono kicker"><?php echo esc_html( $vr_kicker ); ?></span>
	<h1><?php echo esc_html( $vr_heading ); ?></h1>
	<?php if ( $vr_desc ) : ?>
		<p class="desc"><?php echo esc_html( $vr_desc ); ?></p>
	<?php endif; ?>
</header>

<section class="stories">
	<?php if ( have_posts() ) : ?>
		<div class="grid">
			<?php
			$vr_i = 0;
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card', null, array( 'index' => $vr_i ) );
				$vr_i++;
			endwhile;
			?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => '← ' . __( 'Précédent', 'ville-resiliente' ),
				'next_text' => __( 'Suivant', 'ville-resiliente' ) . ' →',
			)
		);
		?>
	<?php else : ?>
		<p class="empty-state"><?php esc_html_e( 'Aucun récit ne correspond pour le moment.', 'ville-resiliente' ); ?></p>
	<?php endif; ?>
</section>

<?php
get_footer();
