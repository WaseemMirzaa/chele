<?php
/**
 * One-time demo content seeding.
 *
 * On first activation this builds a complete, ready-to-browse storefront:
 * collections, a curated set of sample products, the core pages, a static
 * homepage and a primary navigation menu. Runs once, guarded by an option
 * flag, so it never overwrites a shop owner's later edits.
 *
 * @package Chele
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the demo storefront.
 *
 * Hooked to BOTH after_switch_theme and admin_init for reliability: the former
 * does not always run with the new theme's code loaded, while the latter is
 * guaranteed on the first dashboard load after activation — by which point the
 * Product post type (registered on `init`) exists, so archive links resolve.
 * The option flag makes it run exactly once.
 */
function chele_seed_demo_content() {
	if ( get_option( 'chele_demo_seeded' ) ) {
		return;
	}

	// Claim the flag immediately to avoid any double-run race.
	update_option( 'chele_demo_seeded', CHELE_VERSION );

	$collections = chele_seed_collections();
	chele_seed_products( $collections );
	chele_seed_pages_and_menu();
}
add_action( 'after_switch_theme', 'chele_seed_demo_content' );
add_action( 'admin_init', 'chele_seed_demo_content' );

/**
 * Create the Collection terms and return a name => term_id map.
 *
 * @return array
 */
