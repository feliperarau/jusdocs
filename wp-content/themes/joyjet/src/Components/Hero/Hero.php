<?php
/**
 * Hero component
 *
 * @package joyjet
 */

namespace Theme\Components\Hero;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Handle template and props
 */
class Hero extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Hero/Hero/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class'    => '',
        'headline' => '',
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
        $background = Utils::get_asset_image_src( 'hero-bg.jpeg' );

		return [
            'background' => $background,
        ];
	}
}