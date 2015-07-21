<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controllers;

use tf\PageKeys\Models;
use tf\PageKeys\Models\SettingsErrors;

/**
 * Class AJAX
 *
 * @package tf\PageKeys\Controllers
 */
class AJAX {

	/**
	 * @var array
	 */
	private $actions;

	/**
	 * @var Models\PageKeys
	 */
	private $page_keys;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Models\Script   $script    Script model.
	 * @param Models\PageKeys $page_keys Page keys model.
	 */
	public function __construct( Models\Script $script, Models\PageKeys $page_keys ) {

		$this->actions = $script->get_actions();

		$this->page_keys = $page_keys;
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'wp_ajax_' . $this->actions[ 'add' ], array( $this->page_keys, 'add_ajax' ) );
		add_action( 'wp_ajax_' . $this->actions[ 'delete' ], array( $this->page_keys, 'delete_ajax' ) );
	}

}
