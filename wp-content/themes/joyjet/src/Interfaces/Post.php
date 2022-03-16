<?php

namespace Theme\Interfaces;

use Theme\Helpers\Dev;
use Theme\Helpers\Post as HelpersPost;
use Theme\Models\User;

/**
 * Post Interface
 */
class Post {

    /**
     * Query posts ID only
     *
     * @param string $post_type
     * @param array  $query_args
     *
     * @return array
     */
	public static function get_posts_ids( $post_type, $query_args ) : array {
		$defaults = array(
			'post_type'   => $post_type,
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields'      => 'ids', // Only get post IDs
		);

		$args = wp_parse_args( $query_args, $defaults );

		$posts = get_posts( $args );

		return $posts;
	}
}