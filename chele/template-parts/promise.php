<?php
/**
 * Service promise strip.
 *
 * @package Chele
 */

$items = array(
	array( 'truck', __( 'Nationwide Delivery', 'chele' ), __( 'Complimentary across Pakistan', 'chele' ) ),
	array( 'globe', __( 'Worldwide Shipping', 'chele' ), __( 'Delivered to your door, globally', 'chele' ) ),
	array( 'scissors', __( 'Custom Stitching', 'chele' ), __( 'Tailored to your measurements', 'chele' ) ),
	array( 'whatsapp', __( 'Concierge Support', 'chele' ), __( 'Styling help, 7 days a week', 'chele' ) ),
);
?>
<section class="promise">
	<div class="container promise-grid">
		<?php foreach ( $items as $i => $item ) : ?>
			<div class="promise-item reveal" data-reveal-delay="<?php echo esc_attr( $i * 80 ); ?>">
				<span class="promise-icon"><?php chele_icon( $item[0], 26 ); ?></span>
				<div class="promise-copy">
					<h3><?php echo esc_html( $item[1] ); ?></h3>
					<p><?php echo esc_html( $item[2] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
