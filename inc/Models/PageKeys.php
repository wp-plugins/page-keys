<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models;

use tf\PageKeys\ListTable;

/**
 * Class PageKeys
 *
 * @package tf\PageKeys\Models
 */
class PageKeys {

	/**
	 * @var Nonce[]
	 */
	private $nonces;

	/**
	 * @var SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Nonce[]      $nonces        Nonce objects.
	 * @param SettingsPage $settings_page Settings page model.
	 */
	public function __construct( array $nonces, SettingsPage $settings_page ) {

		$this->nonces = $nonces;

		$this->settings_page = $settings_page;
	}

	/**
	 * Add a new page key by AJAX request.
	 *
	 * @wp-hook wp_ajax_{$action}
	 *
	 * @return void
	 */
	public function add_ajax() {

		$error = NULL;

		if ( ! $this->nonces[ 'add' ]->is_valid() ) {
			$error = new SettingsErrors\InvalidNonce();
		} elseif ( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsErrors\NoPermissionToEdit();
		}

		if ( $error ) {
			$error->add();

			ob_start();
			settings_errors();
			$errors = ob_get_clean();

			$data = (object) compact( 'errors' );
			wp_send_json_error( $data );
		}

		$list_table = new ListTable( $this->settings_page );

		$data = (object) array(
			'row' => $list_table->get_single_row(),
		);
		wp_send_json_success( $data );
	}

	/**
	 * Delete the page key given in the AJAX request.
	 *
	 * @wp-hook wp_ajax_{$action}
	 *
	 * @return void
	 */
	public function delete_ajax() {

		$response = $this->delete();

		ob_start();
		settings_errors();
		$errors = ob_get_clean();

		$data = (object) compact( 'errors' );

		if ( $response ) {
			$data->id = filter_input( INPUT_POST, 'id' );
			wp_send_json_success( $data );
		}

		wp_send_json_error( $data );
	}

	/**
	 * Delete the page key given in the $_REQUEST superglobal.
	 *
	 * @return bool
	 */
	private function delete() {

		if ( ! $this->settings_page->current_user_can( 'edit' ) ) {
			$error = new SettingsErrors\NoPermissionToEdit();
			$error->add();

			return FALSE;
		}

		if ( empty( $_REQUEST[ 'page_key' ] ) ) {
			$error = new SettingsErrors\MissingPageKey();
			$error->add();

			return FALSE;
		}

		if ( ! $this->nonces[ 'delete' ]->is_valid() ) {
			$error = new SettingsErrors\InvalidNonce();
			$error->add();

			return FALSE;
		}

		$page_key = urldecode( $_REQUEST[ 'page_key' ] );
		$pages = Option::get();
		if ( array_key_exists( $page_key, $pages ) ) {
			unset( $pages[ $page_key ] );

			$error = new SettingsErrors\PageKeyDeleted( $page_key );
			$error->add();

			return Option::update( $pages );
		}

		$error = new SettingsErrors\InvalidPageKey( $page_key );
		$error->add();

		return FALSE;
	}

}
