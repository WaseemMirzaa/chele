<?php
/**
 * Template tags and presentation helpers.
 *
 * @package Chele
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a published page/post by its exact title (WP 6.2-safe replacement for
 * the deprecated get_page_by_title()).
 *
 * @param string $title     Title to match.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function chele_get_page_by_title( $title, $post_type = 'page' ) {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * Read a Customizer/theme-mod value with a sensible default.
 *
 * @param string $key     Setting key (without the chele_ prefix).
 * @param mixed  $default Default value.
 * @return mixed
 */
function chele_option( $key, $default = '' ) {
	return get_theme_mod( 'chele_' . $key, $default );
}

/**
 * Currency symbol/prefix for a currency code.
 *
 * @param string $code Currency code.
 * @return string
 */
function chele_currency_symbol( $code ) {
	$map = array(
		'PKR' => 'Rs ',
		'USD' => '$',
		'GBP' => '£',
		'EUR' => '€',
		'AED' => 'AED ',
		'INR' => '₹',
		'SAR' => 'SAR ',
		'CAD' => 'C$',
	);
	$code = strtoupper( $code );
	return isset( $map[ $code ] ) ? $map[ $code ] : ( $code . ' ' );
}

/**
 * Format a price value for display.
 *
 * @param string|float $amount   Amount.
 * @param string       $currency Currency code.
 * @return string
 */
function chele_format_price( $amount, $currency = 'PKR' ) {
	if ( '' === $amount || null === $amount ) {
		return '';
	}
	$symbol  = chele_currency_symbol( $currency );
	$decimals = ( floor( (float) $amount ) === (float) $amount ) ? 0 : 2;
	return $symbol . number_format( (float) $amount, $decimals );
}

/**
 * Echo the formatted price markup for the current product.
 *
 * @param int $post_id Optional post ID.
 */
function chele_the_price( $post_id = null ) {
	$post_id  = $post_id ? $post_id : get_the_ID();
	$price    = get_post_meta( $post_id, '_chele_price', true );
	$compare  = get_post_meta( $post_id, '_chele_compare_price', true );
	$currency = get_post_meta( $post_id, '_chele_currency', true );
	$currency = $currency ? $currency : 'PKR';

	if ( '' === $price ) {
		return;
	}

	echo '<span class="product-price">';
	if ( '' !== $compare && (float) $compare > (float) $price ) {
		echo '<span class="price-compare">' . esc_html( chele_format_price( $compare, $currency ) ) . '</span> ';
	}
	echo '<span class="price-current">' . esc_html( chele_format_price( $price, $currency ) ) . '</span>';
	echo '</span>';
}

/**
 * Output the product badge (e.g. "New", "Bestseller"), if any.
 *
 * @param int $post_id Optional post ID.
 */
function chele_the_badge( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$badge   = get_post_meta( $post_id, '_chele_badge', true );
	if ( $badge ) {
		echo '<span class="product-badge">' . esc_html( $badge ) . '</span>';
	}
}

/**
 * Render the product image: the featured image when present, otherwise a
 * tasteful on-brand SVG placeholder generated from the post ID.
 *
 * @param int    $post_id Optional post ID.
 * @param string $size    Image size.
 */
