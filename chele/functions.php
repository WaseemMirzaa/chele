<?php
/**
 * Chelé functions and definitions.
 *
 * Bootstraps theme support, assets, navigation, widget areas, the built-in
 * Product showcase and the Customizer integration. Designed to work with zero
 * required plugins so the theme can be activated and used instantly.
 *
 * @package Chele
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

if ( ! defined( 'CHELE_VERSION' ) ) {
	define( 'CHELE_VERSION', '1.0.0' );
}

define( 'CHELE_DIR', get_template_directory() );
define( 'CHELE_URI', get_template_directory_uri() );

/**
 * Core theme setup.
 */
function chele_setup() {
	load_theme_textdomain( 'chele', CHELE_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'custom-background',
		array( 'default-color' => 'f5efe6' )
	);

	// Optional WooCommerce compatibility — used only if the plugin is present.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Editorial image sizes for the lookbook and product cards.
	add_image_size( 'chele-product', 720, 960, true );
	add_image_size( 'chele-portrait', 900, 1200, true );
	add_image_size( 'chele-wide', 1600, 900, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'chele' ),
			'footer'  => __( 'Footer Menu', 'chele' ),
		)
	);
}
add_action( 'after_setup_theme', 'chele_setup' );

/**
 * Set the content width based on the theme's design.
 */
function chele_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'chele_content_width', 1280 );
}
add_action( 'after_setup_theme', 'chele_content_width', 0 );

/**
 * Enqueue front-end styles and scripts, including the curated Google Fonts.
 */
function chele_assets() {
	// Google Fonts: Cormorant Garamond (display serif), Jost (UI sans), Pinyon Script (accent).
	wp_enqueue_style(
		'chele-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&family=Pinyon+Script&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'chele-style', get_stylesheet_uri(), array(), CHELE_VERSION );

	wp_enqueue_style(
		'chele-main',
		CHELE_URI . '/assets/css/main.css',
		array( 'chele-style' ),
		CHELE_VERSION
	);

	wp_enqueue_script(
		'chele-main',
		CHELE_URI . '/assets/js/main.js',
		array(),
		CHELE_VERSION,
		true
	);

	wp_localize_script(
		'chele-main',
		'cheleData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'chele_nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'chele_assets' );

/**
 * Load fonts and base palette inside the block editor for a true preview.
 */
function chele_editor_assets() {
	add_editor_style(
		array(
			'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&family=Pinyon+Script&display=swap',
			'assets/css/main.css',
		)
	);
}
add_action( 'after_setup_theme', 'chele_editor_assets' );

/**
 * Register widget areas.
 */
function chele_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'chele' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown on the blog sidebar.', 'chele' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'chele' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => __( 'Footer widget area.', 'chele' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'chele_widgets_init' );

/**
 * Add a pill-style body class so templates can hook into the brand layout.
 */
function chele_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( is_front_page() ) {
		$classes[] = 'chele-home';
	}
	$classes[] = 'chele-theme';
	return $classes;
}
add_filter( 'body_class', 'chele_body_classes' );

/**
 * Trim the default excerpt to an elegant length and refine the "read more".
 */
function chele_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'chele_excerpt_length' );

function chele_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'chele_excerpt_more' );

/**
 * Theme includes.
 */
require CHELE_DIR . '/inc/template-tags.php';
require CHELE_DIR . '/inc/product-cpt.php';
require CHELE_DIR . '/inc/sample-content.php';
require CHELE_DIR . '/inc/customizer.php';
