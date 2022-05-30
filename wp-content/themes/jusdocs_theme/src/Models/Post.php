<?php

namespace Theme\Models;

use Error;
use Theme\Core\Model;
use Theme\Helpers\Term;
use WP_Post;

/**
 * Post model
 */
class Post extends Model {
	/**
	 * Model's post type
	 *
	 * @var string
	 */
	public static $post_type = 'post';

	/**
	 * ID
	 *
	 * @var int
	 */
	public $id;

	/**
	 * Publish date
	 *
	 * @var string
	 */
	public $publish_date;

	/**
	 * Title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Content
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Excerpt
	 *
	 * @var string
	 */
	public $excerpt;

	/**
	 * Thumbnail
	 *
	 * @var int
	 */
	public $thumbnail;

	/**
	 * Link
	 *
	 * @var string
	 */
	public $link;

	/**
	 * Categories
	 *
	 * @var array
	 */
	public $categories;

	/**
	 * Tags
	 *
	 * @var array
	 */
	public $tags;

    /**
     * __construct
     *
     * @param array $props
     */
	public function __construct( $props = array() ) {
		$this->id           = $props['id'] ?? 0;
		$this->publish_date = $props['publish_date'] ?? '';
        $this->title        = $props['title'] ?? '';
		$this->content      = $props['content'] ?? '';
		$this->excerpt      = $props['excerpt'] ?? '';
		$this->thumbnail    = $props['thumbnail'] ?? 0;
		$this->link         = $props['link'] ?? '';
		$this->categories   = $props['categories'] ?? [];
		$this->tags         = $props['tags'] ?? [];

		return $this;
	}

	/**
	 * Create from current post
	 *
	 * @return self
	 */
	public static function from_current_post() : self {
		$post_id = get_the_ID();

		return self::from_post( $post_id );
	}

    /**
	 * Create new Model from Custom Post Type
	 *
	 * @param integer|WP_Post $post - Post ID or WP_Post object. Default is global $post.
     * @throws Error Error if CPT of the passed post is not the correct one.
     *
     * @return self
	 */
	public static function from_post( $post = 0 ) : self {
		$post      = $post ? get_post( $post ) : 0;
		$post_type = get_post_type( $post );

        $post_id = $post instanceof WP_Post ? (int) $post->ID : 0;

		// Prevents casting another custom post type Post into Event.
		if ( self::$post_type !== $post_type ) {
			$post_id = 0;
		}

		// "Empty" Object if post ID is invalid.
		if ( $post_id ) {
			$publish_date = get_the_date( 'Y-m-d H:i:s', $post_id );
			$title        = get_post_field( 'post_title', $post_id );
			$content      = get_the_content( null, false, $post_id );
			$excerpt      = get_the_excerpt( $post_id );
			$thumbnail    = (int) get_post_thumbnail_id( $post_id );
			$link         = get_the_permalink( $post_id );
			$categories   = get_the_terms( $post_id, 'category' );
			$tags         = get_the_terms( $post_id, 'post_tag' );

			$categories = Term::get_terms_ids( $categories );
			$tags       = Term::get_terms_ids( $tags );
		}

        $props = array(
			'id'           => $post_id,
			'publish_date' => $publish_date ?? '',
			'title'        => $title ?? '',
			'content'      => $content ?? '',
			'excerpt'      => $excerpt ?? '',
			'thumbnail'    => $thumbnail ?? 0,
			'link'         => $link ?? '',
			'categories'   => $categories ?? [],
			'tags'         => $tags ?? [],
        );

		return self::generate( $props );
	}
}