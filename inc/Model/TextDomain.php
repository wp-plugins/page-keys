<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model;

/**
 * Class TextDomain
 *
 * @package tf\PageKeys\Model
 */
class TextDomain {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @see tf\PageKeys\Controller\Admin::__construct()
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->path = plugin_basename( $file );
		$this->path = dirname( $this->path ) . '/languages';
	}

	/**
	 * Load the text domain.
	 *
	 * @see tf\PageKeys\Controller\Admin::init()
	 *
	 * @return bool
	 */
	public function load() {

		return load_plugin_textdomain( 'page-keys', FALSE, $this->path );
	}

}
