<?php
/**
 * MiroEmbed component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;

/**
 * MiroEmbed
 */
class MiroEmbed extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/MiroEmbed/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class'                 => '',
		'board'                 => '',
		'show_button'           => true,
		'button_label'          => '',
		'autoplay'              => true,
		'presentation'          => true,
		'live_endpoint'         => 'https://miro.com/app/live-embed/',
		'presentation_endpoint' => 'https://miro.com/app/embed/',
		'frame'                 => '',
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		if ( $this->props['presentation'] ) {
			$endpoint = $this->props['presentation_endpoint'];
		} else {
			$endpoint = $this->props['live_endpoint'];
		}

        $src = $endpoint . $this->props['board'] . '/';

        $src = add_query_arg(
            array(
				'embedAutoplay' => $this->props['autoplay'] ? 'true' : 'false',
				'autoplay'      => $this->props['presentation'] ? 'yep' : '',
				'pres'          => $this->props['presentation'] ? '1' : '0',
				'frameId'       => $this->props['frame'],
            ),
            $src
        );

		return [
			'src'          => $src,
			'button_label' => $this->props['button_label'] ? $this->props['button_label'] : __( 'Ver em tela cheia', 'jusdocs' ),
		];
	}
}