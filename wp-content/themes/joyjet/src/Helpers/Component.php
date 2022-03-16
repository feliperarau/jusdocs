<?php
/**
 * Component helpers
 *
 * @package joyjet
 */

namespace Theme\Helpers;

/**
 * Component helper functions
 */
class Component {
    /**
     * Build Component HTML Classes
     *
     * @param mixed $component
     * @param array $control_classes
     *
     * @return string
     */
    public static function html_classes( $component, $control_classes = array() ) : string {
        $class       = isset( $component->class ) ? rtrim( $component->class ) : '';
        $child_class = isset( $component->child_class ) ? rtrim( $component->child_class ) : '';
        $args_class  = isset( $component->props['class'] ) ? rtrim( $component->props['class'] ) : '';

		return rtrim( sprintf( '%s %s %s %s', $class, $child_class, $args_class, implode( ' ', $control_classes ) ), ' ' );
    }

	/**
	 * Get public props of this component
	 *
	 * @param mixed $component
	 *
	 * @return array
	 */
	public static function get_public_props( $component ) : array {
		$props = array();

		foreach ( $component::$public_props as $prop ) {
			if ( isset( $component->props[ $prop ] ) ) {
				$props[ $prop ] = $component->props[ $prop ];
			}
		}

		return $props;
	}
}