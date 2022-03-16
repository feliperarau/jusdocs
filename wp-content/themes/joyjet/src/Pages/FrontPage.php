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
		$props = [];

		return $props;
	}
}