<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys;

use tf\PageKeys\Controller;

/**
 * Class Plugin
 *
 * @package tf\PageKeys
 */
class Plugin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see initialize()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Initialize the controllers.
	 *
	 * @see initialize()
	 *
	 * @return void
	 */
	public function initialize() {

		$general_controller = new Controller\General();
		$general_controller->initialize();

		if ( is_admin() ) {
			$admin_controller = new Controller\Admin( $this->file );
			$admin_controller->initialize();
		}
	}

}
