<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controller;

use tf\PageKeys\Model;

/**
 * Class General
 *
 * @package tf\PageKeys\Controller
 */
class General {

	/**
	 * Wire up all general functions.
	 *
	 * @see tf\PageKeys\Plugin::initialize()
	 *
	 * @return void
	 */
	public function initialize() {

		$page = new Model\Page();
		add_action( 'trashed_post', array( $page, 'delete_from_page_keys' ) );
		add_action( 'deleted_post', array( $page, 'delete_from_page_keys' ) );
	}

	/**
	 * Return the page for the given key, if it exists.
	 *
	 * @param string $key Page key.
	 *
	 * @return \WP_Post|NULL
	 */
	public static function get_page_by_key( $key ) {

		$pages = Model\Option::get();
		if ( isset( $pages[ $key ][ 'page_id' ] ) ) {
			return get_post( $pages[ $key ][ 'page_id' ] );
		}

		return NULL;
	}

}
