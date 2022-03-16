<?php
/**
 * Models
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Theme\Core\Model;

/**
 * Models helper functions
 */
class Models {

	/**
     * Check if interface instance is empty
     *
     * @param Model $instance
     */
	public static function empty( Model $instance ) : bool {
		$empty = true;

		if ( $instance instanceof Model && $instance->id ) {
			$empty = false;
		}

		return $empty;
	}

	/**
     * Get model
     *
     * @param mixed $maybe_model
	 * @param mixed $instance
     *
     * @return mixed
     */
    public static function get_model( $maybe_model = null, $instance ) {
        if ( $maybe_model instanceof $instance ) {
            $instance = $maybe_model;
        } elseif ( is_numeric( $maybe_model ) ) {
			if ( method_exists( $instance, 'from_post' ) ) {
				$instance = $instance::from_post( $maybe_model );
			}
        }

        return $instance;
    }
}