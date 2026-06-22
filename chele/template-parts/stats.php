<?php
/**
 * Stats / credibility band (dark, with animated counters).
 *
 * @package Chele
 */

$stats = array(
	array( 'num' => 8,   'suffix' => '',  'label' => __( 'Curated Collections', 'chele' ) ),
	array( 'num' => 100, 'suffix' => '%', 'label' => __( 'Hand-Finished', 'chele' ) ),
	array( 'num' => 24,  'suffix' => '',  'label' => __( 'Countries Shipped', 'chele' ) ),
	array( 'num' => 5000, 'suffix' => '+', 'label' => __( 'Happy Customers', 'chele' ) ),
);
?>
<section class="stats section">
	<div class="container">
		<div class="stats-grid">
			<?php foreach ( $stats as $i => $stat ) : ?>
				<div class="stat reveal" data-reveal-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<div class="stat-num" data-count="<?php echo esc_attr( $stat['num'] ); ?>" data-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>">0<?php echo esc_html( $stat['suffix'] ); ?></div>
					<div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
