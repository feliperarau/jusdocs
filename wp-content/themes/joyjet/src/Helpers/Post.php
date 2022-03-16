<?php
/**
 * Post
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Theme\Interfaces\Post as InterfacesPost;
use Theme\Models\Image;

/**
 * Post helper functions
 */
class Post {
	/**
	 * Get post formatted content
	 *
	 * @return string
	 */
	public static function get_the_content(): string {
		return apply_filters( 'the_content', get_the_content( null, false, get_the_ID() ) );
	}

	/**
	 * Get post formatted excerpt
	 *
	 * @return string
	 */
	public static function get_the_excerpt(): string {
		return apply_filters( 'the_excerpt', get_the_excerpt( null, false, get_the_ID() ) );
	}

	/**
     * Convert post IDs to interface
     *
     * @param array $ids int[]
	 * @param mixed $interface Interface instance to format the IDs return
     *
     * @return array
     */
    public static function to_interface( array $ids, $interface ) : array {

        if ( is_object( $interface ) && method_exists( $interface, 'from_posts' ) ) {
			$mapped = $interface::from_posts( $ids );
        }

        return $mapped ?? array();
    }

    /**
     * Get total post views
     *
     * @param int|string $post_id
     * @param boolean    $label
     *
     * @return int|string return int or string if $label is true
     */
    public static function get_post_views( $post_id = null, bool $label = false ) {
        $views = '';

        if ( empty( $post_id ) ) {
            $post_id = get_the_ID();
        }

        $count = (int) get_post_meta( $post_id, 'post_views_count', true );

        if ( $label ) {
            // translators: post views
            $views = sprintf( _n( '%s visualização', '%s visualizações', $count, 'joyjet' ), $count );
        } else {
            $views = $count;
        }

        return $views;
    }

	/**
	 * Query posts and return a casted array of interfaces.
	 *
	 * @param string $post_type
	 * @param mixed  $interface Interface instance to format the IDs return
	 * @param array  $custom_args
	 *
	 * @return array
	 */
	public static function get_casted_posts( $post_type, $interface, $custom_args = array() ) : array {
		$post_ids = InterfacesPost::get_posts_ids( $post_type, $custom_args );

		$casted_posts = self::to_interface( $post_ids, $interface );

		return $casted_posts ?? (array) $interface;
	}
}