<?php
/**
 * CustomAdminColumns
 *
 * @package joyjet
 */

namespace Theme\Core;

use Error;
use WP_Meta_Query;
use SolidPress\Core\Hook;
use Theme\Helpers\Dev;
use Theme\Helpers\Utils;

/**
 * Inserts custom admin column for post type or taxonomy
 */
abstract class CustomAdminColumns extends Hook {
    /**
     * Constructor
     *
     * @var array $args
     */
    public $args;

    /**
     * Init
     *
     * Params:
     * where-to-add: tax or post slug /
     * slug: column slug /
     * label: column label /
     * sortable: makes our column sortable /
     * sort_meta_key: sort meta key /
     * can_filter: enable filter for our column value /
     */
	public function __construct() {

		$defaults = array(
			'where-to-add'  => '',
			'object_type'   => 'post',
			'slug'          => '',
			'order'         => 10,
			'label'         => '',
			'sortable'      => '',
			'sort_meta_key' => '',
			'can_filter'    => '',
		);

		$this->args = wp_parse_args( $this->args, $defaults );

		$this->hook_it();
	}

    /**
     * Do the hook stuff
     *
     * @return void
     */
	public function hook_it() {
		$this->add_filter( 'manage_edit-' . $this->args['where-to-add'] . '_columns', 'add_column' );
		if ( 'post' === $this->args['object_type'] ) {
			$this->add_action( 'manage_' . $this->args['where-to-add'] . '_posts_custom_column', 'column_hook_post', -1, 2 );
		} else {
			$this->add_filter( 'manage_' . $this->args['where-to-add'] . '_custom_column', 'column_hook_taxonomy', 10, 3 );
		}

		// TODO: Implement Sortable
		//phpcs:disable
		/* if ( $this->args['sortable'] ) {
		$this->add_filter( 'manage_edit-' . $this->args['where-to-add'] . '_sortable_columns', 'add_sortable_column' );
		$this->add_filter( 'pre_get_terms', 'order_terms_by_custom_column' );
		} */
		//phpcs:enable

		if ( $this->args['can_filter'] ) {
			$this->add_filter( 'query_vars', 'filter_column_query_var' );
			$this->add_filter( 'pre_get_terms', 'filter_terms_by_column_value' );
			$this->add_action( 'pre_get_posts', 'filter_posts_by_column_value' );
		}
	}

    /**
     * Add the column
     *
     * @param array $columns default columns
     *
     * @throws Error Error in case of duplicated column slug.
     * @return array columns with the new one already inserted
     */
	public function add_column( $columns ) : array {
		if ( isset( $columns[ $this->args['slug'] ] ) ) {
			throw new Error( 'Column slug already exists', 500 );
		}

		$insert[ $this->args['slug'] ] = $this->args['label'];

		$column_keys = array_keys( $columns );
		$column_key  = $column_keys[ $this->args['order'] ] ?? 0;

		if ( $column_key ) {
			Utils::array_insert( $columns, $column_key, $insert );
		} else {
			$columns = array_merge( $columns, $insert );
		}
		return $columns;
	}

    /**
     * Make the column sortable
     *
     * @param array $columns default columns
     *
     * @throws Error Error in case of duplicated column slug.
     * @return array columns with the new one already inserted
     */
	public function add_sortable_column( $columns ) : array {
		if ( isset( $columns[ $this->args['slug'] ] ) ) {
			throw new Error( 'Column slug already exists', 500 );
		}

		$insert[ $this->args['slug'] ] = $this->args['label'];

		$column_keys = array_keys( $columns );
		$column_key  = $column_keys[ $this->args['order'] ] ?? 0;

		if ( $column_key ) {
			Utils::array_insert( $columns, $column_key, $insert );
		} else {
			$columns = array_merge( $columns, $insert );
		}

		return $columns;
	}

    /**
	 * Add the custom column to Taxonomies
     *
     * @param string     $content
     * @param string     $column_name
     * @param int|string $object_id
     *
     * @return string
     */
	public function column_hook_taxonomy( $content, $column_name, $object_id ): string {
		$content = '';

		if ( $this->args['slug'] === $column_name ) {
			ob_start();

			$this->column_content( $column_name, $object_id );

			$content = ob_get_clean();
		}

		return $content;
	}
    /**
	 * Add the custom column to Posts
     *
     * @param string     $column_name
     * @param int|string $object_id
     *
     * @return void
     */
	public function column_hook_post( $column_name, $object_id ): void {
		$content = '';

		if ( $this->args['slug'] === $column_name ) {
			ob_start();

			$this->column_content( $column_name, $object_id );

			$content = ob_get_clean();

		}

		echo $content;
	}

    /**
     * Add query var to make our column content be "filterable"
     *
     * @param array $vars
     *
     * @return array
     */
	public function filter_column_query_var( $vars ) : array {
		$vars[] = $this->args['slug'];
		return $vars;
	}

    /**
     * Undocumented function
     *
     * @param [type] $term_query
     *
     * @return WP_Meta_Query
     */
	public function order_terms_by_custom_column( $term_query ) {
		// global $pagenow;

		if ( ! is_admin() || ! isset( $_GET['orderby'] ) || ! isset( $_GET['order'] ) ) {
			return $term_query;
		}

		$order = sanitize_text_field( wp_unslash( $_GET['order'] ) );

		if ( $term_query->query_vars['taxonomy'][0] === $this->args['where-to-add'] ) {
			// set orderby to the named clause in the meta_query
			$term_query->query_vars['orderby'] = 'meta_value';
			$term_query->query_vars['order']   = $order ? $order : 'DESC';

			// the OR relation and the NOT EXISTS clause allow for terms without a meta_value at all

			$args                   = array(
				'relation' => 'OR',
				array(
					'key'  => $this->args['sort_meta_key'],
					'type' => 'CHAR',
				),
				array(
					'key'     => $this->args['sort_meta_key'],
					'compare' => 'NOT EXISTS',
				),
			);
			$term_query->meta_query = new WP_Meta_Query( $args );
		}

		return $term_query;
	}

    /**
     * Filter the content based on the provided filter
     *
     * @param [type] $query
     *
     * @return WP_Query
     */
	public function filter_terms_by_column_value( $query ) {

		if ( ! is_admin() || ! isset( $_GET[ $this->args['slug'] ] ) ) {
			return $query;
		}

		$term_query = isset( $query->query_vars['taxonomy'][0] ) && $query->query_vars['taxonomy'][0] === $this->args['where-to-add'];

		if ( $term_query ) {
            $filter = sanitize_text_field( wp_unslash( $_GET[ $this->args['slug'] ] ) );

			$args              = array(
				'relation' => 'OR',
				array(
					'key'   => $this->args['slug'],
					'value' => $filter,
				),
			);
			$query->meta_query = new WP_Meta_Query( $args );
		}

		return $query;
	}

    /**
     * Filter admin posts
     *
     * @param [type] $query
     *
     * @return void
     */
	public function filter_posts_by_column_value( $query ) : void {
		if ( is_admin() && isset( $_GET[ $this->args['slug'] ] ) ) {

            $post_query = isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === $this->args['where-to-add'];

			if ( $post_query ) {
                $filter = sanitize_text_field( wp_unslash( $_GET[ $this->args['slug'] ] ) );

				$query->set( 'meta_key', $this->args['slug'] );
				$query->set( 'meta_value', $filter );
			}
		}
	}

    /**
     * Custom column content output
     *
     * @param string     $column_name
     * @param string|int $object_id
     */
	public function column_content( $column_name, $object_id ) {}
}