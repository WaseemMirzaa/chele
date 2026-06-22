<?php
/**
 * Brand story / about section.
 *
 * @package Chele
 */

$about_page = chele_get_page_by_title( 'About' );
$about_link = $about_page ? get_permalink( $about_page ) : '#';
?>
<section class="story section" id="story">
	<div class="container story-grid">

		<div class="story-visual reveal">
			<div class="story-frame story-frame--back">
				<?php echo chele_placeholder_svg( 5, __( 'The Atelier', 'chele' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="story-frame story-frame--front" data-parallax="0.07">
				<?php echo chele_placeholder_svg( 1, __( 'Hand Work', 'chele' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>

		<div class="story-copy reveal" data-reveal-delay="120">
			<p class="eyebrow"><?php esc_html_e( 'Our Story', 'chele' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Where tradition is', 'chele' ); ?> <span class="script-accent"><?php esc_html_e( 'hand-finished', 'chele' ); ?></span></h2>
			<p class="story-text"><?php esc_html_e( 'Chelé was born in 2024 from a simple belief — that the beauty of Pakistani dressing deserves the very finest fabrics and the most careful hands. Every piece begins with the cloth and ends with a final, hand-finished detail.', 'chele' ); ?></p>
			<p class="story-text"><?php esc_html_e( 'From everyday lawn to heirloom bridals, for ladies and for girls, we design clothing you will reach for, treasure, and pass on.', 'chele' ); ?></p>

			<div class="story-signature">
				<span class="script-accent story-sign"><?php esc_html_e( 'Made with love, worn with pride', 'chele' ); ?></span>
			</div>

			<a class="btn btn--dark" href="<?php echo esc_url( $about_link ); ?>"><?php esc_html_e( 'Discover Chelé', 'chele' ); ?> <?php chele_icon( 'arrow', 18 ); ?></a>
		</div>

	</div>
</section>
