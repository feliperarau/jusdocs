<?php
/**
 * Header component
 *
 * @package joyjet
 */

namespace Theme\Components\Header;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Handle template and props
 */
class Header extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Header/Header/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class'       => '',
		'slim_header' => false,
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$logo = Utils::get_asset_image_src( 'logo/main.png' );

		return [
			'home_permalink'       => get_bloginfo( 'url' ),
			'site_name'            => get_bloginfo( 'name' ),
			'Header_menu_position' => 'Header-menu',
			'logo_image'           => $logo,
		];
	}
}