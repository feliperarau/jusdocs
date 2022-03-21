<?php
/**
 * FrontPage
 *
 * Website Frontpage Logic
 *
 * @package joyjet
 */

namespace Theme\Pages;

use Theme\Core\Page;
use Theme\Helpers\Post;
use Theme\Interfaces\Post as InterfacesPost;

/**
 * Handle FrontPage template and props
 */
class FrontPage extends Page {

	/**
	 * Page template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'pages/front-page/template';

	/**
     * Values returned by get_props will be avaliable at the template as variables
     *
     * @return array
     */
	public function get_props(): array {
		$page_id     = get_the_id();
		$about_title = get_field( 'about_title', $page_id );
		$about_text  = get_field( 'about_text', $page_id );
		$about_image = get_field( 'about_image', $page_id );

		$about_image = Post::get_attachment_image_src( $about_image, 'large' );

		$props = [
			'about_title' => $about_title,
			'about_text'  => $about_text,
			'about_image' => $about_image,
		];

		return $props;
	}
}