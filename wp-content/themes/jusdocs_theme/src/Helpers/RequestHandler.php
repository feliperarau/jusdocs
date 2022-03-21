<?php
/**
 * RequestHandler
 *
 * @package jusdocs
 */

namespace Theme\Helpers;

use Error;
use Throwable;

/**
 * RequestHandler helper functions
 */
class RequestHandler {
	/**
	 * Validate a request nonce, expects an nonce input name wich is the action name suffixed with '_nonce'.
	 *
	 * @param  mixed $action - action name
	 * @throws Error In case of invalid nonce.
	 * @return void
	 */
	public static function validate_nonce( $action ): void {
		if (
			! isset( $_POST[ "{$action}_nonce" ] )
			|| ! wp_verify_nonce( filter_input( INPUT_POST, "{$action}_nonce" ), $action )
		) {
			throw new Error( 'Invalid nonce!', 400 );
		}
	}

    /**
	 * Validate a request nonce, expects an nonce input name wich is the action name suffixed with '_nonce'.
	 *
	 * @param  mixed $action - action name
	 * @throws Error In case of invalid nonce.
	 * @return void
	 */
	public static function validate_ajax_nonce( $action ): void {
		if (
			! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( filter_input( INPUT_POST, 'nonce' ), $action )
		) {
			throw new Error( 'Invalid nonce!', 400 );
		}
	}

	/**
	 * Get Throwable error message based on system settings.
	 *
	 * @param  Throwable $error
	 * @return string
	 */
	public static function get_error_message( Throwable $error ): string {
		if ( ( $error->getCode() === 500 || ! $error->getCode() ) && ( JUSDOCSDEV !== true ) ) {
			return 'Ops, ocorreu um erro ao executar sua requisição!<br/> Atualize a página do navegador e tente novamente em alguns instantes.';
		}

		return $error->getMessage();
	}

	/**
	 * Handle Throwable error and return json response to the client.
	 *
	 * @param  Throwable $error
	 * @return void
	 */
	public static function handle_error( Throwable $error ): void {
		Dev::handle_error( $error, true );
	}

	/**
	 * Send a JSON message to the request.
	 *
	 * @param string  $message
	 * @param boolean $error
	 *
	 * @return void
	 */
	public static function message( string $message, bool $error = false ) : void {
		wp_send_json(
			array(
				'error'   => $error,
				'message' => $message,
			)
		);
	}
}