<?php
/**
 * Site footer. The homepage shows the newsletter form; other pages a link back to the notebook.
 *
 * @package VilleResiliente
 */
?>
		</main>

		<footer class="site<?php echo is_front_page() ? '' : ' article-footer'; ?>">
			<div class="row">
				<div>
					<a class="lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo vr_marque( 33 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="name" style="font-size: 22px;"><?php bloginfo( 'name' ); ?></span>
					</a>
					<?php $vr_tagline = vr_opt( 'vr_footer_tagline' ); ?>
					<?php if ( $vr_tagline ) : ?>
						<p class="tag"><?php echo esc_html( $vr_tagline ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( is_front_page() ) : ?>
					<?php echo vr_newsletter(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<a class="mono" href="<?php echo esc_url( vr_blog_url() ); ?>" style="color: var(--ink);"><?php esc_html_e( 'Retour au carnet', 'ville-resiliente' ); ?> <span aria-hidden="true">→</span></a>
				<?php endif; ?>
			</div>
			<div class="copyright">
				<span class="mono">© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
			</div>
		</footer>

	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
