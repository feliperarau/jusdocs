<?php
/**
 * TransactionalTest Email Template component
 *
 * @package joyjet
 */

namespace Theme\Components\Emails;

use Theme\Core\EmailComponent;

/**
 * Handle template and props
 */
class TransactionalTest extends EmailTemplateHDL {
    /**
	 * Email html template path relative to theme root
	 *
	 * @var string
	 */
    public $email_template = 'emails/dist/TransactionalTest/template';

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
			'headline'                => __( 'Esqueceu sua senha?', 'joyjet' ),
			'sub_headline'            => 'SUB HEADLINE',
			'body_text_1'             => __(
                'Não se preocupe! Isso acontece com todo mundo...<br>
				Para criar uma nova senha acesse o link abaixo:',
                'joyjet'
            ),

			'body_text_2'             => __( 'Ou clique:', 'joyjet' ),
			'recover_password_url'    => 'https://www.programahdl.com.br/recuperacao-senha?id=0x6AA701244DB404673F60C53477247720916AC405',
			'recover_password_button' => __( 'redefinir senha', 'joyjet' ),
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