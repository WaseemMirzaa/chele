<?php
/**
 * Built-in Product showcase.
 *
 * Registers a lightweight "Product" custom post type plus a "Collection"
 * taxonomy so a boutique catalogue can be managed from the dashboard without
 * requiring WooCommerce. Price, currency and badge meta are stored per product
 * and surfaced through the template tags in inc/template-tags.php.
 *
 * @package Chele
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Product post type.
 */
function chele_register_product_cpt() {
	$labels = array(
		'name'                  => _x( 'Products', 'Post type general name', 'chele' ),
		'singular_name'         => _x( 'Product', 'Post type singular name', 'chele' ),
		'menu_name'             => _x( 'Products', 'Admin Menu text', 'chele' ),
		'add_new'               => __( 'Add New', 'chele' ),
		'add_new_item'          => __( 'Add New Product', 'chele' ),
		'edit_item'             => __( 'Edit Product', 'chele' ),
		'new_item'              => __( 'New Product', 'chele' ),
		'view_item'             => __( 'View Product', 'chele' ),
		'search_items'          => __( 'Search Products', 'chele' ),
		'not_found'             => __( 'No products found.', 'chele' ),
		'not_found_in_trash'    => __( 'No products found in Trash.', 'chele' ),
		'all_items'             => __( 'All Products', 'chele' ),
		'featured_image'        => __( 'Product Image', 'chele' ),
		'set_featured_image'    => __( 'Set product image', 'chele' ),
		'remove_featured_image' => __( 'Remove product image', 'chele' ),
		'use_featured_image'    => __( 'Use as product image', 'chele' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-tag',
		'menu_position'      => 5,
		'rewrite'            => array( 'slug' => 'shop', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'taxonomies'         => array( 'chele_collection' ),
	);

	// If WooCommerce is active it owns the catalogue; stand down to avoid clashes.
	if ( class_exists( 'WooCommerce' ) ) {
		return;
	}

	register_post_type( 'chele_product', $args );
}
add_action( 'init', 'chele_register_product_cpt' );

/**
 * Register the Collection taxonomy (e.g. Lawn, Formal, Girls, Bridal).
 */
function chele_register_collection_taxonomy() {
	if ( class_exists( 'WooCommerce' ) ) {
		return;
	}

	$labels = array(
		'name'              => _x( 'Collections', 'taxonomy general name', 'chele' ),
		'singular_name'     => _x( 'Collection', 'taxonomy singular name', 'chele' ),
		'search_items'      => __( 'Search Collections', 'chele' ),
		'all_items'         => __( 'All Collections', 'chele' ),
		'edit_item'         => __( 'Edit Collection', 'chele' ),
		'update_item'       => __( 'Update Collection', 'chele' ),
		'add_new_item'      => __( 'Add New Collection', 'chele' ),
		'new_item_name'     => __( 'New Collection Name', 'chele' ),
		'menu_name'         => __( 'Collections', 'chele' ),
	);

	register_taxonomy(
		'chele_collection',
		array( 'chele_product' ),
		array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'collection' ),
		)
	);
}
add_action( 'init', 'chele_register_collection_taxonomy' );

/**
 * Flush rewrite rules exactly once, after the Product post type and Collection
 * taxonomy are registered, so the /shop/ archive and collection URLs resolve
 * without the user needing to re-save permalinks.
 */
function chele_maybe_flush_rewrites() {
	if ( get_option( 'chele_rewrites_flushed' ) === CHELE_VERSION ) {
		return;
	}
	if ( class_exists( 'WooCommerce' ) ) {
		update_option( 'chele_rewrites_flushed', CHELE_VERSION );
		return;
	}
	flush_rewrite_rules();
	update_option( 'chele_rewrites_flushed', CHELE_VERSION );
}
add_action( 'init', 'chele_maybe_flush_rewrites', 99 );

/**
 * Register the product detail meta box (price, currency, badge, fabric).
 */
function chele_product_meta_box() {
	add_meta_box(
		'chele_product_details',
		__( 'Product Details', 'chele' ),
		'chele_product_meta_box_html',
		'chele_product',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'chele_product_meta_box' );

/**
 * Render the product meta box.
 *
 * @param WP_Post $post Current post.
 */
function chele_product_meta_box_html( $post ) {
	wp_nonce_field( 'chele_save_product_meta', 'chele_product_meta_nonce' );

	$price    = get_post_meta( $post->ID, '_chele_price', true );
	$compare  = get_post_meta( $post->ID, '_chele_compare_price', true );
	$currency = get_post_meta( $post->ID, '_chele_currency', true );
	$badge    = get_post_meta( $post->ID, '_chele_badge', true );
	$fabric   = get_post_meta( $post->ID, '_chele_fabric', true );
	$pieces   = get_post_meta( $post->ID, '_chele_pieces', true );

	$currency = $currency ? $currency : 'PKR';
	?>
	<p>
		<label for="chele_price"><strong><?php esc_html_e( 'Price', 'chele' ); ?></strong></label><br />
		<input type="number" step="0.01" id="chele_price" name="chele_price" value="<?php echo esc_attr( $price ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="chele_compare_price"><strong><?php esc_html_e( 'Compare-at Price (optional)', 'chele' ); ?></strong></label><br />
		<input type="number" step="0.01" id="chele_compare_price" name="chele_compare_price" value="<?php echo esc_attr( $compare ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="chele_currency"><strong><?php esc_html_e( 'Currency Code', 'chele' ); ?></strong></label><br />
		<input type="text" id="chele_currency" name="chele_currency" value="<?php echo esc_attr( $currency ); ?>" style="width:100%;" maxlength="5" />
	</p>
	<p>
		<label for="chele_badge"><strong><?php esc_html_e( 'Badge (e.g. New, Bestseller)', 'chele' ); ?></strong></label><br />
		<input type="text" id="chele_badge" name="chele_badge" value="<?php echo esc_attr( $badge ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="chele_pieces"><strong><?php esc_html_e( 'Pieces (e.g. 3-Piece)', 'chele' ); ?></strong></label><br />
		<input type="text" id="chele_pieces" name="chele_pieces" value="<?php echo esc_attr( $pieces ); ?>" style="width:100%;" />
	</p>
	<p>
		<label for="chele_fabric"><strong><?php esc_html_e( 'Fabric', 'chele' ); ?></strong></label><br />
		<input type="text" id="chele_fabric" name="chele_fabric" value="<?php echo esc_attr( $fabric ); ?>" style="width:100%;" />
	</p>
	<?php
}

/**
 * Persist product meta on save.
 *
 * @param int $post_id Post ID.
 */
function chele_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['chele_product_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['chele_product_meta_nonce'] ) ), 'chele_save_product_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_chele_price'         => 'chele_price',
		'_chele_compare_price' => 'chele_compare_price',
		'_chele_currency'      => 'chele_currency',
		'_chele_badge'         => 'chele_badge',
		'_chele_fabric'        => 'chele_fabric',
		'_chele_pieces'        => 'chele_pieces',
	);

	foreach ( $fields as $meta_key => $field_name ) {
		if ( isset( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) );
		}
	}
}
add_action( 'save_post_chele_product', 'chele_save_product_meta' );
