<?php
/**
 * Scrolling brand marquee.
 *
 * @package Chele
 */

$words = array(
	__( 'Premium Fabrics', 'chele' ),
	__( 'Exquisite Details', 'chele' ),
	__( 'Elegant Designs', 'chele' ),
	__( 'Made For You', 'chele' ),
	__( 'Timeless', 'chele' ),
	__( 'Hand-Finished', 'chele' ),
);
?>
<section class="marquee" id="marquee" aria-hidden="true">
	<div class="marquee-track">
		<?php for ( $r = 0; $r < 2; $r++ ) : ?>
			<?php foreach ( $words as $word ) : ?>
				<span class="marquee-item"><?php echo esc_html( $word ); ?></span>
				<span class="marquee-star">✦</span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</section>
