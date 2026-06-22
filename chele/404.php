<?php
/**
 * 404 — page not found.
 *
 * @package Chele
 */

get_header();
?>

<div class="container section error-404">
	<p class="eyebrow"><?php esc_html_e( 'Error 404', 'chele' ); ?></p>
	<h1 class="error-404-title">This page has<br><span class="script-accent">slipped a stitch</span></h1>
	<p class="error-404-text"><?php esc_html_e( 'The page you are looking for cannot be found. Let us guide you back to something beautiful.', 'chele' ); ?></p>
	<div class="error-404-actions">
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'chele' ); ?></a>
		<?php $shop = get_post_type_archive_link( 'chele_product' ); ?>
		<?php if ( $shop ) : ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Shop the Collection', 'chele' ); ?></a>
		<?php endif; ?>
	</div>
	<div class="error-404-search">
		<?php get_search_form(); ?>
	</div>
</div>

<?php
get_footer();
