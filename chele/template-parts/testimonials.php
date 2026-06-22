<?php
/**
 * Customer testimonials.
 *
 * @package Chele
 */

$quotes = array(
	array(
		'text'   => __( 'The detailing is exquisite — the embroidery looks even more beautiful in person. My eid outfit drew compliments all day.', 'chele' ),
		'name'   => __( 'Ayesha K.', 'chele' ),
		'place'  => __( 'Lahore', 'chele' ),
	),
	array(
		'text'   => __( 'My daughter and I matched in Chelé for the wedding. The fabric is so soft and the fit was perfect. We felt special.', 'chele' ),
		'name'   => __( 'Sana R.', 'chele' ),
		'place'  => __( 'Karachi', 'chele' ),
	),
	array(
		'text'   => __( 'Ordered from London and it arrived beautifully packaged within the week. Truly luxury, end to end.', 'chele' ),
		'name'   => __( 'Hira M.', 'chele' ),
		'place'  => __( 'London', 'chele' ),
	),
);
?>
<section class="testimonials section">
	<div class="container">
		<header class="section-head section-head--center reveal">
			<p class="eyebrow"><?php esc_html_e( 'Kind Words', 'chele' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Loved by Our Community', 'chele' ); ?></h2>
		</header>

		<div class="testimonial-grid">
			<?php foreach ( $quotes as $i => $quote ) : ?>
				<figure class="testimonial reveal" data-reveal-delay="<?php echo esc_attr( $i * 100 ); ?>">
					<div class="testimonial-stars" aria-hidden="true">
						<?php for ( $s = 0; $s < 5; $s++ ) { chele_icon( 'star', 16 ); } ?>
					</div>
					<blockquote><?php echo esc_html( $quote['text'] ); ?></blockquote>
					<figcaption>
						<span class="testimonial-name"><?php echo esc_html( $quote['name'] ); ?></span>
						<span class="testimonial-place"><?php echo esc_html( $quote['place'] ); ?></span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
