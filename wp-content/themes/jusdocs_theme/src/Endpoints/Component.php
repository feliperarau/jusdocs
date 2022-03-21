<?php

namespace Theme\Endpoints;

use Error;
use SolidPress\Core\Hook;
use SolidPress\Core\Theme;
use Theme\Helpers\Dev;
use Theme\Helpers\RequestHandler;
use Throwable;

/**
 * Component related endpoints and hooks
 */
class Component extends Hook {

	/**
	 * Bind actions
	 */
	public function __construct() {
        // Bind get_component request handler
		$this->add_action( 'wp_ajax_get_component', 'get_component' );
	}

	/**
	 * Get component as string
	 *
	 * @return void
	 *
	 * @throws Error - Throws in case of missing inputs and update failure.
	 */
	public function get_component(): void {
		try {
			$allowed_components = array(
				'Forms\CheckIn',
				'CopyBook\Annotations',
				'LearnPress\Quiz\Results',
				'LearnPress\Quiz\SuccessEnd',
				'LearnPress\Quiz\FormFailedRetake',
				'SelfEvaluation\SuggestionForm',
			);

			$component = filter_input( INPUT_POST, 'component' );
			$args      = json_decode( filter_input( INPUT_POST, 'args' ) ?? '', true ) ?? array();

			if ( ! $component || ! in_array( $component, $allowed_components, true ) ) {
				throw new Error( 'Componente inválido', 400 );
			}

			$theme = Theme::get_instance();

			$component_namespaced = $theme->namespace . '\\Components\\' . $component;

			$allowed_args = $component_namespaced::$public_props ?? array();

			if ( $args && empty( $allowed_args ) ) {
				throw new Error( 'Parâmetros inválidos.', 400 );
			} else {
				foreach ( $args as $arg_name => $arg_val ) {
					if ( array_search( $arg_name, $allowed_args, true ) === false ) {
						throw new Error( 'Parâmetros inválidos...', 400 );
					}
				}
			}

			wp_send_json(
                array(
					'component' => (string) new $component_namespaced( $args ?? array() ),
					'error'     => false,
                )
            );
		} catch ( Throwable $error ) {
			RequestHandler::handle_error( $error );
		}
	}
}