function chele_seed_collections() {
	if ( class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$names = array( 'Lawn', 'Formal', 'Luxury', 'Bridal', 'Girls', 'Pret' );
	$map   = array();

	foreach ( $names as $name ) {
		$existing = term_exists( $name, 'chele_collection' );
		if ( $existing ) {
			$map[ $name ] = (int) $existing['term_id'];
			continue;
		}
		$term = wp_insert_term( $name, 'chele_collection' );
		if ( ! is_wp_error( $term ) ) {
			$map[ $name ] = (int) $term['term_id'];
		}
	}

	return $map;
}

/**
 * Create the sample products.
 *
 * @param array $collections Name => term_id map.
 */
function chele_seed_products( $collections ) {
	if ( class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Don't duplicate if products already exist.
	$existing = get_posts(
		array(
			'post_type'      => 'chele_product',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( ! empty( $existing ) ) {
		return;
	}

	$products = array(
		array(
			'title'      => 'Gulrang Embroidered Lawn',
			'excerpt'    => 'Hand-finished thread embroidery on premium summer lawn, paired with a printed chiffon dupatta.',
			'content'    => "A celebration of the everyday, Gulrang brings delicate floral thread-work to a breathable premium lawn. The unstitched three-piece includes an embroidered front, plain back and sleeves, and a soft printed chiffon dupatta — effortless for daytime gatherings.\n\nStyle it with juttis and gold studs for a quietly luxurious finish.",
			'price'      => 6990,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '3-Piece Unstitched',
			'fabric'     => 'Premium Lawn',
			'badge'      => 'New',
			'collection' => 'Lawn',
		),
		array(
			'title'      => 'Meher Chikankari Kurta',
			'excerpt'    => 'Intricate chikankari on cotton-net — a timeless white-on-ivory statement.',
			'content'    => "Meher revives the heritage craft of chikankari in fine self-thread across a flowing cotton-net kurta. Tonal, textural and endlessly elegant — designed to be the centrepiece of your wardrobe.\n\nA two-piece including kurta and inner slip.",
			'price'      => 8490,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '2-Piece Stitched',
			'fabric'     => 'Cotton Net',
			'badge'      => '',
			'collection' => 'Formal',
		),
		array(
			'title'      => 'Noor Festive Organza',
			'excerpt'    => 'Sequin and resham embroidery on shimmering organza for the festive season.',
			'content'    => "Noor glimmers. Hand-embellished sequins and resham bloom across a luminous organza shirt, finished with an embroidered organza dupatta and dyed raw-silk trouser. Made for mehndis, eids and evenings that ask for a little more.\n\nThree-piece, fully embellished.",
			'price'      => 18500,
			'compare'    => 22000,
			'currency'   => 'PKR',
			'pieces'     => '3-Piece Stitched',
			'fabric'     => 'Organza',
			'badge'      => 'Bestseller',
			'collection' => 'Luxury',
		),
		array(
			'title'      => 'Sana Embroidered Khaddar',
			'excerpt'    => 'Warm woven khaddar with rich winter embroidery and a wool-shawl dupatta.',
			'content'    => "Sana wraps you in winter. A densely embroidered khaddar shirt is paired with a soft woven wool-blend shawl and matching trouser — heritage warmth in a modern, wearable palette.\n\nThree-piece unstitched.",
			'price'      => 9250,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '3-Piece Unstitched',
			'fabric'     => 'Khaddar',
			'badge'      => '',
			'collection' => 'Formal',
		),
		array(
			'title'      => 'Roshni Bridal Maxi',
			'excerpt'    => 'A hand-crafted bridal maxi in raw silk and net, layered with zardozi and pearls.',
			'content'    => "Roshni is the heirloom. Months of atelier hand-work — zardozi, dabka, pearls and crystal — cascade across a raw-silk and net maxi with a sweeping trail. Made to order and made to remember.\n\nThree-piece couture ensemble. Please allow 4–6 weeks for hand-crafting.",
			'price'      => 45000,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '3-Piece Couture',
			'fabric'     => 'Raw Silk & Net',
			'badge'      => 'Limited',
			'collection' => 'Bridal',
		),
		array(
			'title'      => 'Aira Girls Festive Frock',
			'excerpt'    => 'A twirl-worthy embroidered frock in soft lawn — made for little celebrations.',
			'content'    => "Made for the youngest in the family, Aira pairs a sweet embroidered yoke with a full, twirl-ready frock in gentle, skin-soft lawn. Comes with matching tights.\n\nTwo-piece, stitched. Available in ages 2–10.",
			'price'      => 4290,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '2-Piece Stitched',
			'fabric'     => 'Soft Lawn',
			'badge'      => 'New',
			'collection' => 'Girls',
		),
		array(
			'title'      => 'Zara Everyday Pret Kurta',
			'excerpt'    => 'A clean, printed cotton kurta cut for easy, elegant everyday wear.',
			'content'    => "Zara is your go-to. A breezy printed cotton kurta with a relaxed straight cut and side slits — the kind of piece you reach for again and again. Ready to wear.\n\nSingle-piece stitched kurta.",
			'price'      => 3990,
			'compare'    => 4990,
			'currency'   => 'PKR',
			'pieces'     => '1-Piece Stitched',
			'fabric'     => 'Cotton',
			'badge'      => '',
			'collection' => 'Pret',
		),
		array(
			'title'      => 'Hina Luxury Chiffon',
			'excerpt'    => 'Floating embroidered chiffon with a scalloped, hand-finished hem.',
			'content'    => "Hina is pure grace. Fine resham embroidery drifts across a weightless chiffon shirt with a delicate scalloped, hand-finished hem, completed by an embroidered dupatta and silk trouser.\n\nThree-piece, stitched to order.",
			'price'      => 16800,
			'compare'    => '',
			'currency'   => 'PKR',
			'pieces'     => '3-Piece Stitched',
			'fabric'     => 'Chiffon',
			'badge'      => 'Bestseller',
			'collection' => 'Luxury',
		),
	);

	$order = 0;
	foreach ( $products as $product ) {
		$order++;
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'chele_product',
				'post_status'  => 'publish',
				'post_title'   => $product['title'],
				'post_excerpt' => $product['excerpt'],
				'post_content' => $product['content'],
				'menu_order'   => $order,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_chele_price', $product['price'] );
		if ( '' !== $product['compare'] ) {
			update_post_meta( $post_id, '_chele_compare_price', $product['compare'] );
		}
		update_post_meta( $post_id, '_chele_currency', $product['currency'] );
		update_post_meta( $post_id, '_chele_pieces', $product['pieces'] );
		update_post_meta( $post_id, '_chele_fabric', $product['fabric'] );
		if ( '' !== $product['badge'] ) {
			update_post_meta( $post_id, '_chele_badge', $product['badge'] );
		}

		if ( isset( $collections[ $product['collection'] ] ) ) {
			wp_set_object_terms( $post_id, array( $collections[ $product['collection'] ] ), 'chele_collection' );
		}
	}
}

/**
 * Create core pages, the static homepage and the primary menu.
 */
function chele_seed_pages_and_menu() {
	$home_id    = chele_create_page( 'Home', '<!-- wp:paragraph --><p>Welcome to Chelé.</p><!-- /wp:paragraph -->' );
	$journal_id = chele_create_page( 'Journal', '' );

	$about_content  = '<!-- wp:heading {"level":2} --><h2>Our Story</h2><!-- /wp:heading -->';
	$about_content .= '<!-- wp:paragraph --><p>Founded in 2024, Chelé is a celebration of the beauty of Pakistani fashion — timeless designs, premium fabrics and flawlessly hand-finished details, made for ladies and girls alike.</p><!-- /wp:paragraph -->';
	$about_content .= '<!-- wp:paragraph --><p>Every Chelé piece begins with the fabric and ends with the hand. From the first sketch to the final stitch, we work with master karigars to bring elegance and tradition together in clothing you will reach for, treasure and pass on. Made with love, worn with pride.</p><!-- /wp:paragraph -->';
	$about_id = chele_create_page( 'About', $about_content );

	$contact_content  = '<!-- wp:heading {"level":2} --><h2>Get in Touch</h2><!-- /wp:heading -->';
	$contact_content .= '<!-- wp:paragraph --><p>We would love to hear from you. For orders, styling advice or custom bridal enquiries, reach us any day, 10am–8pm (PKT).</p><!-- /wp:paragraph -->';
	$contact_content .= '<!-- wp:paragraph --><p><strong>Email:</strong> hello@chele.pk<br><strong>WhatsApp:</strong> +92 300 0000000<br><strong>Instagram:</strong> @chele.official</p><!-- /wp:paragraph -->';
	$contact_id = chele_create_page( 'Contact', $contact_content );

	// Configure a static homepage with a dedicated posts page.
	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}
	if ( $journal_id ) {
		update_option( 'page_for_posts', $journal_id );
	}

	chele_build_primary_menu(
		array(
			'home'    => $home_id,
			'about'   => $about_id,
			'journal' => $journal_id,
			'contact' => $contact_id,
		)
	);
}

/**
 * Create a page by title if one does not already exist.
 *
 * @param string $title   Page title.
 * @param string $content Block content.
 * @return int Page ID.
 */
function chele_create_page( $title, $content = '' ) {
	$existing = chele_get_page_by_title( $title );
	if ( $existing ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		)
	);
}

/**
 * Build (or reuse) the primary navigation menu and assign it to the location.
 *
 * @param array $pages Slug => page ID map.
 */
function chele_build_primary_menu( $pages ) {
	$menu_name = 'Primary';
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
		// Don't rebuild an existing menu's items.
		if ( count( wp_get_nav_menu_items( $menu_id ) ) > 0 ) {
			chele_assign_primary_menu( $menu_id );
			return;
		}
	}

	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	// Home.
	if ( ! empty( $pages['home'] ) ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => __( 'Home', 'chele' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $pages['home'],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	// Shop (custom post type archive).
	$shop_url = get_post_type_archive_link( 'chele_product' );
	if ( $shop_url ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => __( 'Shop', 'chele' ),
				'menu-item-url'    => $shop_url,
				'menu-item-type'   => 'custom',
				'menu-item-status' => 'publish',
			)
		);
	}

	// About / Journal / Contact.
	$page_items = array(
		'about'   => __( 'About', 'chele' ),
		'journal' => __( 'Journal', 'chele' ),
		'contact' => __( 'Contact', 'chele' ),
	);
	foreach ( $page_items as $slug => $label ) {
		if ( empty( $pages[ $slug ] ) ) {
			continue;
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $pages[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	chele_assign_primary_menu( $menu_id );
}

/**
 * Assign a menu to the "primary" theme location.
 *
 * @param int $menu_id Menu term ID.
 */
function chele_assign_primary_menu( $menu_id ) {
	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations            = is_array( $locations ) ? $locations : array();
	$locations['primary'] = (int) $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
