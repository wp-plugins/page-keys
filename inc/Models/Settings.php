<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models;

/**
 * Class Settings
 *
 * @package tf\PageKeys\Models
 */
class Settings {

	/**
	 * Register the settings.
	 *
	 * @wp-hook admin_init
	 *
	 * @return void
	 */
	public function register() {

		$option_name = Option::get_name();
		register_setting(
			$option_name,
			$option_name,
			array( $this, 'sanitize' )
		);
	}

	/**
	 * Sanitize the settings data.
	 *
	 * @param array $data Settings data.
	 *
	 * @return array
	 */
	public function sanitize( $data ) {

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
				$error = new SettingsErrors\DuplicatePageKey( $page_key, $page_id );
				$error->add();
			}
		}

		return $sanitized_data;
	}

}
