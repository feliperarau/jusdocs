<?php
/**
 * NotFound component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * Handle template and props
 */
class NotFound extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/NotFound/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
		'class'    => '',
		'headline' => '',
		'text'     => '',
	);

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		$emoji    = Utils::get_asset_image_src( 'emoji-wow.svg' );
		$headline = ! empty( $this->props['headline'] ) ? $this->props['headline'] : __( 'Nada por aqui...', 'jusdocs' );
		$text     = ! empty( $this->props['text'] ) ? $this->props['text'] : __( 'Você ainda não tem nenhum conteúdo adicionado em favoritos.', 'jusdocs' );

		$props = array(
			'emoji'    => $emoji,
			'headline' => $headline,
			'text'     => $text,
		);

		return $props;
	}
}