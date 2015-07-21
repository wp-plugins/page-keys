<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models\SettingsErrors;

/**
 * Class MissingPageKey
 *
 * @package tf\PageKeys\Models\SettingsErrors
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
