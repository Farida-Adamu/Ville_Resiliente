<?php
/**
 * Homepage: hero, featured story ("À la une"), story grid ("Autres récits").
 * The featured story is the most recent sticky post, or the latest post.
 *
 * @package VilleResiliente
 */

if ( is_paged() ) {
	require get_template_directory() . '/index.php';
	return;
}

get_header();

$vr_grid_count = VR_HOME_COUNT - 1;
$vr_sticky     = array_filter( array_map( 'intval', (array) get_option( 'sticky_posts' ) ) );

$vr_featured = null;
if ( $vr_sticky ) {
	$vr_q = new WP_Query(
		array(
			'post__in'            => $vr_sticky,
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	$vr_featured = $vr_q->posts ? $vr_q->posts[0] : null;
}
if ( ! $vr_featured ) {
	$vr_q = new WP_Query(
		array(
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	$vr_featured = $vr_q->posts ? $vr_q->posts[0] : null;
}

$vr_grid = new WP_Query(
	array(
		'posts_per_page'      => $vr_grid_count,
		'ignore_sticky_posts' => true,
		'post__not_in'        => $vr_featured ? array( $vr_featured->ID ) : array(),
	)
);

$vr_title = vr_opt( 'vr_hero_title' );
$vr_title = $vr_title ? $vr_title : get_bloginfo( 'name' );
$vr_sub   = vr_opt( 'vr_hero_subtitle' );
?>

<section class="hero">
	<h1><?php echo esc_html( $vr_title ); ?></h1>
	<?php if ( $vr_sub ) : ?>
		<p class="sub"><?php echo esc_html( $vr_sub ); ?></p>
	<?php endif; ?>
</section>

<?php if ( $vr_featured ) : ?>
	<?php
	$post = $vr_featured; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $post );
	$vr_cat   = vr_primary_category();
	$vr_photo   = has_post_thumbnail();
	$vr_drawing = vr_thumbnail_is_drawing();
	?>
	<section class="featured">
		<div class="kicker-row">
			<span class="mono kicker"><?php esc_html_e( 'À la une', 'ville-resiliente' ); ?></span>
		</div>
		<div class="grid">
			<a class="panel card-hover<?php echo $vr_photo ? ' has-photo' : ''; ?><?php echo $vr_drawing ? ' is-drawing' : ''; ?>" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( 'Lire le récit : %s', get_the_title() ) ); ?>">
				<?php if ( $vr_cat && ( ! $vr_photo || $vr_drawing ) ) : ?>
					<span class="corner mono" style="color: var(--ink);"><?php echo esc_html( $vr_cat->name ); ?></span>
				<?php endif; ?>
				<?php if ( $vr_photo ) : ?>
					<?php the_post_thumbnail( $vr_drawing ? 'large' : 'vr-lead', array( 'alt' => '' ) ); ?>
				<?php endif; ?>
			</a>
			<div>
				<?php if ( $vr_cat ) : ?>
					<span class="mono cat-tag"><?php echo esc_html( $vr_cat->name ); ?></span>
				<?php endif; ?>
				<h2><?php the_title(); ?></h2>
				<p class="excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
				<div class="cta-row">
					<a href="<?php the_permalink(); ?>" class="pill solid"><?php esc_html_e( 'Lire le récit', 'ville-resiliente' ); ?></a>
					<span class="mono"><?php echo esc_html( vr_date() . ' · ' . vr_read_time() . ' min' ); ?></span>
				</div>
			</div>
		</div>
	</section>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php if ( $vr_grid->have_posts() ) : ?>
	<section class="stories">
		<div class="kicker-row">
			<span class="mono kicker"><?php esc_html_e( 'Autres récits', 'ville-resiliente' ); ?></span>
		</div>
		<div class="grid">
			<?php
			$vr_i = 0;
			while ( $vr_grid->have_posts() ) :
				$vr_grid->the_post();
				get_template_part( 'template-parts/card', null, array( 'index' => $vr_i ) );
				$vr_i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<?php
		$vr_more_url = '';
		if ( get_option( 'page_for_posts' ) ) {
			$vr_more_url = vr_blog_url();
		} elseif ( 'posts' === get_option( 'show_on_front' ) ) {
			$vr_more_url = get_pagenum_link( 2 );
		}
		?>
		<?php if ( $vr_more_url && $vr_grid->found_posts > $vr_grid_count ) : ?>
			<div class="all-link">
				<a class="pill" href="<?php echo esc_url( $vr_more_url ); ?>"><?php esc_html_e( 'Tous les récits', 'ville-resiliente' ); ?> <span aria-hidden="true">→</span></a>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php if ( ! $vr_featured ) : ?>
	<p class="empty-state"><?php esc_html_e( 'Aucun récit publié pour le moment.', 'ville-resiliente' ); ?></p>
<?php endif; ?>

<?php
get_footer();
