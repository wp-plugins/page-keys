<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models;

use tf\PageKeys\Controllers;
use tf\PageKeys\Views;

/**
 * Class SettingsPage
 *
 * @package tf\PageKeys\Models
 */
class SettingsPage {

	/**
	 * @var string[]
	 */
	private $capabilities;

	/**
	 * @var Nonce[]
	 */
	private $nonces;

	/**
	 * @var string
	 */
	private $slug = 'page_keys';

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Nonce[] $nonces Nonce objects.
	 */
	public function __construct( array $nonces ) {

		$this->nonces = $nonces;

		/**
		 * Filter the capability required to list the page keys.
		 *
		 * @param string $capability Capability required to list the page keys.
		 */
		$this->capabilities[ 'list' ] = apply_filters( 'list_page_keys_capability', 'edit_pages' );

		/**
		 * Filter the capability required to edit the page keys.
		 *
		 * @param string $capability Capability required to edit the page keys.
		 */
		$this->capabilities[ 'edit' ] = apply_filters( 'edit_page_keys_capability', 'edit_published_pages' );
	}

	/**
	 * Return the capability for the given action.
	 *
	 * @param string $action Capability action.
	 *
	 * @return string
	 */
	public function get_capability( $action ) {

		return empty( $this->capabilities[ $action ] ) ? 'do_not_allow' : $this->capabilities[ $action ];
	}

	/**
	 * Return the page slug.
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

	/**
	 * Check if the current user has the capability required to perform the given action.
	 *
	 * @param string $action Action name.
	 *
	 * @return bool
	 */
	public function current_user_can( $action ) {

		return empty( $this->capabilities[ $action ] ) ? FALSE : current_user_can( $this->capabilities[ $action ] );
	}

	/**
	 * Return the URL for adding a page key.
	 *
	 * @return string
	 */
	public function get_add_page_key_url() {

		return $this->get_action_url( 'add' );
	}

	/**
	 * Return the URL for deleting the given page key.
	 *
	 * @param string $page_key Page key.
	 *
	 * @return string
	 */
	public function get_delete_page_key_url( $page_key ) {

		return $this->get_action_url( 'delete', $page_key );
	}

	/**
	 * Return the URL for the given action and optionally given page key.
	 *
	 * @param string $action   Action. Valid actions are 'add' and 'delete'.
	 * @param string $page_key Optional. Page key. Defaults to ''.
	 *
	 * @return string
	 */
	private function get_action_url( $action, $page_key = '' ) {

		$valid_actions = array(
			'add',
			'delete',
		);
		if ( ! in_array( $action, $valid_actions ) ) {
			return '';
		}

		$query_args = array(
			'page'   => $this->slug,
			'action' => $action,
		);
		if ( isset( $this->nonces[ $action ] ) ) {
			$query_args[ '_wpnonce' ] = $this->nonces[ $action ]->get();
		}
		if ( $page_key ) {
			$query_args[ 'page_key' ] = $page_key;
		}

		$url = add_query_arg( $query_args, 'edit.php?post_type=page' );

		return admin_url( $url );
	}

}
