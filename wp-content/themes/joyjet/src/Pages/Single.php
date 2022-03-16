<?php
/**
 * SingleForumTopic
 *
 * @package joyjet
 */

namespace Theme\Pages;

use Theme\Core\Page;
use Theme\Helpers\Post;

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
		$id = get_the_ID();

		do_action( 'jj_single_render', $id );

		return array(
			'title'   => get_the_title(),
			'content' => Post::get_the_content(),
		);
	}
}