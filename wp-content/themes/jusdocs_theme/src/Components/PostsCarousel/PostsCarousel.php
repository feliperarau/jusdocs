<?php
/**
 * PostsCarousel component
 *
 * @package jusdocs
 */

namespace Theme\Components\PostsCarousel;

use SolidPress\Core\Component;
use Theme\Helpers\Interfaces;
use Theme\Helpers\Utils;
use Theme\Interfaces\Post;

/**
 * Handle template and props
 */
class PostsCarousel extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/PostsCarousel/PostsCarousel/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class' => '',
		'posts' => [],
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$posts = Post::get_posts_models(
            'post',
            [
				'numposts'   => 6,
				'meta_query' => [
					[
						'key'   => 'trending',
						'value' => 0,
					],
				],
			]
        );

		return [
			'posts' => $posts,
		];
	}
}