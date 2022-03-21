<?php
/**
 * PostViewCount
 *
 * @package jusdocs
 */

namespace Theme\Hooks\AdminColumns;

use Theme\Core\CustomAdminColumns;
use Theme\Models\Post;

/**
 * Register custom column for the Aircraft post Taxonomies
 */
class PostViewCount extends CustomAdminColumns {

    /**
     * Set the custom column parameters
     */
    public function __construct() {
        $this->args = array(
            'where-to-add' => 'forum_topic',
            'slug'         => 'post_view_count',
            'label'        => __( 'Visualizações', 'jusdocs' ),
            'can_filter'   => true,
        );

        parent::__construct( $this->args );
    }

    /**
     * Custom column content output
     *
     * @param string     $column_name
     * @param string|int $post_id
     *
     * @return void
     */
    public function column_content( $column_name, $post_id ) : void {
        $views = Post::get_post_views( $post_id );

        echo $views;
    }
}