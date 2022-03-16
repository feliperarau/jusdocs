<?php
/**
 * User
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Theme\Models\User as ModelsUser;
use WP_User;

/**
 * User helper functions
 */
class User {
	/**
	 * Helper function to query users
	 *
	 * @param mixed|WP_User $maybe_user
	 *
	 * @return ModelsUser
	 */
	public static function get_user( $maybe_user = null ) : ModelsUser {
		return ModelsUser::get_user( $maybe_user );
	}

	/**
	 * Check user roles
	 *
	 * @param mixed         $roles
	 * @param mixed|WP_User $maybe_user
	 *
	 * @return boolean
	 */
	public static function has_role( $roles, $maybe_user = null ) : bool {
		$user = ModelsUser::get_user( $maybe_user );

		if ( in_array( $user->role, (array) $roles, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get user role
	 *
	 * @param mixed|WP_User $maybe_user
	 *
	 * @return string
	 */
	public static function get_role( $maybe_user = null ) : string {
		$user = ModelsUser::get_user( $maybe_user );

		return $user->role;
	}
}