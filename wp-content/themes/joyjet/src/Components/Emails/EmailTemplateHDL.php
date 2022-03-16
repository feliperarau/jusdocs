<?php
/**
 * EmailTemplateHDL Email Template component
 *
 * @package joyjet
 */

namespace Theme\Components\Emails;

use Theme\Core\EmailComponent;
use Theme\Helpers\Page;
use Theme\Helpers\Utils;

/**
 * Displays the template mock-up for development
 */
class EmailTemplateHDL extends EmailComponent {
    /**
	 * Email html template path relative to theme root
	 *
	 * @var string
	 */
    public $email_template = 'emails/dist/EmailTemplateHDL/template';

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
					'Este e-mail foi gerado de acordo com sua inscrição no Joyjet.<br>
					Precisa realizar alterações? Acesse a àrea <a href="%1$s">Minha Conta.</a><br>
					Para obter mais informações, consulte nossa <a href="%2$s">Política de Privacidade.</a>',
					'joyjet'
				),
				Page::get_page_permalink_by_template( 'template-profile' ),
				Page::get_page_permalink_by_template( 'template-profile-privacy-policy' ),
			),
			'footer_copyright' => __(
                'Copyright © Hospital Israelita Albert Einstein.<br>
				Todos os direitos reservados.',
                'joyjet'
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