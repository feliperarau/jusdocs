<?php
/**
 * Standard Button component
 *
 * @package joyjet
 */

namespace Theme\Components\Buttons;

use SolidPress\Core\Component;
use Theme\Helpers\Component as HelpersComponent;

/**
 * Standard Button
 */
class Standard extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Buttons/Standard/template';

	/**
	 * Component HTML class
	 *
	 * @var string
	 */
	public $class = '_button-standard _button-var';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
        'class'           => '',
		'state'           => 'default',
        'tag'             => 'a',
        'target'          => '',
        'text'            => '',
		'link'            => '',
		'type'            => '',
		'icon'            => '',
		'icon_position'   => 'left',
		'custom_data'     => array(),
		'custom_data_tpl' => '',
	);

	/**
	 * Get Button HTML custom Data
	 *
	 * @param array $custom_data
	 *
	 * @return string
	 */
	public static function get_custom_data( $custom_data ) : string {
		$data_str = '';

		foreach ( $custom_data as $key => $data ) {
			$data_str .= "data-$key=\"$data\" ";
		}

		return rtrim( $data_str );
	}

	/**
	 * Set custom data
	 *
	 * @return void
	 */
	public function set_custom_data() {
		$custom_data                    = $this->props['custom_data'];
		$this->props['custom_data_tpl'] = self::get_custom_data( $custom_data );
	}

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$icon_pos = $this->props['icon_position'] ?? '';

		$control_classes = array(
			"icon-{$icon_pos}",
		);

		$classes = HelpersComponent::html_classes( $this, $control_classes );

		$this->set_custom_data();

		return array(
			'class' => $classes,
		);
	}
}