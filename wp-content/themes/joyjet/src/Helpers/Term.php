<?php
/**
 * Term
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Theme\Models\Image;

/**
 * Term helper functions
 */
class Term {

    /**
     * Get terms IDs
     *
     * @param array $source_terms
     *
     * @return array
     */
    public static function get_terms_ids( $source_terms ) : array {
        $terms = [];

        if ( empty( $source_terms ) || is_wp_error( $source_terms ) ) {
            return $terms;
        }
        $terms = wp_list_pluck( (array) $source_terms, 'term_id' );

        $terms = array_map(
            fn( $term ) => (int) $term,
            $terms
        );

        return $terms;
    }
}