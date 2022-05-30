<?php
/**
 * Field helpers class
 *
 * @package jusdocs
 */

namespace Theme\Helpers;

/**
 * Fields class
 */
class Fields {
    /**
	 * Return terms as dropdown choices
	 *
     * @param string $taxonomy
     * @param bool   $sort
     * @param array  $custom_query_args
	 * @param bool   $slug return slug instead of IDs
     *
	 * @return array
	 */
	public static function get_terms_as_dropdown_choices( $taxonomy, $sort = null, $custom_query_args = null, $slug = false ): array {
		$options = array();

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		);

        if ( $custom_query_args ) {
            $args = array_merge( $args, $custom_query_args );
        }

		if ( $sort ) {
			$args['orderby']  = 'tax_position';
			$args['meta_key'] = 'tax_position';
		}

		$terms = get_terms( $args );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $slug ? $term->slug : $term->term_id ] = $term->name;
			}
		}
		return $options;
	}
}