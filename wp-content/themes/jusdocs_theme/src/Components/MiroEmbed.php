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
		'class'        => '',
		'board_id'     => '',
		'show_button'  => true,
		'button_label' => '',
		'autoplay'     => true,
		'base_link'    => 'https://miro.com/app/live-embed/',
	];

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
        $src = $this->props['base_link'] . $this->props['board_id'];

        $src = add_query_arg(
            array(
				'embedAutoplay' => $this->props['autoplay'] ? 'true' : 'false',
            ),
            $src
        );

		return [
			'src'          => $src,
			'button_label' => $this->props['button_label'] ? $this->props['button_label'] : __( 'Ver em tela cheia', 'jusdocs' ),
		];
	}
}