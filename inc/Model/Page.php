<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model;

/**
 * Class Page
 *
 * @package tf\PageKeys\Model
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
	 * @return void
	 */
	public function delete_from_page_keys( $post_id ) {

		$post = get_post( $post_id );
		if (
			! isset( $post->post_type )
			|| $post->post_type !== 'page'
		) {
			return;
		}

		$pages = Option::get();
		if ( ! $pages ) {
			return;
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
	}

}
