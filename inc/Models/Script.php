<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Models;

/**
 * Class Script
 *
 * @package tf\PageKeys\Models
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
	 * @var string[]
	 */
	private $nonces;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param string  $file   Main plugin file.
	 * @param Nonce[] $nonces Nonce objects.
	 */
	public function __construct( $file, array $nonces ) {

		$this->file = $file;

		foreach ( $nonces as $action => $nonce ) {
			$this->nonces[ $action ] = $nonce->get();
		}
	}

	/**
	 * Return all actions.
	 *
	 * @return string[]
	 */
	public function get_actions() {

		return $this->actions;
	}

	/**
	 * Return the action name for the given key.
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
	 * @wp-hook admin_print_scripts-{$hook_suffix}
	 *
	 * @return void
	 */
	public function enqueue() {

		$url = plugin_dir_url( $this->file );
		$infix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$file = 'assets/js/admin' . $infix . '.js';
		$path = plugin_dir_path( $this->file );
		$version = filemtime( $path . $file );
		wp_enqueue_script(
			$this->handle,
			$url . $file,
			array( 'jquery' ),
			$version,
			TRUE
		);

		$data = array(
			'actions'  => $this->actions,
			'messages' => array(
				'delete' => __( 'Do you really want to delete this page key?', 'page-keys' ),
				'unload' => __( 'There are unsaved changes. Do you really want to leave?', 'page-keys' ),
			),
			'nonces'   => $this->nonces,
			'url'      => admin_url( 'admin-ajax.php', 'relative' ),
		);
		wp_localize_script( $this->handle, 'tfPageKeysData', $data );
	}

}
