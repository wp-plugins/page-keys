<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Controllers;

use tf\PageKeys\Models\Page as Model;

/**
 * Class Page
 *
 * @package tf\PageKeys\Controllers
 */
class Page {

	/**
	 * @var Model
	 */
	private $model;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model $model Model.
	 */
	public function __construct( Model $model ) {

		$this->model = $model;
	}

	/**
	 * Wire up all functions.
	 *
	 * @return void
	 */
	public function initialize() {

		add_action( 'trashed_post', array( $this->model, 'delete_from_page_keys' ) );
		add_action( 'deleted_post', array( $this->model, 'delete_from_page_keys' ) );
	}

}