function chele_product_image( $post_id = null, $size = 'chele-product' ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array( 'class' => 'product-photo', 'loading' => 'lazy' ) );
		return;
	}

	echo chele_placeholder_svg( $post_id, get_the_title( $post_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup is built internally.
}

/**
 * Build an elegant, brand-aligned SVG placeholder.
 *
 * Inline (not a background-image) so it inherits the page's loaded web fonts
 * and scales crisply at any size with no external requests.
 *
 * @param int    $seed  Number used to pick a palette deterministically.
 * @param string $title Title to print on the placeholder.
 * @return string SVG markup.
 */
function chele_placeholder_svg( $seed, $title = '' ) {
	$palettes = array(
		array( 'top' => '#f1e6d6', 'bottom' => '#e8cfca', 'accent' => '#a86b78' ),
		array( 'top' => '#ecdcd4', 'bottom' => '#cfa6aa', 'accent' => '#6e4253' ),
		array( 'top' => '#f1e8da', 'bottom' => '#d8c195', 'accent' => '#b7935e' ),
		array( 'top' => '#e8dad5', 'bottom' => '#a9737f', 'accent' => '#57303f' ),
		array( 'top' => '#e7e1d2', 'bottom' => '#bcc4af', 'accent' => '#7d8a6e' ),
		array( 'top' => '#f3e9dd', 'bottom' => '#dcbcc0', 'accent' => '#9c6b78' ),
	);
	$p   = $palettes[ absint( $seed ) % count( $palettes ) ];
	$uid = 'g' . absint( $seed ) . wp_rand( 1000, 9999 );

	$label = $title ? esc_html( mb_strtoupper( wp_trim_words( $title, 4, '' ) ) ) : '';

	// A single botanical sprig, reused via <use> and rotated into two corners.
	$sprig = '<path d="M0 0 C 18 -26 40 -34 64 -30 M22 -16 C 30 -30 24 -44 8 -48 M30 -8 C 46 -16 56 -36 50 -54 M42 -2 C 60 -2 76 -18 78 -38" fill="none" stroke="' . esc_attr( $p['accent'] ) . '" stroke-width="1.4" stroke-linecap="round" opacity="0.55"/>'
		. '<circle cx="6" cy="-50" r="3.4" fill="' . esc_attr( $p['accent'] ) . '" opacity="0.6"/>'
		. '<circle cx="50" cy="-56" r="2.8" fill="' . esc_attr( $p['accent'] ) . '" opacity="0.5"/>'
		. '<circle cx="78" cy="-40" r="3.2" fill="' . esc_attr( $p['accent'] ) . '" opacity="0.55"/>';

	$svg  = '<svg class="chele-ph" viewBox="0 0 720 960" role="img" aria-label="' . esc_attr( $title ) . '" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">';
	$svg .= '<defs><linearGradient id="' . esc_attr( $uid ) . '" x1="0" y1="0" x2="0.35" y2="1">'
		. '<stop offset="0" stop-color="' . esc_attr( $p['top'] ) . '"/>'
		. '<stop offset="1" stop-color="' . esc_attr( $p['bottom'] ) . '"/></linearGradient>'
		. '<g id="sprig-' . esc_attr( $uid ) . '">' . $sprig . '</g></defs>';
	$svg .= '<rect width="720" height="960" fill="url(#' . esc_attr( $uid ) . ')"/>';

	// Inset hairline frame.
	$svg .= '<rect x="34" y="34" width="652" height="892" fill="none" stroke="' . esc_attr( $p['accent'] ) . '" stroke-width="1" opacity="0.35"/>';

	// Botanical sprigs in opposite corners.
	$svg .= '<use href="#sprig-' . esc_attr( $uid ) . '" transform="translate(70 150)"/>';
	$svg .= '<use href="#sprig-' . esc_attr( $uid ) . '" transform="translate(650 810) rotate(180)"/>';

	// Faint Chelé "C" monogram watermark.
	$svg .= '<text x="360" y="470" text-anchor="middle" font-family="\'Cormorant Garamond\', Georgia, serif" font-size="420" fill="' . esc_attr( $p['accent'] ) . '" opacity="0.08" font-weight="500">C</text>';

	// Centred label block.
	$svg .= '<g text-anchor="middle" font-family="\'Jost\', Arial, sans-serif">';
	$svg .= '<text x="360" y="540" font-family="\'Cormorant Garamond\', Georgia, serif" font-size="46" fill="#4a3a34" opacity="0.85">Chelé</text>';
	if ( $label ) {
		$svg .= '<text x="360" y="586" font-size="17" letter-spacing="5" fill="' . esc_attr( $p['accent'] ) . '">' . $label . '</text>';
	}
	$svg .= '<line x1="320" y1="610" x2="400" y2="610" stroke="' . esc_attr( $p['accent'] ) . '" stroke-width="1" opacity="0.6"/>';
	$svg .= '</g>';

	$svg .= '</svg>';

	return $svg;
}

/**
 * Output the inline Chelé wordmark (used when no custom logo is set).
 *
 * Recreates the brand wordmark — an elegant serif "chelé" with a needle &
 * thread motif threaded through the second "l".
 */
function chele_wordmark() {
	?>
	<span class="chele-wordmark">
		<span class="wm-text">chel<span class="wm-thread">é</span></span>
		<svg class="wm-needle" viewBox="0 0 28 120" xmlns="http://www.w3.org/2000/svg" focusable="false" aria-hidden="true">
			<path d="M14 6 C 26 16 26 30 16 40 C 6 50 6 62 18 72" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
			<line x1="14" y1="30" x2="14" y2="114" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
			<ellipse cx="14" cy="42" rx="3" ry="6.5" fill="none" stroke="currentColor" stroke-width="1.6"/>
		</svg>
	</span>
	<?php
}

/**
 * Site branding — custom logo if set, otherwise the inline wordmark.
 */
function chele_site_branding() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	?>
	<a class="brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<?php chele_wordmark(); ?>
		<span class="brand-tagline"><?php echo esc_html( chele_option( 'tagline', __( 'est. 2024  ·  designer apparel', 'chele' ) ) ); ?></span>
	</a>
	<?php
}

