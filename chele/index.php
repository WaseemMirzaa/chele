<?php
/**
 * The main template (blog / archive fallback).
 *
 * @package Chele
 */

get_header();
?>

<div class="page-hero page-hero--compact">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'The Chelé Journal', 'chele' ); ?></p>
		<h1 class="page-hero-title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				/* translators: %s: search query. */
				printf( esc_html__( 'Search: %s', 'chele' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} else {
				esc_html_e( 'Journal', 'chele' );
			}
			?>
		</h1>
		<?php the_archive_description( '<p class="page-hero-text">', '</p>' ); ?>
	</div>
</div>

<div class="container section">
	<?php if ( have_posts() ) : ?>
		<div class="post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => __( '← Newer', 'chele' ),
				'next_text' => __( 'Older →', 'chele' ),
			)
		);
		?>
	<?php else : ?>
		<div class="empty-state">
			<p><?php esc_html_e( 'Nothing here yet — please check back soon.', 'chele' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
