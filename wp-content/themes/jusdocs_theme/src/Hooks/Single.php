<?php
/**
 * Single
 *
 * @package jusdocs
 */

namespace Theme\Hooks;

use SolidPress\Core\Hook;
use Apfelbox\FileDownload\FileDownload;
use Theme\Helpers\Page;

/**
 * Enqueue assets for current template
 */
class Single extends Hook {

	/**
	 * Adds actions
	 */
	public function __construct() {
        $this->add_action( 'template_redirect', 'template_redirect' );
        $this->add_action( 'jj_single_render', 'set_post_view' );
	}

	/**
	 * Single page redirect
	 *
	 * @return void
	 */
	public function template_redirect(): void {
		if ( ! ( is_single() && get_post_type() === 'post' ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			$login_page_permalink = Page::get_page_permalink_by_template( 'template-login' );
			wp_safe_redirect( $login_page_permalink );
			die();
		}
	}

    /**
     * Set post view
     *
     * @param int|string $post_id
     *
     * @return void
     */
    public static function set_post_view( $post_id = null ) :void {
        $key = 'post_views_count';

        if ( empty( $post_id ) ) {
            $post_id = get_the_ID();
        }

        $count = (int) get_post_meta( $post_id, $key, true );
        $count++;
        update_post_meta( $post_id, $key, $count );
    }
}