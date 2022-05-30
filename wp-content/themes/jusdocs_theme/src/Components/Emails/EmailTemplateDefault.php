<?php
/**
 * EmailTemplateDefault Email Template component
 *
 * @package jusdocs
 */

namespace Theme\Components\Emails;

use Theme\Core\EmailComponent;
use Theme\Helpers\Page;
use Theme\Helpers\Utils;

/**
 * Displays the template mock-up for development
 */
class EmailTemplateDefault extends EmailComponent {
    /**
	 * Email html template path relative to theme root
	 *
	 * @var string
	 */
    public $email_template = 'emails/dist/EmailTemplateDefault/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array();

	/**
	 * This method is meant to be override by the extending class in order to provide
	 * the respective email component defaults
	 *
	 * @return array
	 */
	public function defaults() {
		return array(
			'logo_url'         => Utils::get_asset_image_src( 'logo/logo-main.svg' ),
			'footer_text'      => sprintf(
				// translators: my account URL, privacy policy URL
				__(
					'Este e-mail foi gerado de acordo com sua inscrição no Jusdocs.<br>',
					'jusdocs'
				),
			),
			'footer_copyright' => __(
                'Copyright © Jusdocs <br>
				Todos os direitos reservados.',
                'jusdocs'
			),
		);
	}

    /**
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
		return array();
	}
}