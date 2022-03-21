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
				'about_tab'   => new Fields\Tab( __( 'About', 'jusdocs' ) ),
				'about_image' => new Fields\Image(
                    __( 'Image', 'jusdocs' ),
                ),
				'about_title' => new Fields\Text(
                    __( 'Title', 'jusdocs' ),
                ),
				'about_text'  => new Fields\Editor(
                    __( 'Text', 'jusdocs' ),
                ),
			)
		);

		$this->args = array(
			'key'      => 'home-fields',
			'title'    => 'Home',
			'location' => [
				[
					Page::type_is_equal_to( 'front_page' ),
				],
			],
		);
	}
}