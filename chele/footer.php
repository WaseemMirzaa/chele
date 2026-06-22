<?php
/**
 * The footer: motto band, widget columns, social and copyright.
 *
 * @package Chele
 */

?>
	</main><!-- #content -->

	<footer id="colophon" class="site-footer">
		<span class="footer-watermark" aria-hidden="true">Chelé</span>

		<div class="footer-motto">
			<span class="footer-botanical footer-botanical--left" aria-hidden="true">
				<svg viewBox="0 0 120 120" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"><path d="M10 110C30 80 30 50 60 40M30 92c-2-14 6-24 20-26M44 74c-4-14 4-24 18-26M58 58c-2-12 6-22 20-22"/><circle cx="78" cy="36" r="3"/><circle cx="62" cy="32" r="2.4"/></svg>
			</span>

			<p class="footer-motto-eyebrow"><?php esc_html_e( 'For ladies. For girls.', 'chele' ); ?></p>
			<p class="footer-motto-text"><?php echo esc_html( chele_option( 'footer_motto', __( 'Made with love, worn with pride.', 'chele' ) ) ); ?></p>

			<span class="footer-botanical footer-botanical--right" aria-hidden="true">
				<svg viewBox="0 0 120 120" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"><path d="M110 110C90 80 90 50 60 40M90 92c2-14-6-24-20-26M76 74c4-14-4-24-18-26M62 58c2-12-6-22-20-22"/><circle cx="42" cy="36" r="3"/><circle cx="58" cy="32" r="2.4"/></svg>
			</span>
		</div>

		<div class="footer-main">

			<div class="footer-brand">
				<?php chele_wordmark(); ?>
				<p class="footer-about"><?php esc_html_e( 'Chelé celebrates the beauty of Pakistani fashion — timeless designs, premium fabrics and flawless, hand-finished details for ladies and girls.', 'chele' ); ?></p>
				<?php chele_social_links(); ?>
			</div>

			<div class="footer-widgets">
				<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<div class="footer-col">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php elseif ( 1 === $i ) : ?>
						<div class="footer-col">
							<h3 class="widget-title"><?php esc_html_e( 'Shop', 'chele' ); ?></h3>
							<ul>
								<?php
								$terms = get_terms( array( 'taxonomy' => 'chele_collection', 'hide_empty' => false, 'number' => 6 ) );
								if ( ! is_wp_error( $terms ) && $terms ) {
									foreach ( $terms as $term ) {
										printf( '<li><a href="%s">%s</a></li>', esc_url( get_term_link( $term ) ), esc_html( $term->name ) );
									}
								}
								?>
							</ul>
						</div>
					<?php elseif ( 2 === $i ) : ?>
						<div class="footer-col">
							<h3 class="widget-title"><?php esc_html_e( 'House of Chelé', 'chele' ); ?></h3>
							<?php
							if ( has_nav_menu( 'footer' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'depth' => 1 ) );
							} else {
								wp_list_pages( array( 'title_li' => '', 'depth' => 1, 'number' => 5 ) );
							}
							?>
						</div>
					<?php elseif ( 3 === $i ) : ?>
						<div class="footer-col footer-newsletter">
							<h3 class="widget-title"><?php esc_html_e( 'Join the List', 'chele' ); ?></h3>
							<p><?php esc_html_e( 'Be first to see new arrivals, private sales and styling notes.', 'chele' ); ?></p>
							<form class="newsletter-form" action="#" method="post" onsubmit="return false;">
								<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'chele' ); ?>" aria-label="<?php esc_attr_e( 'Your email address', 'chele' ); ?>" required />
								<button type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'chele' ); ?>"><?php chele_icon( 'arrow', 18 ); ?></button>
							</form>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

		</div>

		<div class="footer-bottom">
			<div class="footer-contact">
				<a href="<?php echo esc_url( chele_option( 'instagram', 'https://instagram.com/chele.official' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php chele_icon( 'instagram', 18 ); ?> @chele.official</a>
				<span class="footer-sep" aria-hidden="true">|</span>
				<a href="<?php echo esc_url( chele_option( 'facebook', 'https://facebook.com/cheleofficial' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php chele_icon( 'facebook', 18 ); ?> Chelé Official</a>
				<span class="footer-sep" aria-hidden="true">|</span>
				<a href="<?php echo esc_url( chele_option( 'website', 'https://www.chele.pk' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php chele_icon( 'globe', 18 ); ?> www.chele.pk</a>
			</div>
			<p class="footer-copyright">
				<?php
				$copyright = chele_option( 'footer_copyright', '' );
				if ( $copyright ) {
					echo esc_html( $copyright );
				} else {
					/* translators: %s: year. */
					printf( esc_html__( '© %s Chelé. All rights reserved.', 'chele' ), esc_html( gmdate( 'Y' ) ) );
				}
				?>
			</p>
		</div>

	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
