<?php
/**
 * Comments, shown only when a post has comments or allows them.
 *
 * @package VilleResiliente
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title mono kicker">
			<?php
			$vr_count = get_comments_number();
			/* translators: %d: number of comments. */
			echo esc_html( sprintf( _n( '%d commentaire', '%d commentaires', $vr_count, 'ville-resiliente' ), $vr_count ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 0,
					'short_ping'  => true,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Laisser un commentaire', 'ville-resiliente' ),
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title mono kicker">',
			'title_reply_after'  => '</h2>',
			'class_submit'       => 'pill solid',
			'label_submit'       => __( 'Publier', 'ville-resiliente' ),
		)
	);
	?>
</section>
