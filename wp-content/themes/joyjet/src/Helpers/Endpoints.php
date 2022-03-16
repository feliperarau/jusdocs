<?php
/**
 * Endpoint helpers
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Error;
use Throwable;

/**
 * Endpoints helper functions
 */
class Endpoints {
	/**
	 * Check for required fields on form submission. (deprecated)
	 *
	 * @param array  $required_fields
	 * @param array  $fields
	 * @param string $err_msg
	 *
	 * @throws Error In case a required field is empty.
	 *
	 * @return void
	 */
	public static function check_required_fields( array $required_fields, array $fields, string $err_msg = '' ) : void {
		$default_err_msg = __( 'Um dos campos obrigatórios não foram preenchidos.', 'joyjet' );

		foreach ( $fields as $index => $field_value ) {
			if ( in_array( $index, $required_fields, true ) && empty( $field_value ) ) {
				throw new Error( $err_msg ?? $default_err_msg, 401 );
			}
		}
	}

	/**
	 * Validate required fields
	 *
	 * @param array $required_fields
	 * @param array $client_fields
	 *
	 * @return boolean
	 */
	public static function validate_required_fields( $required_fields, $client_fields ) : bool {
		$ok = true;

		foreach ( $required_fields as $field_key ) {
			if ( empty( $client_fields[ $field_key ] ) ) {
				$ok = false;
				break;
			}
		}

		return $ok;
	}
}