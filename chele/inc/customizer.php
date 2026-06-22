<?php
/**
 * Customizer settings — brand, hero, palette, social and footer controls.
 *
 * @package Chele
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer panels, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function chele_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// ---- Panel ----------------------------------------------------------
	$wp_customize->add_panel(
		'chele_panel',
		array(
			'title'    => __( 'Chelé Theme', 'chele' ),
			'priority' => 20,
		)
	);

	// ---- Brand ----------------------------------------------------------
	$wp_customize->add_section(
		'chele_brand',
		array(
			'title' => __( 'Brand & Announcement', 'chele' ),
			'panel' => 'chele_panel',
		)
	);

	chele_add_text( $wp_customize, 'chele_announcement', __( 'Announcement bar text', 'chele' ), 'chele_brand', __( 'Complimentary delivery across Pakistan · Worldwide shipping available', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_tagline', __( 'Logo tagline', 'chele' ), 'chele_brand', __( 'est. 2024  ·  designer apparel', 'chele' ) );

	// ---- Hero -----------------------------------------------------------
	$wp_customize->add_section(
		'chele_hero',
		array(
			'title' => __( 'Homepage Hero', 'chele' ),
			'panel' => 'chele_panel',
		)
	);

	chele_add_text( $wp_customize, 'chele_hero_eyebrow', __( 'Eyebrow', 'chele' ), 'chele_hero', __( 'Elegance. Tradition. You.', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_hero_title', __( 'Title', 'chele' ), 'chele_hero', __( 'Ladies & Girls Dresses of Pakistan', 'chele' ) );
	chele_add_textarea( $wp_customize, 'chele_hero_subtitle', __( 'Subtitle', 'chele' ), 'chele_hero', __( 'Celebrating the beauty of Pakistani fashion with timeless designs, premium fabrics and flawless details.', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_hero_cta_text', __( 'Primary button text', 'chele' ), 'chele_hero', __( 'Shop the Collection', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_hero_cta_link', __( 'Primary button link', 'chele' ), 'chele_hero', '' );
	chele_add_text( $wp_customize, 'chele_hero_cta2_text', __( 'Secondary button text', 'chele' ), 'chele_hero', __( 'Our Story', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_hero_cta2_link', __( 'Secondary button link', 'chele' ), 'chele_hero', '' );

	// ---- Palette --------------------------------------------------------
	$wp_customize->add_section(
		'chele_palette',
		array(
			'title'       => __( 'Brand Palette', 'chele' ),
			'panel'       => 'chele_panel',
			'description' => __( 'Override the core brand colours. Leave as-is to keep the Chelé identity.', 'chele' ),
		)
	);

	$colors = array(
		'chele_color_cream' => array( __( 'Ivory / Background', 'chele' ), '#f5efe6' ),
		'chele_color_plum'  => array( __( 'Plum / Primary', 'chele' ), '#6e4253' ),
		'chele_color_rose'  => array( __( 'Dusty Rose', 'chele' ), '#c99ba1' ),
		'chele_color_gold'  => array( __( 'Antique Gold', 'chele' ), '#b7935e' ),
		'chele_color_ink'   => array( __( 'Text / Ink', 'chele' ), '#3a3036' ),
	);
	foreach ( $colors as $id => $data ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $data[1],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $data[0],
					'section' => 'chele_palette',
				)
			)
		);
	}

	// ---- Social ---------------------------------------------------------
	$wp_customize->add_section(
		'chele_social',
		array(
			'title' => __( 'Social & Contact', 'chele' ),
			'panel' => 'chele_panel',
		)
	);

	chele_add_url( $wp_customize, 'chele_instagram', __( 'Instagram URL', 'chele' ), 'chele_social', 'https://instagram.com/chele.official' );
	chele_add_url( $wp_customize, 'chele_facebook', __( 'Facebook URL', 'chele' ), 'chele_social', 'https://facebook.com/cheleofficial' );
	chele_add_url( $wp_customize, 'chele_whatsapp', __( 'WhatsApp link', 'chele' ), 'chele_social', '' );
	chele_add_url( $wp_customize, 'chele_website', __( 'Website URL', 'chele' ), 'chele_social', 'https://www.chele.pk' );

	// ---- Footer ---------------------------------------------------------
	$wp_customize->add_section(
		'chele_footer',
		array(
			'title' => __( 'Footer', 'chele' ),
			'panel' => 'chele_panel',
		)
	);

	chele_add_text( $wp_customize, 'chele_footer_motto', __( 'Footer motto', 'chele' ), 'chele_footer', __( 'Made with love, worn with pride.', 'chele' ) );
	chele_add_text( $wp_customize, 'chele_footer_copyright', __( 'Copyright line', 'chele' ), 'chele_footer', '' );
}
add_action( 'customize_register', 'chele_customize_register' );

/**
 * Helper: add a text setting + control.
 */
function chele_add_text( $wp_customize, $id, $label, $section, $default = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'text' ) );
}

/**
 * Helper: add a textarea setting + control.
 */
function chele_add_textarea( $wp_customize, $id, $label, $section, $default = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'textarea' ) );
}

/**
 * Helper: add a URL setting + control.
 */
function chele_add_url( $wp_customize, $id, $label, $section, $default = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'url' ) );
}

/**
 * Print palette overrides as CSS custom properties in the document head.
 */
function chele_customizer_css() {
	$map = array(
		'--cream' => chele_option( 'color_cream', '#f5efe6' ),
		'--plum'  => chele_option( 'color_plum', '#6e4253' ),
		'--rose'  => chele_option( 'color_rose', '#c99ba1' ),
		'--gold'  => chele_option( 'color_gold', '#b7935e' ),
		'--ink'   => chele_option( 'color_ink', '#3a3036' ),
	);

	$defaults = array(
		'--cream' => '#f5efe6',
		'--plum'  => '#6e4253',
		'--rose'  => '#c99ba1',
		'--gold'  => '#b7935e',
		'--ink'   => '#3a3036',
	);

	$rules = '';
	foreach ( $map as $var => $value ) {
		if ( $value && $value !== $defaults[ $var ] ) {
			$rules .= sprintf( '%s:%s;', $var, $value );
		}
	}

	if ( $rules ) {
		printf( "<style id=\"chele-customizer-css\">:root{%s}</style>\n", $rules ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values sanitized as hex colors.
	}
}
add_action( 'wp_head', 'chele_customizer_css', 20 );

/**
 * Live-preview JS for the Customizer.
 */
function chele_customize_preview_js() {
	wp_enqueue_script(
		'chele-customize-preview',
		CHELE_URI . '/assets/js/customize-preview.js',
		array( 'customize-preview' ),
		CHELE_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'chele_customize_preview_js' );
