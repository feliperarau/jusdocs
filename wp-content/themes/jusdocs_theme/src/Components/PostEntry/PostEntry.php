<?php
/**
 * PostEntry component
 *
 * @package jusdocs
 */

namespace Theme\Components\PostEntry;

use SolidPress\Core\Component;
use Theme\Helpers\Post as HelpersPost;
use Theme\Helpers\Utils;
use Theme\Models\Post;

/**
 * Handle template and props
 */
class PostEntry extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/PostEntry/PostEntry/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class' => '',
		'post'  => false,
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$post = $this->props['post'];
		$post = $post instanceof Post ? $post : Post::from_post( $post );

		$thumbnail = HelpersPost::get_attachment_image_src( $post->thumbnail, 'medium' );

		return [
			'title'     => $post->title,
			'thumbnail' => $thumbnail,
			'content'   => $post->excerpt,
			'link'      => $post->link,
		];
	}
}