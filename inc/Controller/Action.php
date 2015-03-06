<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controller;

use tf\PageKeys\ListTable;
use tf\PageKeys\Model;
use tf\PageKeys\Model\SettingsError;

/**
 * Class Action
 *
 * @package tf\PageKeys\Controller
 */
class Action {

	/**
	 * @var Model\SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model\SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Model\SettingsPage $settings_page) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Handle a potential user request.
	 *
	 * @see tf\PageKeys\View\SettingsPage::render()
	 *
	 * @return bool
	 */
	public function maybe_take_action() {

		if ( empty( $_REQUEST[ 'action' ] ) ) {
			return FALSE;
		}

		$this->reset_request_uri();

		switch ( (string) $_REQUEST[ 'action' ] ) {
			case 'delete':
				return $this->delete_page_key();

			default:
				return FALSE;
		}
	}

	/**
	 * Remove unnecessary query arguments from the request URI.
	 *
	 * @see maybe_take_action()
	 *
	 * @return void
	 */
	private function reset_request_uri() {

		$slug = $this->settings_page->get_slug();
		$_SERVER[ 'REQUEST_URI' ] = admin_url( 'edit.php?post_type=page&page=' . $slug, 'relative' );
	}

	/**
	 * Delete the page key given in the $_REQUEST superglobal.
	 *
	 * @see maybe_take_action()
	 * @see delete_page_key_ajax()
	 *
	 * @return bool
	 */
	private function delete_page_key() {

		if ( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsError\NoPermissionToEdit();
			$error->add();

			return FALSE;
		}

		if ( empty( $_REQUEST[ 'page_key' ] ) ) {
			$error = new SettingsError\MissingPageKey();
			$error->add();

			return FALSE;
		}

		if ( ! Model\Nonce::is_valid() ) {
			$error = new SettingsError\InvalidNonce();
			$error->add();

			return FALSE;
		}

		$page_key = urldecode( $_REQUEST[ 'page_key' ] );
		$pages = Model\Option::get();
		if ( array_key_exists( $page_key, $pages ) ) {
			unset( $pages[ $page_key ] );

			$error = new SettingsError\PageKeyDeleted( $page_key );
			$error->add();

			return Model\Option::update( $pages );
		}

		$error = new SettingsError\InvalidPageKey( $page_key );
		$error->add();

		return FALSE;
	}

	/**
	 * Delete the page key given in the AJAX request.
	 *
	 * @wp-hook wp_ajax_{$action}
	 *
	 * @return void
	 */
	public function delete_page_key_ajax() {

		$response = $this->delete_page_key();

		ob_start();
		settings_errors();
		$errors = ob_get_clean();

		$data = (object) compact(
			'errors'
		);

		if ( $response ) {
			$data->id = filter_input( INPUT_POST, 'id' );
			wp_send_json_success( $data );
		}

		wp_send_json_error( $data );
	}

	/**
	 * Add a new page key by AJAX request.
	 *
	 * @wp-hook wp_ajax_{$action}
	 *
	 * @return void
	 */
	public function add_page_key_ajax() {

		$error = NULL;

		if ( ! Model\Nonce::is_valid() ) {
			$error = new SettingsError\InvalidNonce();
		} elseif( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsError\NoPermissionToEdit();
		}

		if ( $error !== NULL ) {
			$error->add();

			ob_start();
			settings_errors();
			$errors = ob_get_clean();

			$data = (object) compact(
				'errors'
			);
			wp_send_json_error( $data );

		}

		$list_table = new ListTable( $this->settings_page );

		$data = (object) array(
			'row' => $list_table->get_single_row(),
		);
		wp_send_json_success( $data );
	}

}
