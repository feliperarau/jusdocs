<?php
/**
 * Solid Button component
 *
 * @package joyjet
 */

namespace Theme\Components\Buttons;

use SolidPress\Core\Component;
use Theme\Helpers\Component as HelpersComponent;

/**
 * Solid Button
 */
class Solid extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Buttons/Solid/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
        'class'         => '',
		'state'         => 'default',
        'tag'           => 'a',
        'target'        => '',
        'label'         => '',
		'link'          => '',
		'type'          => '',
		'icon'          => '',
		'icon_position' => 'left',
	);

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$icon_pos = $this->props['icon_position'] ?? '';

		$classess = [
			'_button-solid',
			"icon-{$icon_pos}",
		];

		return array(
			'class' => implode( ' ', $classess ),
		);
	}
}