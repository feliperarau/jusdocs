<?php
/**
 * Enqueueable.
 *
 * @package jusdocs
 */

namespace Theme\Hooks;

use SolidPress\Core\Hook;
use Theme\Helpers\User;

/**
 * Enqueue assets for current template
 */
class Enqueue extends Hook {

	/**
	 * Adds actions
	 */
	public function __construct() {
		$this->add_action( 'admin_enqueue_scripts', 'admin_assets' );
        $this->add_action( 'wp_enqueue_scripts', 'enqueue_template_scripts' );
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function admin_assets(): void {
		$admin_css_path = sprintf(
			get_template_directory_uri() . '/dist/%s.css',
			'admin-area'
		);
		$admin_js_path  = sprintf(
			get_template_directory_uri() . '/dist/%s.js',
			'admin-area'
		);

		wp_enqueue_style(
			'jusdocs-admin-style',
			$admin_css_path,
			array(),
			filemtime( get_template_directory( $admin_css_path ) )
		);

		wp_register_script(
			'jusdocs-admin-scripts',
			$admin_js_path . '#defer',
			array(),
			filemtime( get_template_directory( $admin_js_path ) ),
			true
		);

		wp_enqueue_script( 'jusdocs-admin-scripts' );
	}

	/**
	 * Enqueue assets
	 *
	 * @return void
	 */
	public function enqueue_template_scripts(): void {
		$template_name = $this->get_template_name();

		if ( ! $template_name ) {
			return;
		}

		// vendor
		$js_vendor_path = get_template_directory_uri() . '/dist/vendor.js';

		wp_dequeue_style( 'wp-block-library' );

		wp_enqueue_script(
            'jusdocs-vendor-scripts',
            $js_vendor_path . '#defer',
            array( 'jquery' ),
            filemtime( get_template_directory( $js_vendor_path ) ),
            true
		);

		// Theme scripts & styles
		$css_path = sprintf(
            get_template_directory_uri() . '/dist/%s.css',
            $template_name
		);

		$js_path = sprintf(
            get_template_directory_uri() . '/dist/%s.js',
            $template_name
		);

		wp_enqueue_style(
            'jusdocs-style',
            $css_path,
            array(),
            filemtime( get_template_directory( $css_path ) )
		);

		wp_register_script(
            'jusdocs-scripts',
            $js_path . '#defer',
            array(),
            filemtime( get_template_directory( $js_path ) ),
            true
		);

		/**
		 * Jusdocs Localization Object
		 */
		wp_localize_script(
            'jusdocs-scripts',
            'jusdocs',
            array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
		);

		wp_localize_script(
            'jusdocs-scripts',
            'jusdocsComponentsData',
            array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'dynamic_components_nonce' ),
            )
        );

		wp_enqueue_script( 'jusdocs-scripts' );

		if ( ! is_admin() && is_singular() && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Return current page template name based on WordPress template hierarchy
	 *
	 * @see https://developer.wordpress.org/themes/basics/template-hierarchy/
	 *
	 * @return string
	 */
	public static function get_template_name(): string {
		$template_name = 'index';
		global $wp_query;

		if ( is_front_page() ) {
			// Home
			$template_name = 'front-page';
		} elseif ( is_archive() ) {
			$template_name = 'archive';

			/**
			 * Query custom taxonomy template assets if exists, fallback to default archive if not
			 */
			$term                = $wp_query->get_queried_object();
			$term_tax            = $term->taxonomy;
			$custom_template_dir = get_template_directory() . '/pages/taxonomy-' . str_replace( '_', '-', $term_tax );

			if ( is_dir( $custom_template_dir ) ) {
				$template_name = 'taxonomy-' . str_replace( '_', '-', $term_tax );
			}
		} elseif ( is_search() ) {
			$template_name = 'search';
		} elseif ( is_single() ) {
			$post_type = get_post_type();
			if ( 'post' === $post_type ) {
				$template_name = 'single';
			} else {
				$template_name = 'single-' . str_replace( '_', '-', $post_type );
			}
		} elseif ( is_page() ) {
			$template_name = 'page';

			// Set $template_name for custom templates.
			if ( is_page_template() ) {
				$template_name = str_replace(
                    array( 'template-', '.php' ),
                    array( '', '' ),
                    get_page_template_slug()
				);
			}
		} elseif ( is_404() ) {
			// 404
			$template_name = '404';
		}

		// Return template name, providing filter hook to add or modify rules
		return apply_filters( 'jusdocs_template_name', $template_name );
	}
}