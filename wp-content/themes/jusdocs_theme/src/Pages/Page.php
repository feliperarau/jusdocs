<?php
/**
 * Page
 *
 * @package jusdocs
 */

namespace Theme\Pages;

use SolidPress\Core\Page as SolidPressPage;
use Theme\Helpers\Page as PageHelper;
use Theme\Models\Image;

/**
 * Handle Page template and props
 */
class Page extends SolidPressPage {
	/**
	 * Page template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'pages/page/template';

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return array(
			'title'   => get_the_title(),
			'image'   => Image::from_post_thumbnail(),
			'content' => PageHelper::get_the_content(),
		);
	}
}