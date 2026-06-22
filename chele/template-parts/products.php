<?php
/**
 * Featured products grid.
 *
 * @param array $args { title, eyebrow, limit, offset }
 * @package Chele
 */

$args      = wp_parse_args( $args ?? array(), array(
	'title'   => __( 'Featured', 'chele' ),
	'eyebrow' => __( 'The Edit', 'chele' ),
	'limit'   => 4,
	'offset'  => 0,
) );

$post_type = class_exists( 'WooCommerce' ) ? 'product' : 'chele_product';
$shop_url  = get_post_type_archive_link( $post_type );

$query = new WP_Query(
	array(
		'post_type'      => $post_type,
		'posts_per_page' => (int) $args['limit'],
		'offset'         => (int) $args['offset'],
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( $query->have_posts() ) :
	?>
	<section class="products section">
		<div class="container">
			<header class="section-head reveal">
				<p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
				<h2 class="section-title"><?php echo esc_html( $args['title'] ); ?></h2>
				<?php if ( $shop_url ) : ?>
					<a class="section-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View all', 'chele' ); ?> <?php chele_icon( 'arrow', 16 ); ?></a>
				<?php endif; ?>
			</header>

			<div class="product-grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					get_template_part( 'template-parts/product-card' );
				endwhile;
				?>
			</div>
		</div>
	</section>
	<?php
endif;
wp_reset_postdata();
