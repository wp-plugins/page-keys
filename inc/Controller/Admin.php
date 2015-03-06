<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controller;

use tf\PageKeys\Model;
use tf\PageKeys\View;

/**
 * Class Admin
 *
 * @package tf\PageKeys\Controller
 */
class Admin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\PageKeys\Plugin::initialize()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Wire up all backend-specific functions.
	 *
	 * @see tf\PageKeys\Plugin::initialize()
	 *
	 * @return void
	 */
	public function initialize() {

		$text_domain = new Model\TextDomain( $this->file );
		$text_domain->load();

		$settings_page = new Model\SettingsPage();
		add_action( 'admin_menu', array( $settings_page, 'add' ), PHP_INT_MAX );

		$settings_controller = new Settings();
		add_action( 'admin_init', array( $settings_controller, 'register_settings' ) );

		$script = new Model\Script( $this->file );
		$slug = $settings_page->get_slug();
		add_action( 'admin_print_scripts-pages_page_' . $slug, array( $script, 'enqueue' ) );

		/**
		 * Filter whether to show an admin notice if not all registered page keys have a page assigned.
		 *
		 * @param bool $show_admin_notice Show admin notice?
		 */
		if ( apply_filters( 'page_keys_show_admin_notice', TRUE ) ) {
			$admin_notice = new View\AdminNotice( $settings_page );
			add_action( 'admin_notices', array( $admin_notice, 'render' ) );
		}

		$action_controller = new Action( $settings_page );
		$action = $script->get_action( 'add' );
		add_action( 'wp_ajax_' . $action, array( $action_controller, 'add_page_key_ajax' ) );
		$action = $script->get_action( 'delete' );
		add_action( 'wp_ajax_' . $action, array( $action_controller, 'delete_page_key_ajax' ) );
	}

}
