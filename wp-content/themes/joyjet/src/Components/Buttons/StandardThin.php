<?php
/**
 * Standard Thin Button component
 *
 * @package joyjet
 */

namespace Theme\Components\Buttons;

use Theme\Components\Buttons\Standard as ButtonsStandard;
use Theme\Helpers\Component as HelpersComponent;

/**
 * Standard Button
 */
class StandardThin extends ButtonsStandard {
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
	public $class = '_button-standard-thin _button-var';
}