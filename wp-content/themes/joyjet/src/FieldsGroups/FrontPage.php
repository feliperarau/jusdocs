<?php

namespace Theme\FieldsGroups;

use SolidPress\Core\Field;
use Theme\Helpers\Icons;
use SolidPress\Core\FieldGroup;
use SolidPress\Core\Page;
use SolidPress\Fields;

/**
 * Register fields to FrontPage
 */
class FrontPage extends FieldGroup {

	/**
	 * Set fields and group args
	 */
	public function __construct() {
        $this->set_fields(
            array(
				'about_text' => new Fields\Textarea(
                    __( 'Texto sobre', 'joyjet' ),
                    array()
                ),
			)
		);

		$this->args = array(
			'key'      => 'home-fields',
			'title'    => 'Home',
			'location' => array(
				array(
					Page::type_is_equal_to( 'front_page' ),
				),
			),
		);
	}
}