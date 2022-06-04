<?php
/**
 * MiroEmbed Shortcode
 *
 * @package jusdocs
 */

namespace Theme\Hooks\Shortcodes;

use SolidPress\Core\Hook;

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
            'link' => '#',
        );

        $base_link = 'https://miro.com/app/live-embed/';

        $params = shortcode_atts( $default, $attrs );

        $src = $base_link . $params['link'];

        $iframe_src = add_query_arg(
            array(
				'embedAutoplay' => 'true',
            ),
            $src
        );

        return <<<HTML
            <div class="miro-iframe-container">
                <iframe style="margin-bottom: 1rem" width="768" height="432" src="$iframe_src" frameBorder="0" scrolling="no" allowFullScreen>Miro Embed</iframe>
                <button class="elementor-button" type="button">Fullscreen</button>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Launch demo modal
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe width="768" height="432" src="$iframe_src" frameBorder="0" scrolling="no" allowFullScreen>Miro Embed</iframe>
                </div>
                </div>
            </div>
            </div>
        HTML;
    }
}