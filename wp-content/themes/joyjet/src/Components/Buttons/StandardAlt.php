<?php
/**
 * StandardAlt Button component
 *
 * @package joyjet
 */

namespace Theme\Components\Buttons;

use Theme\Components\Buttons\Standard as ButtonsStandard;
use Theme\Helpers\Component as HelpersComponent;

/**
 * StandardAlt Button
 */
class StandardAlt extends ButtonsStandard {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/Buttons/Standard/template';

	/**
	 * Component HTML class
	 *
	 * @var string
	 */
	public $class = '_button-standard-alt _button-var';
}