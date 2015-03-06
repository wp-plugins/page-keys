<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model\SettingsError;

/**
 * Class PageKeyDeleted
 *
 * @package tf\PageKeys\Model\SettingsError
 */
class PageKeyDeleted extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $page_key Page key.
	 */
	public function __construct( $page_key ) {

		$this->set_slug( 'Page Key deleted' );

		$this->set_code( 'page-key-deleted' );

		$message = _x( "Page key '%s' permanently deleted.", 'Settings error message, %s=page key', 'page-keys' );
		$message = sprintf( $message, $page_key );
		$this->set_message( $message );

		$this->set_type( 'updated' );
	}

}
