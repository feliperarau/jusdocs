<?php

namespace Theme\Endpoints;

use SolidPress\Core\Hook;
use Theme\Helpers\RequestHandler;
use Theme\CustomFields\TaxonomySelect;
use Theme\Helpers\Dev;
use Throwable;

/**
 * ACF related endpoints and hooks
 */
class ACF extends Hook {

	/**
	 * Bind actions
	 */
	public function __construct() {
        // Backend terms dropdown
		$this->add_action( 'wp_ajax_get_related_terms_dropdown', 'get_related_terms_dropdown' );

        // Frontend terms dropdown
        $this->add_action( 'wp_ajax_get_frontend_related_terms_dropdown', 'get_frontend_related_terms_dropdown' );
        $this->add_action( 'wp_ajax_nopriv_get_frontend_related_terms_dropdown', 'get_frontend_related_terms_dropdown' );
	}

    /**
     * Get terms dropdown values
     *
     * @return void
     */
    public function get_related_terms_dropdown() : void {
		try {
			RequestHandler::validate_ajax_nonce( 'acf_nonce' );

            global $pagenow;
            $post_id = '';

            if ( 'profile.php' === $pagenow || 'user-edit.php' === $pagenow ) {
                $user    = wp_get_current_user();
                $post_id = "user_$user->ID";
            }

			$taxonomy          = filter_input( INPUT_POST, 'taxonomy' );
            $meta_field        = filter_input( INPUT_POST, 'meta_field' );
            $meta_value        = filter_input( INPUT_POST, 'meta_value' );
            $placeholder       = filter_input( INPUT_POST, 'placeholder' );
            $target_meta_field = filter_input( INPUT_POST, 'target_meta_field' );

            $choices = array(
                '0' => "- $placeholder -",
            );

            $term_choices = TaxonomySelect::get_dropdown_options( $taxonomy, $meta_field, $post_id, $target_meta_field, $meta_value );

            $arr1 = $choices + $term_choices;

			wp_send_json(
                array(
                    'error'   => false,
					'choices' => $arr1,
                )
            );
		} catch ( Throwable $error ) {
			RequestHandler::handle_error( $error );
		}
    }
    /**
     * Get terms dropdown values
     *
     * @return void
     */
    public function get_frontend_related_terms_dropdown() : void {
		try {
			RequestHandler::validate_ajax_nonce( 'input_taxonomy' );

			$taxonomy      = filter_input( INPUT_POST, 'taxonomy' );
            $terms_field   = filter_input( INPUT_POST, 'terms_field' );
            $term_selected = filter_input( INPUT_POST, 'term_selected' );

            $term_choices = TaxonomySelect::get_dropdown_options( $taxonomy, $terms_field, null, null, $term_selected );

			wp_send_json(
                array(
                    'error'   => false,
					'choices' => $term_choices,
                )
            );
		} catch ( Throwable $error ) {
			RequestHandler::handle_error( $error );
		}
    }
}