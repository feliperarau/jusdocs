<?php

namespace Theme\Options;

use SolidPress\Core\OptionsPage;

/**
 * Register SiteOptions options page
 */
class SiteOptions extends OptionsPage {
	/**
	 * Set options page args
	 */
	public function __construct() {
		$this->args = array(
			'page_title' => __( 'Site Options', 'joyjet' ),
			'menu_title' => __( 'Site Options', 'joyjet' ),
			'menu_slug'  => 'site-options',
			'capability' => 'edit_posts',
			'redirect'   => false,
		);
	}
}