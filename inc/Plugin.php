<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys;

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
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function initialize() {

		$page = new Models\Page();
		$page_controller = new Controllers\Page( $page );
		$page_controller->initialize();

		if ( is_admin() ) {
			$nonce_name = '_wpnonce';
			$nonces = array(
				'add'    => new Models\Nonce( 'add_page_key', $nonce_name ),
				'delete' => new Models\Nonce( 'delete_page_key', $nonce_name ),
			);

			$text_domain = new Models\TextDomain( $this->file );
			$text_domain->load();

			$settings = new Models\Settings();
			$settings_page = new Models\SettingsPage( $nonces );
			$settings_page_view = new Views\SettingsPage( $settings_page );
			$settings_controller = new Controllers\Settings( $settings, $settings_page_view );
			$settings_controller->initialize();

			$admin_notice_view = new Views\AdminNotice( $settings_page );
			$admin_notice_controller = new Controllers\AdminNotice( $admin_notice_view );
			$admin_notice_controller->initialize();

			$script = new Models\Script( $this->file, $nonces );
			$script_controller = new Controllers\Script( $script, $settings_page );
			$script_controller->initialize();

			$page_keys = new Models\PageKeys( $nonces, $settings_page );

			$ajax_controller = new Controllers\AJAX( $script, $page_keys );
			$ajax_controller->initialize();
		}
	}

}
