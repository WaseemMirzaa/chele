<?php
/**
 * Reusable product card. Expects to run inside the loop.
 *
 * @package Chele
 */

$pieces     = get_post_meta( get_the_ID(), '_chele_pieces', true );
$terms      = get_the_terms( get_the_ID(), 'chele_collection' );
$collection = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

// Decorative colour swatches suggesting available tones (demo affordance).
$swatch_sets = array(
	array( '#c99ba1', '#6e4253', '#b7935e' ),
	array( '#dcc598', '#9a7848', '#3f2230' ),
	array( '#e7d2cf', '#c2cbb4', '#7a3f55' ),
	array( '#b07a86', '#e7cdcb', '#967446' ),
);
$swatches = $swatch_sets[ absint( get_the_ID() ) % count( $swatch_sets ) ];
?>
<article <?php post_class( 'product-card reveal' ); ?>>
	<div class="product-media">
		<?php chele_the_badge(); ?>
		<button class="product-wish" data-wish type="button" aria-label="<?php esc_attr_e( 'Save to wishlist', 'chele' ); ?>" aria-pressed="false">
			<?php chele_icon( 'heart', 18 ); ?>
		</button>

		<a class="product-media-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
			<div class="product-media-inner">
				<?php chele_product_image(); ?>
			</div>
		</a>

		<a class="product-quickadd" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Product', 'chele' ); ?></a>
	</div>

	<div class="product-info">
		<?php if ( $collection ) : ?>
			<span class="product-collection"><?php echo esc_html( $collection ); ?></span>
		<?php endif; ?>
		<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $pieces ) : ?>
			<span class="product-pieces"><?php echo esc_html( $pieces ); ?></span>
		<?php endif; ?>
		<?php chele_the_price(); ?>
		<div class="product-swatches" aria-hidden="true">
			<?php foreach ( $swatches as $color ) : ?>
				<span style="background:<?php echo esc_attr( $color ); ?>"></span>
			<?php endforeach; ?>
		</div>
	</div>
</article>
