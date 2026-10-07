<?php
/**
 * Static page (À propos, etc.). Uses the article reading column without the drop cap or meta.
 *
 * @package VilleResiliente
 */

get_header();

$vr_wide = isset( $vr_page_wide ) && $vr_page_wide;

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' . ( $vr_wide ? ' page-wide' : '' ) ); ?>>
		<a class="back mono" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Le carnet', 'ville-resiliente' ); ?></a>

		<h1 class="title"><?php the_title(); ?></h1>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php $vr_caption = get_the_post_thumbnail_caption(); ?>
			<figure class="lead<?php echo $vr_caption ? '' : ' no-caption'; ?>">
				<?php the_post_thumbnail( 'vr-lead', array( 'class' => 'lead-img' ) ); ?>
				<?php if ( $vr_caption ) : ?>
					<figcaption class="cap mono"><?php echo esc_html( $vr_caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php else : ?>
			<div style="height: 40px;" aria-hidden="true"></div>
		<?php endif; ?>

		<div class="body no-dropcap">
			<?php the_content(); ?>
			<?php
			wp_link_pages(
				array(
					'before' => '<nav class="pagination"><div class="nav-links">',
					'after'  => '</div></nav>',
				)
			);
			?>
		</div>

		<div class="ornament">
			<?php echo vr_illustration( 'lagune', 220, 'var(--sage-2)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
