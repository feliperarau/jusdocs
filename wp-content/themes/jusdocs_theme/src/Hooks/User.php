<?php

namespace Theme\Hooks;

use SolidPress\Core\Hook;
use Theme\Components\Emails;
use Theme\Helpers\Page;
use Theme\Helpers\User as HelpersUser;
use Theme\Helpers\Utils;
use Theme\Models\Startup;
use Theme\Models\User as ModelsUser;
use Theme\ComponentPreset\Emails as PresetEmails;

/**
 * User related endpoints and hooks
 */
class User extends Hook {
	/**
	 * Bind actions
	 */
	public function __construct() {
		$this->add_action( 'after_setup_theme', 'remove_admin_bar' );
	}

	/**
	 * Remove WordPress bar from non admin users
	 *
	 * @return void
	 */
	public function remove_admin_bar() {
        if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}
}