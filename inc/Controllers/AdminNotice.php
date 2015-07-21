<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controllers;

use tf\PageKeys\Views\AdminNotice as View;

/**
 * Class AdminNotice
 *
 * @package tf\PageKeys\Controllers
 */
class AdminNotice {

	/**
	 * @var View
	 */
	private $view;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param View $view Admin notice view.
	 */
	public function __construct( View $view ) {

		$this->view = $view;
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		/**
		 * Filter whether to show an admin notice if not all registered page keys have a page assigned.
		 *
		 * @param bool $show_admin_notice Show admin notice?
		 */
		if ( apply_filters( 'page_keys_show_admin_notice', TRUE ) ) {
			add_action( 'admin_notices', array( $this->view, 'render' ) );
		}
	}

}
