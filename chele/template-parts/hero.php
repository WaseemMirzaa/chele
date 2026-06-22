<?php
/**
 * Homepage hero.
 *
 * @package Chele
 */

$shop_url   = get_post_type_archive_link( 'chele_product' );
$shop_url   = $shop_url ? $shop_url : home_url( '/' );
$cta_link   = chele_option( 'hero_cta_link' ) ? chele_option( 'hero_cta_link' ) : $shop_url;
$cta2_link  = chele_option( 'hero_cta2_link' );
$about_page = chele_get_page_by_title( 'About' );
if ( ! $cta2_link ) {
	$cta2_link = $about_page ? get_permalink( $about_page ) : '#story';
}
?>
<section class="hero" id="hero">
	<span class="hero-orn hero-orn--1" aria-hidden="true"></span>
	<span class="hero-orn hero-orn--2" aria-hidden="true"></span>

	<div class="hero-inner">

		<div class="hero-copy reveal">
			<p class="eyebrow hero-eyebrow"><?php echo esc_html( chele_option( 'hero_eyebrow', __( 'Elegance. Tradition. You.', 'chele' ) ) ); ?></p>

			<h1 class="hero-title">
				<span class="hero-brand">Chelé</span>
				<span class="hero-sub-line"><?php echo esc_html( chele_option( 'hero_title', __( 'Ladies & Girls Dresses of Pakistan', 'chele' ) ) ); ?></span>
			</h1>

			<p class="hero-text"><?php echo esc_html( chele_option( 'hero_subtitle', __( 'Celebrating the beauty of Pakistani fashion with timeless designs, premium fabrics and flawless details.', 'chele' ) ) ); ?></p>

			<div class="hero-actions">
				<a class="btn btn--primary" href="<?php echo esc_url( $cta_link ); ?>">
					<?php echo esc_html( chele_option( 'hero_cta_text', __( 'Shop the Collection', 'chele' ) ) ); ?>
					<?php chele_icon( 'arrow', 18 ); ?>
				</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $cta2_link ); ?>">
					<?php echo esc_html( chele_option( 'hero_cta2_text', __( 'Our Story', 'chele' ) ) ); ?>
				</a>
			</div>

			<ul class="hero-meta">
				<li><span><?php esc_html_e( 'Premium Fabrics', 'chele' ); ?></span></li>
				<li><span><?php esc_html_e( 'Hand-Finished', 'chele' ); ?></span></li>
				<li><span><?php esc_html_e( 'Made in Pakistan', 'chele' ); ?></span></li>
			</ul>
		</div>

		<div class="hero-visual reveal" data-reveal-delay="120">
			<div class="hero-frame">
				<?php echo chele_placeholder_svg( 2, __( 'Signature Edit', 'chele' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="hero-badge">
					<span class="hero-badge-script">since</span>
					<span class="hero-badge-year">2024</span>
				</div>
			</div>
			<div class="hero-caption">
				<span class="hero-caption-line"></span>
				<span><?php esc_html_e( 'The Festive Collection', 'chele' ); ?></span>
			</div>
		</div>

	</div>

	<a href="#marquee" class="hero-scroll" aria-label="<?php esc_attr_e( 'Scroll down', 'chele' ); ?>">
		<span class="hero-scroll-line"></span>
	</a>
</section>
