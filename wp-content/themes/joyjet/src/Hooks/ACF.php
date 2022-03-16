<?php

namespace Theme\Hooks;

use SolidPress\Core\Hook;
use Theme\CustomFields\TaxonomySelect;
use Theme\Helpers\Dev;
use Theme\Models\Image;

/**
 * ACF specific hooks
 */
class ACF extends Hook {

	/**
	 * Add actions
	 */
	public function __construct() {
        $this->add_action( 'acf/format_value/type=image', 'image_format_value', 20, 3 );

		// Save terms for fields that has taxonomy arg set
		$this->add_action( 'acf/save_post', 'save_post', 15, 1 );
		$this->add_filter( 'acf/update_value/type=select', 'update_value', 10, 4 );

		// Change postobject query based on field args
		$this->add_filter( 'acf/fields/post_object/query', 'change_postobject_query', 10, 3 );

		$this->add_filter( 'acf/update_value/type=time_picker', 'time_picker_format', 10, 3 );
	}

	/**
	 * Hook into image acf field to return Image model instance
	 *
	 * @param array  $value - acf original value.
	 * @param [type] $post_id - post id.
	 * @param [type] $field - field.
	 * @return Image|array
	 */
	public function image_format_value( $value, $post_id, $field ) {
		return is_array( $value ) ? Image::from_acf( $value ) : new Image();
	}

	/**
	 * Change postobject query based on field args
	 *
	 * @param mixed $args
	 * @param mixed $field
	 * @param mixed $post_id
	 *
	 * @return array
	 */
	public function change_postobject_query( $args, $field, $post_id ) {
		if ( isset( $field['term'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $field['taxonomy'] ?? '',
					'field'    => 'slug',
					'terms'    => $field['term'] ?? '',
				),
			);
		}
		return $args;
	}

	/**
     *  Save select term option relationship
     *
     *  This filter is appied to the $value before it is updated in the db
     *
     *  @param mixed $value - the value which will be saved in the database
     *  @param mixed $post_id - the $post_id of which the value will be saved
     *  @param mixed $field - the field array holding all the field options
     *
     *  @return  $value - the modified value
     */
	public function update_value( $value, $post_id, $field ) {
		// vars
		if ( is_array( $value ) ) {
			$value = array_filter( $value );
		}

		if ( isset( $field['save_terms'] ) || isset( $field['load_save_terms'] ) ) {
			// vars
			$taxonomy = $field['taxonomy'];

			// Dev::dump( $value );
			// force value to array
			$terms = acf_get_array( $value );
			// convert to int
			$terms = is_numeric( $value ) ? array_map( 'intval', $terms ) : $terms;

			// get existing term id's (from a previously saved field)
			$old_terms = isset( $this->save_post_terms[ $taxonomy ] ) ? $this->save_post_terms[ $taxonomy ] : array();
			// append
			$this->save_post_terms[ $taxonomy ] = array_merge( $old_terms, $terms );

			// if called directly from frontend update_field()
			if ( ! did_action( 'acf/save_post' ) ) {

				$this->save_post( $post_id );

				return $value;
			}
		} else {
			// Bail early if no value.
			if ( empty( $value ) ) {
				return $value;
			}

			// Format array of values.
			// - Parse each value as string for SQL LIKE queries.
			if ( is_array( $value ) ) {
				$value = array_map( 'strval', $value );
			}

			// return
			return $value;
		}

		return $value;
	}
    /**
     *
     *  This function will save any terms in the save_post_terms array
     *
     *  @param mixed $post_id (int)
     *  @return void
     */
	public function save_post( $post_id ) {
		$decode = acf_decode_post_id( $post_id );

		// Check for saved terms.
		if ( ! empty( $this->save_post_terms ) ) {

			// Determine object ID allowing for non "post" $post_id (user, taxonomy, etc).
			// Although not fully supported by WordPress, non "post" objects may use the term relationships table.
			// Sharing taxonomies across object types is discoraged, but unique taxonomies work well.
			// Note: Do not attempt to restrict to "post" only. This has been attempted in 5.8.9 and later reverted.
            // phpcs:ignore
			extract( (array) $decode );

            // phpcs:ignore
			if ( 'block' === $type ) {
				// Get parent block...
			}

			// Loop over taxonomies and save terms.
			foreach ( $this->save_post_terms as $taxonomy => $term_ids ) {
				wp_set_object_terms( $id, $term_ids, $taxonomy, false );
			}

			// Reset storage.
			$this->save_post_terms = array();
		}
	}

	/**
     * Remove seconds from ACF time field
     *
     * @param string     $value
     * @param string|int $post_id
     * @param string     $field
     *
     * @return string
     */
	public function time_picker_format( $value, $post_id, $field ) {
		return gmdate( 'H:i', strtotime( $value ) );
	}
}