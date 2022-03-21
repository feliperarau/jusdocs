<?php
/**
 * SingleForumTopic
 *
 * @package jusdocs
 */

namespace Theme\Pages;

use Theme\Core\Page;
use Theme\Helpers\Dev;
use Theme\Helpers\Post;
use Theme\Models\Post as ModelsPost;

/**
 * Handle Page template and props
 */
class Single extends Page {

	/**
	 * Page template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'pages/single/template';

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$post = ModelsPost::from_current_post();

		return array(
			'title'   => $post->title,
			'content' => $post->content,
		);
	}
}