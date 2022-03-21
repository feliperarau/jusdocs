<?php

namespace Theme\PostTypes;

use SolidPress\Core\PostType;

/**
 * Register 'roteiro' custom post type
 */
class Roteiro extends PostType {
	/**
	 * Set custom post type args
	 */
	public function __construct() {
        $this->post_type = 'roteiro';

		$labels = array(
			'name'               => _x( 'Roteiro', 'Post Type Labels', 'jusdocs' ),
			'singular_name'      => _x( 'Roteiro', 'Post Type Labels', 'jusdocs' ),
			'menu_name'          => _x( 'Roteiros', 'Post Type Labels', 'jusdocs' ),
			'all_items'          => _x( 'All Roteiros', 'Post Type Labels', 'jusdocs' ),
			'add_new'            => _x( 'Add new', 'Post Type Labels', 'jusdocs' ),
			'add_new_item'       => _x( 'Add new Roteiro', 'Post Type Labels', 'jusdocs' ),
			'edit_item'          => _x( 'Edit Roteiro', 'Post Type Labels', 'jusdocs' ),
			'new_item'           => _x( 'New Roteiro', 'Post Type Labels', 'jusdocs' ),
			'view_item'          => _x( 'View Roteiro', 'Post Type Labels', 'jusdocs' ),
			'insert_into_item'   => _x( 'Insert into Roteiro', 'Post Type Labels', 'jusdocs' ),
			'view_items'         => _x( 'View Roteiros', 'Post Type Labels', 'jusdocs' ),
			'search_items'       => _x( 'Search Roteiros', 'Post Type Labels', 'jusdocs' ),
			'not_found'          => _x( 'No Roteiro found', 'Post Type Labels', 'jusdocs' ),
			'not_found_in_trash' => _x( 'No Roteiro found at the bin', 'Post Type Labels', 'jusdocs' ),
		);

			$rewrite = array(
			'slug'                  => 'roteiro',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);

		$this->args = array(
			'label'              => _x( 'Roteiros', 'Post Type Labels', 'jusdocs' ),
			'labels'             => $labels,
			'description'        => __( 'Roteiros', 'jusdocs' ),
			'public'             => true,
			'publicly_queryable' => true,
			'has_archive'        => true,
			'capability_type'    => 'post',
			'hierarchical'       => true,
			'menu_position'      => 10,
			'rewrite'             => $rewrite,
			 'query_var'           => true,
			'supports'           => array( 'title', 'excerpt', 'thumbnail', 'editor' ),
			'menu_icon'          => 'dashicons-chart-line',
		);
	}
}