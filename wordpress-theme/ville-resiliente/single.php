<?php
/**
 * Single article.
 *
 * @package VilleResiliente
 */

get_header();

while ( have_posts() ) :
	the_post();
	$vr_cat = vr_primary_category();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
		<a class="back mono" href="<?php echo esc_url( vr_blog_url() ); ?>"><span aria-hidden="true">←</span> <?php esc_html_e( 'Le carnet', 'ville-resiliente' ); ?></a>

		<?php if ( $vr_cat ) : ?>
			<a class="mono cat-tag" href="<?php echo esc_url( get_category_link( $vr_cat ) ); ?>"><?php echo esc_html( $vr_cat->name ); ?></a>
		<?php endif; ?>
		<h1 class="title"><?php the_title(); ?></h1>
		<div class="meta">
			<span class="mono"><?php echo esc_html( vr_date() ); ?></span>
			<span class="mono" aria-hidden="true">·</span>
			<span class="mono"><?php echo esc_html( vr_read_time() . ' min de lecture' ); ?></span>
			<?php if ( get_the_author() ) : ?>
				<span class="mono" aria-hidden="true">·</span>
				<span class="mono"><?php the_author(); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php $vr_caption = get_the_post_thumbnail_caption(); ?>
			<figure class="lead<?php echo $vr_caption ? '' : ' no-caption'; ?>">
				<?php $vr_drawing = vr_thumbnail_is_drawing(); ?>
				<?php the_post_thumbnail( $vr_drawing ? 'large' : 'vr-lead', array( 'class' => 'lead-img' . ( $vr_drawing ? ' is-drawing' : '' ) ) ); ?>
				<?php if ( $vr_caption ) : ?>
					<figcaption class="cap mono"><?php echo esc_html( $vr_caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php else : ?>
			<div style="height: 48px;" aria-hidden="true"></div>
		<?php endif; ?>

		<div class="body">
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

		<?php $vr_byline = vr_byline(); ?>
		<?php if ( $vr_byline ) : ?>
			<p class="byline"><?php echo esc_html( $vr_byline ); ?></p>
		<?php endif; ?>

		<?php $vr_tags = get_the_tags(); ?>
		<?php if ( $vr_tags ) : ?>
			<div class="tags">
				<?php foreach ( $vr_tags as $vr_tag ) : ?>
					<a class="pill" href="<?php echo esc_url( get_tag_link( $vr_tag ) ); ?>"><?php echo esc_html( $vr_tag->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="ornament">
			<?php echo vr_illustration( 'lagune', 220, 'var(--sage-2)' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<?php
		$vr_prev = get_previous_post();
		$vr_next = get_next_post();
		?>
		<?php if ( $vr_prev || $vr_next ) : ?>
			<nav class="post-nav" aria-label="<?php esc_attr_e( 'Récits voisins', 'ville-resiliente' ); ?>">
				<?php if ( $vr_prev ) : ?>
					<a class="prev mono" href="<?php echo esc_url( get_permalink( $vr_prev ) ); ?>"><span aria-hidden="true">←</span> <?php echo esc_html( get_the_title( $vr_prev ) ); ?></a>
				<?php endif; ?>
				<?php if ( $vr_next ) : ?>
					<a class="next mono" href="<?php echo esc_url( get_permalink( $vr_next ) ); ?>"><?php echo esc_html( get_the_title( $vr_next ) ); ?> <span aria-hidden="true">→</span></a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
