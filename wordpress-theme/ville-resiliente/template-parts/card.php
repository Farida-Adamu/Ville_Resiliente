<?php
/**
 * Story card used in "Autres récits" and archive grids. Expects to be called inside the loop.
 *
 * @package VilleResiliente
 *
 * @var array $args { index: int, zero-based position in the grid }
 */

$vr_index = isset( $args['index'] ) ? (int) $args['index'] : 0;
$vr_cat   = vr_primary_category();
$vr_photo = vr_opt( 'vr_card_photos' ) && has_post_thumbnail();
$vr_kind  = vr_illustration_kind( null, $vr_index );
?>
<a class="card card-hover" href="<?php the_permalink(); ?>">
	<span class="panel p-<?php echo esc_attr( ( $vr_index % 3 ) + 1 ); ?><?php echo $vr_photo ? ' has-photo' : ''; ?>">
		<?php if ( $vr_photo ) : ?>
			<?php the_post_thumbnail( 'vr-card', array( 'alt' => '' ) ); ?>
		<?php else : ?>
			<span class="num"><?php echo esc_html( str_pad( (string) ( $vr_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
			<?php echo vr_illustration( $vr_kind, vr_card_illustration_width( $vr_kind ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</span>
	<span class="card-body" style="display:block;">
		<span class="card-meta">
			<span class="mono cat-tag"><?php echo $vr_cat ? esc_html( $vr_cat->name ) : ''; ?></span>
			<span class="mono"><?php echo esc_html( vr_date() ); ?></span>
		</span>
		<h3><?php the_title(); ?></h3>
		<p class="excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<span class="read-link" style="display:block;">
			<span class="mono"><?php esc_html_e( 'Lire', 'ville-resiliente' ); ?> <span aria-hidden="true">→</span></span>
		</span>
	</span>
</a>
