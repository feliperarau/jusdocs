<?php
/**
 * Post
 *
 * @package jusdocs
 */

namespace Theme\Hooks;

use SolidPress\Core\Hook;
use Theme\Helpers\Page;

/**
 * Enqueue assets for current template
 */
class Post extends Hook {

	/**
	 * Adds actions
	 */
	public function __construct() {
        $this->add_filter( 'excerpt_more', 'change_excerpt_more' );
	}

	/**
	 * Change Excerpt More
	 *
	 * @param mixed $more
	 *
	 * @return string
	 */
	public function change_excerpt_more( $more ) : string {
		return '...';
	}
}