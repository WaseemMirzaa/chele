<?php
/**
 * Product archive — the Shop.
 *
 * @package Chele
 */

get_header();

$is_tax     = is_tax( 'chele_collection' );
$page_title = $is_tax ? single_term_title( '', false ) : __( 'The Collection', 'chele' );
$terms      = get_terms( array( 'taxonomy' => 'chele_collection', 'hide_empty' => true ) );
?>

<div class="page-hero page-hero--shop">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Chelé', 'chele' ); ?></p>
		<h1 class="page-hero-title"><?php echo esc_html( $page_title ); ?></h1>
		<?php if ( $is_tax ) : ?>
			<?php the_archive_description( '<p class="page-hero-text">', '</p>' ); ?>
		<?php else : ?>
			<p class="page-hero-text"><?php esc_html_e( 'Premium ladies & girls dresses — embroidered, hand-finished and made with love.', 'chele' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<div class="container section">

	<?php if ( ! is_wp_error( $terms ) && $terms ) : ?>
		<nav class="shop-filters" aria-label="<?php esc_attr_e( 'Collections', 'chele' ); ?>">
			<a class="shop-filter <?php echo ! $is_tax ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'chele_product' ) ); ?>"><?php esc_html_e( 'All', 'chele' ); ?></a>
			<?php
			$current = $is_tax ? get_queried_object_id() : 0;
			foreach ( $terms as $term ) :
				$active = ( $current === $term->term_id ) ? 'is-active' : '';
				?>
				<a class="shop-filter <?php echo esc_attr( $active ); ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="product-grid product-grid--shop">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/product-card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => __( '←', 'chele' ),
				'next_text' => __( '→', 'chele' ),
			)
		);
		?>
	<?php else : ?>
		<div class="empty-state">
			<p><?php esc_html_e( 'No pieces in this collection just yet — please check back soon.', 'chele' ); ?></p>
		</div>
	<?php endif; ?>

</div>

<?php
get_footer();
