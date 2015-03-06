<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model\SettingsError;

/**
 * Class MissingPageKey
 *
 * @package tf\PageKeys\Model\SettingsError
 */
class MissingPageKey extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 */
	public function __construct() {

		$this->set_slug( 'Missing Page Key' );

		$this->set_code( 'missing-page-key' );

		$message = _x( 'No page key given!', 'Settings error', 'page-keys' );
		$this->set_message( $message );
	}

}
