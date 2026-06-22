<?php
/**
 * The header: document head, announcement bar and site navigation.
 *
 * @package Chele
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="chele-loader" data-loader aria-hidden="true">
	<span class="loader-mark">Chelé</span>
	<span class="loader-bar"></span>
</div>
<div class="grain" aria-hidden="true"></div>
<div class="scroll-progress" data-progress aria-hidden="true"></div>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'chele' ); ?></a>

<div id="page" class="site">

	<?php $announcement = chele_option( 'announcement', __( 'Complimentary delivery across Pakistan · Worldwide shipping available', 'chele' ) ); ?>
	<?php if ( $announcement ) : ?>
		<div class="announcement-bar">
			<div class="announcement-track">
				<?php for ( $i = 0; $i < 2; $i++ ) : ?>
					<span><?php echo esc_html( $announcement ); ?></span>
					<span class="announcement-dot" aria-hidden="true">✦</span>
					<span><?php esc_html_e( 'Made with love, worn with pride', 'chele' ); ?></span>
					<span class="announcement-dot" aria-hidden="true">✦</span>
				<?php endfor; ?>
			</div>
		</div>
	<?php endif; ?>

	<header id="masthead" class="site-header" data-header>
		<div class="header-inner">

			<button class="nav-toggle" aria-controls="primary-navigation" aria-expanded="false" data-nav-toggle>
				<span class="nav-toggle-bar"></span>
				<span class="nav-toggle-bar"></span>
				<span class="nav-toggle-bar"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'chele' ); ?></span>
			</button>

			<div class="site-branding">
				<?php chele_site_branding(); ?>
			</div>

			<nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary', 'chele' ); ?>" data-nav>
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nav-menu',
							'container'      => false,
							'depth'          => 2,
						)
					);
				} else {
					echo '<ul class="nav-menu">';
					echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'chele' ) . '</a></li>';
					$shop = get_post_type_archive_link( 'chele_product' );
					if ( $shop ) {
						echo '<li><a href="' . esc_url( $shop ) . '">' . esc_html__( 'Shop', 'chele' ) . '</a></li>';
					}
					echo '</ul>';
				}
				?>
			</nav>

			<div class="header-actions">
				<button class="header-action" data-search-toggle aria-label="<?php esc_attr_e( 'Search', 'chele' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5"/></svg>
				</button>
				<?php if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_cart_url' ) ) : ?>
					<a class="header-action" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'chele' ); ?>">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8zM9 8V6a3 3 0 0 1 6 0v2"/></svg>
					</a>
				<?php endif; ?>
			</div>

		</div>

		<div class="header-search" data-search-panel hidden>
			<?php get_search_form(); ?>
		</div>
	</header>

	<div class="nav-overlay" data-nav-overlay hidden></div>

	<main id="content" class="site-content">
