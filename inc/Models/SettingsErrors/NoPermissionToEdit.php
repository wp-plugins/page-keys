<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models\SettingsErrors;

/**
 * Class NoPermissionToEdit
 *
 * @package tf\PageKeys\Models\SettingsErrors
 */
class NoPermissionToEdit extends SettingsError {

	/**
	 * Constructor. Set up the properties.
	 */
	public function __construct() {

		$this->set_slug( 'No Permission to Edit' );

		$this->set_code( 'no-permission-to-edit' );

		$message = _x( "You don't have permission to edit page keys.", 'Settings error message', 'page-keys' );
		$this->set_message( $message );
	}

}
