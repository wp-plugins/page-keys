<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model;

use tf\PageKeys\Controller;
use tf\PageKeys\View;

/**
 * Class SettingsPage
 *
 * @package tf\PageKeys\Model
 */
class SettingsPage {

	/**
	 * @var string[]
	 */
	private $capabilities;

	/**
	 * @var string
	 */
	private $slug = 'page_keys';

	/**
	 * @var string
	 */
	private $title;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\PageKeys\Controller\Admin::init()
	 */
	public function __construct() {

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

		$this->title = _x( 'Page Keys', 'Settings page title', 'page-keys' );
	}

	/**
	 * Return the page slug.
	 *
	 * @see tf\PageKeys\View\AdminNotice::render()
	 *
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;
	}

	/**
	 * Return the page title.
	 *
	 * @see tf\PageKeys\View\AdminNotice::render()
	 *
	 * @return string
	 */
	public function get_title() {

		return $this->title;
	}

	/**
	 * Add the settings page to the Pages menu.
	 *
	 * @wp-hook admin_menu
	 *
	 * @return void
	 */
	public function add() {

		$menu_title = _x( 'Page Keys', 'Menu item title', 'page-keys' );
		$controller = new Controller\Action( $this );
		$view = new View\SettingsPage( $this, $controller );
		add_pages_page(
			$this->title,
			$menu_title,
			$this->capabilities[ 'list' ],
			$this->slug,
			array( $view, 'render' )
		);
	}

	/**
	 * Check if the current user has the capability required to perform the given action.
	 *
	 * @see tf\PageKeys\View\AdminNotice::render()
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
	 * @see tf\PageKeys\ListTable::column_page_key()
	 *
	 * @return string
	 */
	public function get_add_page_key_url() {

		return $this->get_action_url( 'add' );
	}

	/**
	 * Return the URL for deleting the given page key.
	 *
	 * @see tf\PageKeys\ListTable::column_page_key()
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
			'page'     => $this->slug,
			'action'   => $action,
			'_wpnonce' => Nonce::get(),
		);
		if ( $page_key !== '' ) {
			$query_args[ 'page_key' ] = $page_key;
		}
		$url = add_query_arg( $query_args, 'edit.php?post_type=page' );

		return admin_url( $url );
	}

}
