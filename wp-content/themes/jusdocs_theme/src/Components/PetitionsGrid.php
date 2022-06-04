<?php
/**
 * PetitionsGrid component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * PetitionsGrid
 */
class PetitionsGrid extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/PetitionsGrid/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class'     => '',
		'petitions' => [],
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
        $petitions = (array) $this->props['petitions'];

		return [
			'petitions' => $petitions,
		];
	}
}