/**
 * Named SVG icon (line-art set used across the theme).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 */
function chele_icon( $name, $size = 24 ) {
	$icons = array(
		'fabric'    => '<path d="M4 7c2.5-2 4-2 6-2s3.5 0 6 2c2.5 2 4 2 4 2v10s-1.5 0-4-2c-2.5-2-4-2-6-2s-3.5 0-6 2c-2.5 2-4 2-4 2V9s1.5 0 4-2z"/>',
		'needle'    => '<path d="M4 20l9-9M14 10c2-2 2-4 0-6M14 4l6 6-6 0z"/><circle cx="6" cy="18" r="1.4"/>',
		'dress'     => '<path d="M9 3l3 3 3-3M9 3l-2 5 2 2-2 8h10l-2-8 2-2-2-5M12 6v3"/>',
		'heart'     => '<path d="M12 20s-7-4.5-9-9c-1.2-2.7.3-5.6 3-6 1.9-.3 3.4.8 4.2 2 .8-1.2 2.3-2.3 4.2-2 2.7.4 4.2 3.3 3 6-2 4.5-7.4 9-7.4 9z"/>',
		'leaf'      => '<path d="M5 19c0-8 6-14 14-14 0 8-6 14-14 14zM5 19c4-4 7-7 9-9"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor" stroke="none"/>',
		'facebook'  => '<path d="M14 9V7c0-1 .5-1.5 1.5-1.5H17V2.5h-2.5C12 2.5 11 4 11 6.5V9H8.5v3H11v9.5h3V12h2.2l.3-3H14z"/>',
		'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 2.5 16.5 0 18M12 3c-2.5 2.5-2.5 16.5 0 18"/>',
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'star'      => '<path d="M12 3l2.5 5.3 5.8.8-4.2 4 1 5.7L12 16l-5.1 2.6 1-5.7-4.2-4 5.8-.8z"/>',
		'whatsapp'  => '<path d="M3 21l1.6-4.6A8 8 0 1 1 8 19.4L3 21z"/><path d="M9 8.5c0 4 3.5 7 6.5 6.5M9 8.5c0-.6.4-1 1-1l1 2-1 1M15.5 15c.6 0 1-.4 1-1l-2-1-1 1"/>',
		'truck'     => '<path d="M2 6h11v9H2zM13 9h4l3 3v3h-7zM7 18a2 2 0 1 0 0-.1M17.5 18a2 2 0 1 0 0-.1"/>',
		'gift'      => '<path d="M3 9h18v3H3zM4 12h16v9H4zM12 9v12M12 9c-2-4-6-2-4 0M12 9c2-4 6-2 4 0"/>',
		'scissors'  => '<circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="M8 8l12 9M8 16L20 7"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return;
	}
	printf(
		'<svg class="chele-icon icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		absint( $size ),
		$icons[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static internal markup.
	);
}

/**
 * Output configured social links.
 */
function chele_social_links() {
	$links = array(
		'instagram' => chele_option( 'instagram', 'https://instagram.com/chele.official' ),
		'facebook'  => chele_option( 'facebook', 'https://facebook.com/cheleofficial' ),
		'whatsapp'  => chele_option( 'whatsapp', '' ),
		'globe'     => chele_option( 'website', 'https://www.chele.pk' ),
	);

	$labels = array(
		'instagram' => __( 'Instagram', 'chele' ),
		'facebook'  => __( 'Facebook', 'chele' ),
		'whatsapp'  => __( 'WhatsApp', 'chele' ),
		'globe'     => __( 'Website', 'chele' ),
	);

	echo '<ul class="social-links">';
	foreach ( $links as $icon => $url ) {
		if ( ! $url ) {
			continue;
		}
		printf(
			'<li><a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">',
			esc_url( $url ),
			esc_attr( $labels[ $icon ] )
		);
		chele_icon( $icon, 20 );
		echo '</a></li>';
	}
	echo '</ul>';
}

/**
 * Posted-on / posted-by meta for the blog.
 */
function chele_posted_meta() {
	printf(
		'<span class="posted-on">%1$s</span><span class="byline">%2$s %3$s</span>',
		esc_html( get_the_date() ),
		esc_html__( 'by', 'chele' ),
		esc_html( get_the_author() )
	);
}
