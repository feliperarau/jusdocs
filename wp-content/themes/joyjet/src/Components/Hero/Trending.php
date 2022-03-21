<?php
/**
 * Trending component
 *
 * @package joyjet
 */

namespace Theme\Components\Hero;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;
use Theme\Interfaces\Post as InterfacesPost;

/**
 * Handle template and props
 */
class Trending extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Hero/Trending/template';

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
	public function get_props() : array {
		$trending = InterfacesPost::get_trending_posts( 'post', 3 );

		return [
			'trending_posts' => $trending,
		];
	}
}