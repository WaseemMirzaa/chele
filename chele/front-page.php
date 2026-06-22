<?php
/**
 * The homepage — a curated, editorial sequence of brand sections.
 *
 * @package Chele
 */

get_header();
?>

<div class="home-sections">

	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/marquee' );
	get_template_part( 'template-parts/pillars' );
	get_template_part( 'template-parts/products', null, array( 'title' => __( 'New This Season', 'chele' ), 'eyebrow' => __( 'The Edit', 'chele' ), 'limit' => 4 ) );
	get_template_part( 'template-parts/story' );
	get_template_part( 'template-parts/collections' );
	get_template_part( 'template-parts/lookbook' );
	get_template_part( 'template-parts/products', null, array( 'title' => __( 'Most Loved', 'chele' ), 'eyebrow' => __( 'Bestsellers', 'chele' ), 'limit' => 4, 'offset' => 4 ) );
	get_template_part( 'template-parts/promise' );
	get_template_part( 'template-parts/testimonials' );
	get_template_part( 'template-parts/newsletter' );
	?>

</div>

<?php
get_footer();
