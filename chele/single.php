<?php
/**
 * Single blog post.
 *
 * @package Chele
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'single-post' ); ?>>
		<div class="page-hero">
			<div class="container container--narrow">
				<?php
				$cats = get_the_category();
				if ( $cats ) {
					echo '<p class="eyebrow">' . esc_html( $cats[0]->name ) . '</p>';
				}
				?>
				<h1 class="page-hero-title"><?php the_title(); ?></h1>
				<p class="page-hero-meta"><?php chele_posted_meta(); ?></p>
			</div>
		</div>

		<div class="container container--narrow section">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-featured"><?php the_post_thumbnail( 'chele-wide' ); ?></div>
			<?php endif; ?>

			<div class="entry-content prose">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'chele' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>

			<footer class="entry-footer">
				<?php
				the_tags( '<div class="entry-tags">', '', '</div>' );
				?>
			</footer>

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
