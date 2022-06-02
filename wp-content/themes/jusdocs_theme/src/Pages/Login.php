<?php
/**
 * Login
 *
 * @package jusdocs
 */

namespace Theme\Pages;

use SolidPress\Core\Page;

/**
 * Handle Login template and props
 */
class Login extends Page {
	/**
	 * Page template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'pages/login/template';

	/**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return array(
			'title'      => get_the_title(),
			'background' => get_the_post_thumbnail_url(),
			'subtitle'   => __( 'Todas estas questões, devidamente ponderadas, levantam dúvidas.', 'jusdocs' ),
		);
	}
}