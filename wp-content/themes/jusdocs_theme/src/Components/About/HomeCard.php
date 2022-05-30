<?php
/**
 * HomeCard component
 *
 * @package jusdocs
 */

namespace Theme\Components\About;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Handle template and props
 */
class HomeCard extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/About/HomeCard/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class' => '',
		'image' => '',
		'title' => '',
		'text'  => '',
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