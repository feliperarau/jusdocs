<?php
/**
 * Breadcrumbs component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;

/**
 * Handle template and props
 */
class Breadcrumbs extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Breadcrumbs/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'class' => '',
		'image' => '',
	);
}
