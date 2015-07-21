<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models\SettingsErrors;

/**
 * Class DuplicatePageKey
 *
 * @package tf\PageKeys\Models\SettingsErrors
 */
class DuplicatePageKey extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $page_key Page key.
	 * @param int    $page_id  Page ID.
	 */
	public function __construct( $page_key, $page_id ) {

		$this->set_slug( 'Duplicate Page Key' );

		$this->set_code( 'duplicate-page-key' );

		$message = _x(
			"Cannot map page key '%s' to page ID '%d'! Page key already set.",
			'Settings error message, %s=page key, %d=page ID', 'page-keys'
		);
		$message = sprintf( $message, $page_key, $page_id );
		$this->set_message( $message );
	}

}
