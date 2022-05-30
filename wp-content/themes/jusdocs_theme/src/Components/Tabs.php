<?php
/**
 * Tabs component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Tabs
 */
class Tabs extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Tabs/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'tabs'  => array(),
        'id'    => '',
        'class' => '',
	);

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
        $modal_tabs = (array) $this->props['tabs'];

        $tabs = array_map(
            function( $tab ) {
                $tab['id']      = sanitize_title( $tab['name'] );
                $tab['name']    = $tab['name'] ?? '';
                $tab['content'] = $tab['content'] ?? '';

                return $tab;
            },
            $modal_tabs
        );

		return array(
			'modal_tabs' => $tabs,
		);
	}
}