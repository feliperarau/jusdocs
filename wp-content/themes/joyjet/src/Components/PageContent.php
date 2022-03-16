<?php
/**
 * PageContent component
 *
 * @package joyjet
 */

namespace Theme\Components;

use SolidPress\Core\Component;
use Theme\Helpers\Page;

/**
 * Handle template and props
 */
class PageContent extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/PageContent/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'class'   => '',
		'title'   => '',
		'content' => '',
		'image'   => null,
	);
}
