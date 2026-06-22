<?php
/**
 * Shop-by-collection grid.
 *
 * @package Chele
 */

$terms = get_terms(
	array(
		'taxonomy'   => 'chele_collection',
		'hide_empty' => false,
		'number'     => 6,
	)
);

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}
?>
<section class="collections section section--soft">
	<div class="container">
		<header class="section-head section-head--center reveal">
			<p class="eyebrow"><?php esc_html_e( 'Shop by', 'chele' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Our Collections', 'chele' ); ?></h2>
		</header>

		<div class="collection-grid">
			<?php foreach ( $terms as $i => $term ) : ?>
				<a class="collection-card reveal" data-reveal-delay="<?php echo esc_attr( ( $i % 3 ) * 90 ); ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<div class="collection-media">
						<?php echo chele_placeholder_svg( $i + 1, $term->name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="collection-overlay">
						<h3 class="collection-name"><?php echo esc_html( $term->name ); ?></h3>
						<span class="collection-cta"><?php esc_html_e( 'Explore', 'chele' ); ?> <?php chele_icon( 'arrow', 16 ); ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
