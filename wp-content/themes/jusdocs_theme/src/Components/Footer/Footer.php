<?php
/**
 * Footer component
 *
 * @package jusdocs
 */

namespace Theme\Components\Footer;

use SolidPress\Core\Component;

/**
 * Handle template and props
 */
class Footer extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Footer/Footer/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'class' => '',
	);

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return [
			'copyright' => __( '© 2021 Created by Jusdocs Digital Space Agency', 'jusdocs' ),
		];
	}
}