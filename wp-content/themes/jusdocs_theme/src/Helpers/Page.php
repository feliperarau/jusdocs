<?php
/**
 * Page
 *
 * @package jusdocs
 */

namespace Theme\Helpers;

/**
 * Page helper functions
 */
class Page {

	/**
	 * Get post formatted content
	 *
	 * @return string
	 */
	public static function get_the_content(): string {
		return apply_filters( 'the_content', get_the_content( null, false, get_the_ID() ) );
	}

	/**
	 * Get excerpt formatted content
	 *
	 * @return string
	 */
	public static function get_the_excerpt(): string {
		return apply_filters( 'the_excerpt', get_the_excerpt( null, false, get_the_ID() ) );
	}

	/**
	 * Get page id
	 *
	 * @param string $template_name template file name whitout extension
	 * @return integer
	 */
	public static function get_page_id_by_template( string $template_name ): int {
		$args = array(
			'posts_per_page' => 1,
			'post_type'      => 'page',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_wp_page_template',
					'value' => "{$template_name}.php",
				),
			),
		);

		$pages = get_posts( $args );

		return $pages[0] ?? 0;
	}

	/**
	 * Get page permalink
	 *
	 * @param string $template_name template file name whitout extension
	 * @return integer
	 */
	public static function get_page_permalink_by_template( string $template_name ): string {
		$args = array(
			'posts_per_page' => 1,
			'post_type'      => 'page',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_wp_page_template',
					'value' => "{$template_name}.php",
				),
			),
		);

		$pages = get_posts( $args );

		return isset( $pages[0] ) && $pages[0] ? get_permalink( $pages[0] ) : '';
	}

	/**
	 * Check if the page is equal the template.
	 *
	 * @param string $template_name
	 * @param mixed  $page_id
	 *
	 * @return boolean
	 */
	public static function is_page_template( string $template_name, $page_id = null ) : bool {
		$template_id     = self::get_page_id_by_template( $template_name );
		$current_page_id = $page_id ?? get_queried_object_id();

		if ( $template_id === $current_page_id ) {
			return true;
		}

		return false;
	}
}