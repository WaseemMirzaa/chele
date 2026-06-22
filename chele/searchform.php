<?php
/**
 * Custom search form.
 *
 * @package Chele
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="chele-search"><?php esc_html_e( 'Search for:', 'chele' ); ?></label>
	<input type="search" id="chele-search" class="search-field" placeholder="<?php esc_attr_e( 'Search Chelé…', 'chele' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'chele' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5"/></svg>
	</button>
</form>
