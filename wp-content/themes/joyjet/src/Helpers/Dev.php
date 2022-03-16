<?php
/**
 * Dev helpers
 *
 * @package joyjet
 */

namespace Theme\Helpers;

use Throwable;

/**
 * Dev helper functions
 */
class Dev {
    /**
     * Dumps the passed variable
     *
     * @param mixed $to_dump
     *
     * @return void
     */
    public static function dump( $to_dump ) : void {
        echo '<pre>';

        var_dump( $to_dump );

        echo '</pre>';
    }

    /**
     * Dumps the passed variable in AJAX requests
     *
     * @param mixed $to_dump
     *
     * @return void
     */
    public static function dump_ajax( $to_dump ) : void {
        ob_start();

        self::dump( $to_dump );

        $debug = ob_get_clean();

        wp_send_json(
            array(
                'error'   => false,
                'message' => $debug,
            )
        );
    }

    /**
     * Log erros to the theme directory
     *
     * @param mixed $error_data
     *
     * @return void
     */
    public static function log_error( $error_data ) : void {
        $error_str = $error_data;

        if ( is_array( $error_data ) || is_object( $error_data ) ) {
            ob_start();

            print_r( $error_data );

            $error_str = ob_get_clean();
        }

        error_log( $error_str, 3, get_template_directory() . '/joyjeterrors.log' );
    }

    /**
	 * Handle Throwable error and return json response to the client.
	 *
	 * @param  Throwable $error
	 * @param  bool      $ajax
	 * @return void
	 */
	public static function handle_error( Throwable $error, $ajax = false ): void {
		$error_code    = $error->getCode() ? $error->getCode() : 500;
		$error_message = RequestHandler::get_error_message( $error );
		$error_log     = "[$error_code] - " . $error->getMessage();

		if ( 500 === $error_code ) {
			$error_log .= PHP_EOL . $error->getTraceAsString();
		}

		self::log_error( $error_log );

        if ( $ajax ) {
            wp_send_json(
                array(
                    'error'     => true,
                    'errorCode' => $error_code,
                    'message'   => $error_message,
                ),
                $error_code
            );
        }
	}
}