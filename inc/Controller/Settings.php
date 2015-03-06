<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controller;

use tf\PageKeys\Model;
use tf\PageKeys\Model\SettingsError;

/**
 * Class Settings
 *
 * @package tf\PageKeys\Controller
 */
class Settings {

	/**
	 * Register the settings.
	 *
	 * @wp-hook admin_init
	 *
	 * @return void
	 */
	public function register_settings() {

		$option_name = Model\Option::get_name();
		register_setting(
			$option_name,
			$option_name,
			array( $this, 'sanitize_data' )
		);
	}

	/**
	 * Sanitize the settings data.
	 *
	 * @see register_settings()
	 *
	 * @param array $data Settings data.
	 *
	 * @return array
	 */
	public function sanitize_data( $data ) {

		$sanitized_data = array();

		foreach ( $data as $page_key => $page ) {
			if ( isset( $page[ 'page_key' ] ) ) {
				if ( $page[ 'page_key' ] === '' ) {
					continue;
				}

				$page_key = $page[ 'page_key' ];
			}

			$page_id = '';
			if ( isset( $page[ 'page_id' ] ) ) {
				$page_id = intval( $page[ 'page_id' ] );
				if ( $page_id < 1 ) {
					$page_id = '';
				}
			}

			if ( empty( $sanitized_data[ $page_key ] ) ) {
				$sanitized_data[ $page_key ] = compact(
					'page_id'
				);
			} else {
				$error = new SettingsError\DuplicatePageKey( $page_key, $page_id );
				$error->add();
			}
		}

		return $sanitized_data;
	}

}
