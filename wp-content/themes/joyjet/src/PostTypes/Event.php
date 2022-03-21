<?php

namespace Theme\PostTypes;

use SolidPress\Core\PostType;

/**
 * Register 'event' custom post type
 */
class Event extends PostType {
	/**
	 * Set custom post type args
	 */
	public function __construct() {
        $this->post_type = 'event';

		$labels = array(
			'name'               => _x( 'Event', 'Post Type Labels', 'joyjet' ),
			'singular_name'      => _x( 'Event', 'Post Type Labels', 'joyjet' ),
			'menu_name'          => _x( 'Events', 'Post Type Labels', 'joyjet' ),
			'all_items'          => _x( 'All Events', 'Post Type Labels', 'joyjet' ),
			'add_new'            => _x( 'Add new', 'Post Type Labels', 'joyjet' ),
			'add_new_item'       => _x( 'Add new Event', 'Post Type Labels', 'joyjet' ),
			'edit_item'          => _x( 'Edit Event', 'Post Type Labels', 'joyjet' ),
			'new_item'           => _x( 'New Event', 'Post Type Labels', 'joyjet' ),
			'view_item'          => _x( 'View Event', 'Post Type Labels', 'joyjet' ),
			'insert_into_item'   => _x( 'Insert into Event', 'Post Type Labels', 'joyjet' ),
			'view_items'         => _x( 'View Events', 'Post Type Labels', 'joyjet' ),
			'search_items'       => _x( 'Search Events', 'Post Type Labels', 'joyjet' ),
			'not_found'          => _x( 'No Event found', 'Post Type Labels', 'joyjet' ),
			'not_found_in_trash' => _x( 'No Event found at the bin', 'Post Type Labels', 'joyjet' ),
		);

		$this->args = array(
			'label'              => _x( 'Events', 'Post Type Labels', 'joyjet' ),
			'labels'             => $labels,
			'description'        => __( 'Events', 'joyjet' ),
			'public'             => true,
			'publicly_queryable' => false,
			'has_archive'        => true,
			'capability_type'    => 'post',
			'hierarchical'       => true,
			'menu_position'      => 10,
			// 'rewrite'             => false,
			// 'query_var'           => false,
			'supports'           => array( 'title', 'excerpt', 'thumbnail', 'editor' ),
			'menu_icon'          => 'dashicons-chart-line',
		);
	}
}