<?php # -*- coding: utf-8 -*-

if ( ! function_exists( 'get_page_by_key' ) ) :

	/**
	 * Return the page for the given key, if it exists.
	 *
	 * @see tf\PageKeys\Controller\General::get_page_by_key()
	 *
	 * @param string $key Page key.
	 *
	 * @return \WP_Post|NULL
	 */
	function get_page_by_key( $key ) {

		return \tf\PageKeys\Controller\General::get_page_by_key( $key );
	}

endif;
