<?php
/**
 * Jusdocs boostrap
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package jusdocs
 */

use SolidPress\Core\WPTemplate;
use Theme\Core\Boilerplate;

define( 'JUSDOCSDEV', true );

add_filter( 'use_block_editor_for_post', '__return_false' );

// Composer autoload
require get_template_directory() . '/vendor/autoload.php';

$registrable_namespaces = array();

// Set core registrables
$registrable_namespaces = array_merge(
	$registrable_namespaces,
	array(
		'Hooks',
		'Hooks\Shortcodes',
		'Endpoints',
	)
);

// Check if ACF plugin is active to register fields
if ( function_exists( 'acf_add_local_field_group' ) ) {
	$registrable_namespaces[] = 'FieldsGroups';
	$registrable_namespaces[] = 'Options';
}


// Setup a theme instance for SolidPress
new Boilerplate(
	array(
		'template_engine'        => new WPTemplate(),
		'namespace'              => 'Theme',
		'base_folder'            => 'src',
		'registrable_namespaces' => $registrable_namespaces,
	)
);

require 'shortcode_api.php';