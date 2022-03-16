<?php
/**
 * Interfaces utils class
 *
 * @package joyjet
 */

namespace Theme\Helpers;

/**
 * Interfaces related helper class
 */
class Interfaces {

    /**
     * Insert post
     *
     * @param string $title
     * @param string $cpt
     * @param array  $meta
     *
     * @return int
     */
    public static function insert( $title, $cpt, $meta = array() ) : int {
		$post_id = wp_insert_post(
            array(
				'post_type'   => $cpt,
				'post_status' => 'publish',
				'post_author' => 1, // Master Admin
			),
            true
        );

		if ( empty( $post_id ) || is_wp_error( $post_id ) ) {
			return 0;
		}

        foreach ( $meta as $key => $value ) {
            $update = update_field( $key, $value, $post_id );

            if ( ! $update ) {
                return 0;
            }
        }

        $update = wp_update_post(
            array(
				'ID'         => $post_id,
				'post_title' => sprintf( '#%s - %s', $post_id, $title ),
            )
        );

        if ( ! $update ) {
            return 0;
        }

        return $post_id;
    }
}