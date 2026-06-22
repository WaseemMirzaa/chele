<?php
/**
 * Newsletter call-to-action band.
 *
 * @package Chele
 */

?>
<section class="newsletter-cta">
	<div class="container">
		<div class="newsletter-card reveal">
			<span class="newsletter-orn" aria-hidden="true">✦</span>
			<p class="eyebrow"><?php esc_html_e( 'Join the House of Chelé', 'chele' ); ?></p>
			<h2 class="newsletter-title"><?php esc_html_e( 'Be first to know', 'chele' ); ?></h2>
			<p class="newsletter-text"><?php esc_html_e( 'New arrivals, private previews and styling notes — delivered with love. No noise, only the beautiful.', 'chele' ); ?></p>
			<form class="newsletter-form newsletter-form--lg" action="#" method="post" onsubmit="return false;">
				<input type="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'chele' ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'chele' ); ?>" required />
				<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Subscribe', 'chele' ); ?></button>
			</form>
			<p class="newsletter-fineprint"><?php esc_html_e( 'By subscribing you agree to receive Chelé emails. Unsubscribe anytime.', 'chele' ); ?></p>
		</div>
	</div>
</section>
