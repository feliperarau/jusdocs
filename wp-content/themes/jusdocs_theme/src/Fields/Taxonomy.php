<?php
namespace Theme\Fields;

use SolidPress\Core\Field;

/**
 * Creates Taxonomy Fields for ACF
 */
class Taxonomy extends Field {
    /**
     * Field Type
     *
     * @var array
     */
	public $defaults = array( 'type' => 'taxonomy' );
}