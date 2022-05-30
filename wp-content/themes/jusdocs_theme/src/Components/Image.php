<?php
/**
 * Image component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;

/**
 * Return loaded img tag
 *
 * @param string class CSS class attribute
 * @param string src Image data-src attribute
 * @param int width Image width attribute
 * @param int height Image height attribute
 * @param int alt Image alt attribute
 * @param int placeholder_fill Placeholder fill color, in case of hex format, the '#' must be omitted
 * @param Image image Image model object
 */
class Image extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Image/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'class'  => '',
		'width'  => 0,
		'height' => 0,
		'alt'    => '',
		'image'  => null,
		'src'    => '',
	);


	/**
	 * Parse props if image param is provided
	 *
	 * @return array
	 */
	public function get_props(): array {
		if ( $this->props['image'] ) {
			return array(
				'src'    => $this->props['image']->src,
				'width'  => $this->props['image']->width,
				'height' => $this->props['image']->height,
				'alt'    => $this->props['image']->alt,
			);
		}

		return array();
	}
}