<?php

namespace Theme\FieldsGroups;

use SolidPress\Core\Field;
use Theme\Helpers\Icons;
use SolidPress\Core\FieldGroup;
use SolidPress\Core\Page;
use SolidPress\Core\PostType;
use SolidPress\Fields;
use Theme\Helpers\Post as HelpersPost;

/**
 * Register fields to Post
 */
class Post extends FieldGroup {

	/**
	 * Set fields and group args
	 */
	public function __construct() {
        $this->set_fields(
            array(
				'trending' => new Fields\Boolean(
                    __( 'Trending', 'jusdocs' ),
					[
						'instructions' => __( 'Is this post trending?', 'jusdocs' ),
						'ui'           => true,
					]
                ),
			)
		);

		$this->args = [
			'key'      => 'post-fields',
			'title'    => __( 'Post Options', 'jusdocs' ),
			'location' => [
				[
					PostType::is_equal_to( 'post' ),
				],
			],
		];
	}
}