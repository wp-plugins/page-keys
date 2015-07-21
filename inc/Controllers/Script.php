<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controllers;

use tf\PageKeys\Models\Script as Model;
use tf\PageKeys\Models\SettingsPage;

/**
 * Class Script
 *
 * @package tf\PageKeys\Controllers
 */
class Script {

	/**
	 * @var Model
	 */
	private $model;

	/**
	 * @var string
	 */
	private $settings_page_slug;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model        $model         Model.
	 * @param SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Model $model, SettingsPage $settings_page ) {

		$this->model = $model;
		$this->settings_page_slug = $settings_page->get_slug();
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'admin_print_scripts-pages_page_' . $this->settings_page_slug, array( $this->model, 'enqueue' ) );
	}

}
