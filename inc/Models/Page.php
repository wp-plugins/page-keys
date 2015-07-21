<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models;

/**
 * Class Page
 *
 * @package tf\PageKeys\Models
 */
class Page {

	/**
	 * Delete a trashed or deleted page from the page keys.
	 *
	 * @wp-hook trashed_post
	 * @wp-hook deleted_post
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	public function delete_from_page_keys( $post_id ) {

		$post = get_post( $post_id );
		if (
			! isset( $post->post_type )
			|| $post->post_type !== 'page'
		) {
			return FALSE;
		}

		$pages = Option::get();
		if ( ! $pages ) {
			return FALSE;
		}

		$update = FALSE;

		foreach ( $pages as $page_key => $page ) {
			if (
				isset( $page[ 'page_id' ] )
				&& $page[ 'page_id' ] == $post_id
			) {
				$pages[ $page_key ][ 'page_id' ] = '';
				$update = TRUE;
			}
		}

		if ( $update ) {
			Option::update( $pages );
		}

		return $update;
	}

}
