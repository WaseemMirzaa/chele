<?php
/**
 * The four brand pillars (echoing the Chelé brand poster).
 *
 * @package Chele
 */

$pillars = array(
	array( 'leaf', __( 'Premium Fabrics', 'chele' ), __( 'Lawn, chiffon, organza & raw silk, sourced for their hand and finish.', 'chele' ) ),
	array( 'needle', __( 'Exquisite Details', 'chele' ), __( 'Embroidery, chikankari and embellishment, finished by master karigars.', 'chele' ) ),
	array( 'dress', __( 'Elegant Designs', 'chele' ), __( 'Silhouettes that honour tradition while feeling effortlessly modern.', 'chele' ) ),
	array( 'heart', __( 'Made For You', 'chele' ), __( 'Considered cuts and sizing for ladies and girls, made with love.', 'chele' ) ),
);
?>
<section class="pillars section">
	<div class="container">
		<div class="pillars-grid">
			<?php foreach ( $pillars as $i => $pillar ) : ?>
				<article class="pillar reveal" data-reveal-delay="<?php echo esc_attr( $i * 90 ); ?>">
					<span class="pillar-index"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<span class="pillar-icon"><?php chele_icon( $pillar[0], 28 ); ?></span>
					<h3 class="pillar-title"><?php echo esc_html( $pillar[1] ); ?></h3>
					<p class="pillar-text"><?php echo esc_html( $pillar[2] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
