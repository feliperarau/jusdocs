<?php

namespace Theme\Interfaces;

use Theme\Helpers\Dev;
use Theme\Helpers\Post as HelpersPost;
use Theme\Models\Post as ModelsPost;
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

 	/**
     * Query posts ID only
     *
     * @param string $post_type
     * @param array  $query_args
     *
     * @return array
     */
	public static function get_posts_models( $post_type, $query_args ) : array {
		$ids = self::get_posts_ids( $post_type, $query_args );

		return ModelsPost::from_posts( $ids );
	}
	/**
	 * Get trending posts
	 *
	 * @param mixed $post_type
	 * @param int   $count
	 * @param bool  $ids_only
	 *
	 * @return array
	 */
	public static function get_trending_posts( $post_type = 'post', $count = 8, $ids_only = false ) : array {
		$args = [
			'numberposts' => $count,
			'meta_query'  => [
				[
					'key'   => 'trending',
					'value' => 1,
				],
			],
		];

		$posts = self::get_posts_ids( $post_type, $args );

		if ( $ids_only ) {
			return $posts;
		} else {
			return ModelsPost::from_posts( $posts );
		}
	}
}