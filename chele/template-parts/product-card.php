<?php
/**
 * Reusable product card. Expects to run inside the loop.
 *
 * @package Chele
 */

$pieces = get_post_meta( get_the_ID(), '_chele_pieces', true );
$terms  = get_the_terms( get_the_ID(), 'chele_collection' );
$collection = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
?>
<article <?php post_class( 'product-card reveal' ); ?>>
	<a class="product-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php chele_the_badge(); ?>
		<div class="product-media-inner">
			<?php chele_product_image(); ?>
		</div>
		<span class="product-quickview">
			<span><?php esc_html_e( 'View', 'chele' ); ?></span>
		</span>
	</a>
	<div class="product-info">
		<?php if ( $collection ) : ?>
			<span class="product-collection"><?php echo esc_html( $collection ); ?></span>
		<?php endif; ?>
		<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $pieces ) : ?>
			<span class="product-pieces"><?php echo esc_html( $pieces ); ?></span>
		<?php endif; ?>
		<?php chele_the_price(); ?>
	</div>
</article>
