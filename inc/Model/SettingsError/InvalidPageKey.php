<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model\SettingsError;

/**
 * Class InvalidPageKey
 *
 * @package tf\PageKeys\Model\SettingsError
 */
class InvalidPageKey extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $page_key Page key.
	 */
	public function __construct( $page_key ) {

		$this->set_slug( 'Invalid Page Key' );

		$this->set_code( 'invalid-page-key' );

		$message = _x( "Page key '%s' invalid!", 'Settings error message, %s=page key', 'page-keys' );
		$message = sprintf( $message, $page_key );
		$this->set_message( $message );
	}

}
