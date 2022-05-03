<?php

namespace Theme\FieldsGroups;

use SolidPress\Core\FieldGroup;
use SolidPress\Core\OptionsPage;
use SolidPress\Fields;

/**
 * Register fields to SiteOptions
 */
class SiteOptions extends FieldGroup {

	/**
	 * Set fields and group args
	 */
	public function __construct() {
        $this->set_fields(
			/**
			 * Policy Tab
			 */
            array(
				'privacy_policy_tab' => new Fields\Tab( __( 'Termos e Condições', 'jusdocs' ) ),

				'privacy_policy'     => new Fields\Editor(
					__( 'Política de privacidade', 'jusdocs' ),
				),
			)
		);

		$this->args = array(
			'key'      => 'site-options',
			'title'    => __( 'Site Options', 'jusdocs' ),
			'location' => array(
				array(
					OptionsPage::is_equal_to( 'site-options' ),
				),
			),
		);
	}
}