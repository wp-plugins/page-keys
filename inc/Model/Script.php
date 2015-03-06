<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model;

/**
 * Class Script
 *
 * @package tf\PageKeys\Model
 */
class Script {

	/**
	 * @var string[]
	 */
	private $actions = array(
		'add'    => 'page_keys_add_page_key',
		'delete' => 'page_keys_delete_page_key',
	);

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $handle = 'page-keys-admin';

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Return action name for given key.
	 *
	 * @param string $key Action key.
	 *
	 * @return string
	 */
	public function get_action( $key ) {

		return ! empty( $this->actions[ $key ] ) ? (string) $this->actions[ $key ] : '';
	}

	/**
	 * Enqueue the script file.
	 *
	 * @wp-hook admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue() {

		$url = plugin_dir_url( $this->file );
		$file = 'assets/js/admin.js';
		$path = plugin_dir_path( $this->file );
		$version = filemtime( $path . $file );
		wp_enqueue_script(
			$this->handle,
			$url . $file,
			array( 'jquery' ),
			$version
		);

		$data = array(
			'actions'  => $this->actions,
			'messages' => array(
				'delete' => __( 'Do you really want to delete this page key?', 'page-keys' ),
				'unload' => __( 'There are unsaved changes. Do you really want to leave?', 'page-keys' ),
			),
			'nonce'    => Nonce::get(),
			'url'      => admin_url( 'admin-ajax.php', 'relative' ),
		);
		wp_localize_script( $this->handle, 'tfPageKeysData', $data );
	}

}
