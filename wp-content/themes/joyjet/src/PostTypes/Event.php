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
			'name'               => _x( 'Live', 'Post Type Labels', 'joyjet' ),
			'singular_name'      => _x( 'Live', 'Post Type Labels', 'joyjet' ),
			'menu_name'          => _x( 'Lives', 'Post Type Labels', 'joyjet' ),
			'all_items'          => _x( 'Todas as Lives', 'Post Type Labels', 'joyjet' ),
			'add_new'            => _x( 'Adicionar nova', 'Post Type Labels', 'joyjet' ),
			'add_new_item'       => _x( 'Adicionar nova Live', 'Post Type Labels', 'joyjet' ),
			'edit_item'          => _x( 'Editar Live', 'Post Type Labels', 'joyjet' ),
			'new_item'           => _x( 'Nova Live', 'Post Type Labels', 'joyjet' ),
			'view_item'          => _x( 'Ver Live', 'Post Type Labels', 'joyjet' ),
			'insert_into_item'   => _x( 'Inserir na Live', 'Post Type Labels', 'joyjet' ),
			'view_items'         => _x( 'Ver Lives', 'Post Type Labels', 'joyjet' ),
			'search_items'       => _x( 'Perquisar Lives', 'Post Type Labels', 'joyjet' ),
			'not_found'          => _x( 'Nenhuma Live encontrada', 'Post Type Labels', 'joyjet' ),
			'not_found_in_trash' => _x( 'Nenhuma Live encontrada na Lixeira', 'Post Type Labels', 'joyjet' ),
		);

		$this->args = array(
			'label'               => _x( 'Lives', 'Post Type Labels', 'joyjet' ),
			'labels'              => $labels,
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'rest_base'           => '',
			'has_archive'         => false,
			'show_in_menu'        => true,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'menu_position'       => 40,
			'rewrite'             => false,
			'query_var'           => false,
			'supports'            => array( 'title', 'excerpt', 'thumbnail' ),
			'menu_icon'           => 'dashicons-embed-video',
			'taxonomies'          => array(),
		);
	}
}