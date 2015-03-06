<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model\SettingsError;

/**
 * Class InvalidNonce
 *
 * @package tf\PageKeys\Model\SettingsError
 */
class InvalidNonce extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 */
	public function __construct() {

		$this->set_slug( 'Invalid Nonce' );

		$this->set_code( 'invalid-nonce' );

		$message = _x( 'Nonce invalid!', 'Settings error message', 'page-keys' );
		$this->set_message( $message );
	}

}
