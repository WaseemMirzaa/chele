<?php
/**
 * Editorial lookbook banner — a full-width brand moment.
 *
 * @package Chele
 */

$shop_url = get_post_type_archive_link( class_exists( 'WooCommerce' ) ? 'product' : 'chele_product' );
$shop_url = $shop_url ? $shop_url : home_url( '/' );
?>
<section class="lookbook">
	<div class="lookbook-visual" aria-hidden="true">
		<?php echo chele_placeholder_svg( 3, '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="lookbook-content reveal">
		<p class="eyebrow eyebrow--light"><?php esc_html_e( 'The Festive Edit', 'chele' ); ?></p>
		<h2 class="lookbook-title"><?php esc_html_e( 'Dressed for every', 'chele' ); ?> <span class="script-accent"><?php esc_html_e( 'celebration', 'chele' ); ?></span></h2>
		<p class="lookbook-text"><?php esc_html_e( 'From mehndi mornings to evening soirées — embellished organza, flowing chiffon and heirloom bridals, crafted to be remembered.', 'chele' ); ?></p>
		<a class="btn btn--light" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore the Edit', 'chele' ); ?> <?php chele_icon( 'arrow', 18 ); ?></a>
	</div>
</section>
