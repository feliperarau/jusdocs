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