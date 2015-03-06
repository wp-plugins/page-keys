<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Model;

/**
 * Class Nonce
 *
 * @package tf\PageKeys\Model
 */
class Nonce {

	/**
	 * @var string
	 */
	private static $action = 'page_keys';

	/**
	 * @var string
	 */
	private static $name = '_wpnonce';

	/**
	 * Return the nonce name.
	 *
	 * @return string
	 */
	public static function get_name() {

		return self::$name;
	}

	/**
	 * Return the nonce value.
	 *
	 * @param string $action Optional. Nonce action. Defaults to 'page_keys'.
	 *
	 * @return string
	 */
	public static function get( $action = '' ) {

		$action = empty( $action ) ? self::$action : $action;

		return wp_create_nonce( $action );
	}

	/**
	 * Print the input element for the nonce. Unless $referer is set to FALSE a referer ipnut is printed, too.
	 *
	 * @param string $action  Optional. Nonce action. Defaults to 'page_keys'.
	 * @param bool   $referer Optional. Print referer field? Defaults to TRUE.
	 *
	 * @return void
	 */
	public static function print_field( $action = '', $referer = TRUE ) {

		self::get_field( $action, self::$name, $referer, TRUE );
	}

	/**
	 * Return the input element for the nonce. Unless $referer is set to FALSE a referer input is returned, too.
	 *
	 * @param string $action  Optional. Nonce action. Defaults to 'page_keys'.
	 * @param bool   $referer Optional. Print referer field? Defaults to TRUE.
	 * @param bool   $echo    Optional. Echo the field? Defaults to FALSE.
	 *
	 * @return string
	 */
	public static function get_field( $action = '', $referer = TRUE, $echo = FALSE ) {

		$action = empty( $action ) ? self::$action : $action;

		return wp_nonce_field( $action, self::$name, $referer, $echo );
	}

	/**
	 * Check if the given nonce is valid. If no nonce is given, the according field of the $_REQUEST superglobal is
	 * checked.
	 *
	 * @param string $nonce  Optional. Nonce value. Defaults to ''.
	 * @param string $action Optional. Nonce action. Defaults to 'page_keys'.
	 *
	 * @return bool
	 */
	public static function is_valid( $nonce = '', $action = '' ) {

		if ( empty( $nonce ) ) {
			if ( empty( $_REQUEST[ self::$name ] ) ) {
				return FALSE;
			}

			$nonce = $_REQUEST[ self::$name ];
		}

		$action = empty( $action ) ? self::$action : $action;

		return wp_verify_nonce( $nonce, $action );
	}

}
