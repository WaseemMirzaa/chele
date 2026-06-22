<?php
/**
 * Single product page.
 *
 * @package Chele
 */

get_header();

while ( have_posts() ) :
	the_post();

	$price    = get_post_meta( get_the_ID(), '_chele_price', true );
	$currency = get_post_meta( get_the_ID(), '_chele_currency', true );
	$currency = $currency ? $currency : 'PKR';
	$pieces   = get_post_meta( get_the_ID(), '_chele_pieces', true );
	$fabric   = get_post_meta( get_the_ID(), '_chele_fabric', true );
	$terms    = get_the_terms( get_the_ID(), 'chele_collection' );
	$collection = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;

	$whatsapp = chele_option( 'whatsapp', '' );
	$wa_text  = rawurlencode( sprintf( /* translators: %s product name */ __( 'Hi Chelé! I would love to order: %s', 'chele' ), get_the_title() ) );
	$enquire  = $whatsapp ? trailingslashit( $whatsapp ) . '?text=' . $wa_text : 'mailto:hello@chele.pk?subject=' . rawurlencode( get_the_title() );
	?>

	<div class="container section">
		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'chele' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'chele' ); ?></a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'chele_product' ) ); ?>"><?php esc_html_e( 'Shop', 'chele' ); ?></a>
			<?php if ( $collection ) : ?>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_term_link( $collection ) ); ?>"><?php echo esc_html( $collection->name ); ?></a>
			<?php endif; ?>
		</nav>

		<article <?php post_class( 'product-single' ); ?>>

			<div class="product-single-media">
				<div class="product-single-image reveal">
					<?php chele_product_image( get_the_ID(), 'chele-portrait' ); ?>
				</div>
			</div>

			<div class="product-single-summary reveal" data-reveal-delay="100">
				<?php if ( $collection ) : ?>
					<span class="product-collection"><?php echo esc_html( $collection->name ); ?></span>
				<?php endif; ?>

				<h1 class="product-single-title"><?php the_title(); ?></h1>

				<?php if ( '' !== $price ) : ?>
					<div class="product-single-price"><?php chele_the_price(); ?></div>
				<?php endif; ?>

				<div class="product-single-excerpt">
					<?php the_excerpt(); ?>
				</div>

				<ul class="product-attributes">
					<?php if ( $pieces ) : ?>
						<li><span><?php esc_html_e( 'Pieces', 'chele' ); ?></span><strong><?php echo esc_html( $pieces ); ?></strong></li>
					<?php endif; ?>
					<?php if ( $fabric ) : ?>
						<li><span><?php esc_html_e( 'Fabric', 'chele' ); ?></span><strong><?php echo esc_html( $fabric ); ?></strong></li>
					<?php endif; ?>
					<li><span><?php esc_html_e( 'Finishing', 'chele' ); ?></span><strong><?php esc_html_e( 'Hand-finished', 'chele' ); ?></strong></li>
				</ul>

				<div class="product-single-actions">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<?php woocommerce_template_single_add_to_cart(); ?>
					<?php else : ?>
						<a class="btn btn--primary btn--block" href="<?php echo esc_url( $enquire ); ?>" target="_blank" rel="noopener noreferrer">
							<?php chele_icon( 'whatsapp', 18 ); ?> <?php esc_html_e( 'Enquire to Order', 'chele' ); ?>
						</a>
						<p class="product-single-note"><?php esc_html_e( 'Message us to confirm size, stitching and delivery. We reply within hours.', 'chele' ); ?></p>
					<?php endif; ?>
				</div>

				<div class="product-single-promise">
					<span><?php chele_icon( 'truck', 18 ); ?> <?php esc_html_e( 'Nationwide & worldwide shipping', 'chele' ); ?></span>
					<span><?php chele_icon( 'scissors', 18 ); ?> <?php esc_html_e( 'Custom stitching available', 'chele' ); ?></span>
				</div>
			</div>

		</article>

		<div class="product-single-description prose reveal">
			<h2 class="product-desc-title"><?php esc_html_e( 'The Details', 'chele' ); ?></h2>
			<?php the_content(); ?>
		</div>

	</div>

	<?php
	// Related products from the same collection.
	if ( $collection ) :
		$related = new WP_Query(
			array(
				'post_type'      => 'chele_product',
				'posts_per_page' => 4,
				'post__not_in'   => array( get_the_ID() ),
				'tax_query'      => array(
					array(
						'taxonomy' => 'chele_collection',
						'field'    => 'term_id',
						'terms'    => $collection->term_id,
					),
				),
				'no_found_rows'  => true,
			)
		);
		if ( $related->have_posts() ) :
			?>
			<section class="products section section--soft">
				<div class="container">
					<header class="section-head section-head--center reveal">
						<p class="eyebrow"><?php esc_html_e( 'You may also love', 'chele' ); ?></p>
						<h2 class="section-title"><?php esc_html_e( 'More from this Collection', 'chele' ); ?></h2>
					</header>
					<div class="product-grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							get_template_part( 'template-parts/product-card' );
						endwhile;
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
		wp_reset_postdata();
	endif;

endwhile;

get_footer();
