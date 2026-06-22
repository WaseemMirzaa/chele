<?php
/**
 * Default page template.
 *
 * @package Chele
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'single-page' ); ?>>
		<div class="page-hero">
			<div class="container">
				<p class="eyebrow"><?php esc_html_e( 'Chelé', 'chele' ); ?></p>
				<h1 class="page-hero-title"><?php the_title(); ?></h1>
			</div>
		</div>

		<div class="container section">
			<div class="entry-content prose">
				<?php
				if ( has_post_thumbnail() ) {
					echo '<div class="entry-featured">';
					the_post_thumbnail( 'chele-wide' );
					echo '</div>';
				}
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'chele' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
