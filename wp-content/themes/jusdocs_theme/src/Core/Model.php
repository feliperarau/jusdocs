<?php

namespace Theme\Core;

use Theme\Helpers\Utils;
use Theme\Models\UserCourse;
use WP_Error;
use WP_Query;

/**
 * Model
 */
abstract class Model {
	/**
	 * Id
	 *
	 * @var string
	 */
	public $id;

    /**
     * __construct
     *
     * @param array $props
     */
	public function __construct( $props = array() ) {
		$this->prop = $props['id'] ?? '';

		return $this;
	}

	/**
	 * From posts
	 */
	public static function from_post() {}

	/**
     * Create new Self from Post array
     *
     * @param array $posts - Array containing post IDs or WP_Posts.
     *
     * @return array
     */
	public static function from_posts( array $posts = array() ) : array {
		$instances = array();
        $child     = get_called_class();

		foreach ( $posts as $index => $post ) {
			$instances[] = $child::from_post( $post );
		}

		return $instances;
	}

	/**
	 * Build from wp query results
	 *
	 * @param WP_Query $query_results
	 *
	 * @return array
	 */
	public static function from_wp_query( WP_Query $query_results ) : array {
		$posts = array();
        $child = get_called_class();

		if ( isset( $query_results->posts ) ) {
			$posts = array_map(
				function( $post ) use ( $child ) {
					$course_obj            = $child::from_post( $post );
					$course_obj->user_data = UserCourse::from_course( $course_obj );

					return $course_obj;
				},
                $query_results->posts
            );
		}

		return $posts;
	}

	/**
	 * Get post terms IDs
	 *
	 * @param mixed  $post
	 * @param string $taxonomy
	 *
	 * @return array int[]
	 */
	public static function get_post_terms( $post, $taxonomy ) : array {
		$terms = get_the_terms( $post, $taxonomy );
		$terms = ! empty( $terms ) && ! $terms instanceof WP_Error ? (array) $terms : array();

		$term_ids = wp_list_pluck( $terms, 'term_id' );

		$ids_array = Utils::sanitize_ids_array( $term_ids );

		return $ids_array;
	}

    /**
     * Generate Instance
     *
     * @param array $props
     */
    protected static function generate( array $props ) : self {
        $class = get_called_class();

        return new $class( $props );
    }
    /**
	 * PHP __toString magic method
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->id;
	}
}