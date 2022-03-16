<?php

namespace Theme\Hooks;

use Theme\Components;
use SolidPress\Core\Hook;
use Theme\Helpers\User;
use Theme\Helpers\Utils;

/**
 * Init theme
 */
class Theme extends Hook {

	/**
	 * Add actions and filters
	 */
	public function __construct() {
        $this->add_action( 'after_setup_theme', 'setup_theme' );
		$this->add_filter( 'body_class', 'add_classes_to_body' );
		$this->add_action( 'wp_head', 'add_pingback_url_header' );
		$this->add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
		$this->add_action( 'init', 'session_start' );
		$this->add_filter( 'wp_mail_content_type', 'wp_mail_content_type' );
	}

	/**
	 * Custom Excerpt
	 *
	 * @param int $length
	 *
	 * @return int
	 */
	public function custom_excerpt_length( $length ) {
		return 20;
	}

	/**
	 * Setup WordPress theme
	 *
	 * @return void
	 */
	public function setup_theme(): void {
		// Make theme available for translation.
		// Translations can be filed in the /languages/ directory.
		load_theme_textdomain( 'joyjet', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		// By adding theme support, we declare that this theme does not use a hard-coded <title> tag, and expect WordPress to provide it for us.
		add_theme_support( 'title-tag' );

		// This theme uses wp_nav_menu() in the Header and Footer.
		register_nav_menus(
			array(
				'main-menu'   => esc_html__( 'Main Menu', 'joyjet' ),
				'footer-menu' => esc_html__( 'Footer Menu', 'joyjet' ),
			)
		);

		// Switch default core markup for search form, gallery and image captions to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'gallery',
				'caption',
			)
		);

		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 */
	public function add_classes_to_body( $classes ): array {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @return void
	 */
	public function add_pingback_url_header(): void {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Starts PHP session
	 *
	 * @return void
	 */
	public function session_start(): void {
		if ( ! session_id() ) {
			session_start();
		}
	}

	/**
	 * Set platform emails to HTML type
	 *
	 * @return string
	 */
	public function wp_mail_content_type() {
		return 'text/html';
	}
}