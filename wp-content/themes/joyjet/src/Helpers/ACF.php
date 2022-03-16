<?php
/**
 * ACF
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Error;

/**
 * ACF Helper functions
 */
class ACF {

    /**
     * Get default term ID by slug
     *
     * @param [type] $term_slug
     * @param [type] $taxonomy
     *
     * @return int|string
     */
	public static function default_term( $term_slug, $taxonomy ) {
		$default = get_term_by( 'slug', $term_slug, $taxonomy );

		return ! empty( $default->term_id ) ? $default->term_id : '';
	}

    /**
     * Update fields that stores a serialized array as data.
     *
     * @param string|int $selector acf field selector
     * @param string|int $object_id
     * @param mixed      $value
     * @param string     $key meant for updating assoc array field.
     * @param string     $action
     *
     * @return bool
     */
	public static function update_array_field( $selector, $object_id, $value, $key = null, $action ) : bool {
		$old_meta_array = (array) get_field( $selector, $object_id, false );
		$new_meta_array = $old_meta_array;
        $update         = false;

		switch ( $action ) {
                /**
                 * Add new ID to meta IDs array
                 */
			case 'insert':
				if ( $old_meta_array ) {
                    if ( $key ) {
                        $new_meta_array[ $key ] = $value;
                    } else {
                        array_push( $new_meta_array, $value );
                    }
					$update = update_field( $selector, $new_meta_array, $object_id );
				} else {
                    if ( $key ) {
                        $new_meta_array[ $key ] = $value;
                    } else {
                        $new_meta_array[] = $value;
                    }
					$update = update_field( $selector, $new_meta_array, $object_id );
				}
                break;

				/**
				 * Remove ID from meta IDs array
				 */
			case 'remove':
                if ( $key ) {
                    $index = $key;
                } else {
                    $index = array_search( $value, $old_meta_array, true );
                }

				if ( false !== $index ) {
					unset( $new_meta_array[ $index ] );
				}

				$update = update_field( $selector, $new_meta_array, $object_id );

                break;
		}

        return $update;
	}

    /**
     * Simple field condition logic
     *
     * @param string $field
     * @param string $value
     * @param string $operator
     *
     * @return array
     */
    public static function field_condition( string $field, string $value, string $operator = '==' ) : array {
		return array(
			array(
				array(
					'field'    => $field,
					'operator' => $operator,
					'value'    => $value,
				),
			),
		);
    }

    /**
     * ACF Update repeater field
     *
     * @param integer $post_id The post ID
     * @param string  $repeater The repeater Selector
     * @param integer $index The repeater index to update
     * @param string  $field The repeater subfield to update
     * @param mixed   $value The subfield value
     *
     * @return boolean
     */
    public static function update_repeater_field( int $post_id, string $repeater, int $index, string $field, $value ) : bool {
        $notificate = (bool) update_sub_field(
            array( $repeater, $index, $field ),
            $value,
            $post_id
        );

        return $notificate;
    }

	/**
	 * Get ACF fields user ID
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	public static function user_id( int $user_id ) : string {
		return "user_$user_id";
	}
}