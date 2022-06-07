<?php
/**
 * MiroEmbed Shortcode
 *
 * @package jusdocs
 */

namespace Theme\Hooks\Shortcodes;

use SolidPress\Core\Hook;
use Theme\Components;

/**
 * MiroEmbed shortcode class
 */
class MiroEmbed extends Hook {

	/**
	 * Adds actions
	 */
	public function __construct() {
        add_shortcode( 'miro', [ $this, 'shortcode_content' ] );
	}

	/**
	 * The content of the shortcode
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
    public function shortcode_content( $attrs ) {
        $default = array(
            'board_id' => '',
        );

        $params = shortcode_atts( $default, $attrs );

        if ( $params['board_id'] ) {
            $embed = new Components\MiroEmbed( $params );
        }

        return $embed ?? '';
    }
}