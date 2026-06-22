<?php
/**
 * Blog post card. Expects to run inside the loop.
 *
 * @package Chele
 */

?>
<article <?php post_class( 'post-card reveal' ); ?>>
	<a class="post-card-media" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'chele-product', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php echo chele_placeholder_svg( get_the_ID(), get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</a>
	<div class="post-card-body">
		<?php
		$cats = get_the_category();
		if ( $cats ) :
			?>
			<span class="post-card-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
		<?php endif; ?>
		<h2 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="post-card-meta"><?php chele_posted_meta(); ?></p>
		<p class="post-card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'chele' ); ?> <?php chele_icon( 'arrow', 16 ); ?></a>
	</div>
</article>
