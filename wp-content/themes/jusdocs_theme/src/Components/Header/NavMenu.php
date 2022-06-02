<?php
/**
 * Header NavMenu component
 *
 * @package jusdocs
 */

namespace Theme\Components\Header;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Handle template and props
 */
class NavMenu extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Header/NavMenu/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class' => '',
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return [];
	}
}