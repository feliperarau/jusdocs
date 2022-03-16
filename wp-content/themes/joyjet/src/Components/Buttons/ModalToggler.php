<?php
/**
 * ModalToggler component
 *
 * @package joyjet
 */

namespace Theme\Components\Buttons;

use SolidPress\Core\Component;

/**
 * Toggle modal
 */
class ModalToggler extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Buttons/ModalToggler/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = array(
        'class'        => '',
		'modal_id'     => '',
        'label'        => '',
        'element'      => 'button',
		'button_class' => 'btn-primary',
		'align'        => 'left',
	);
}