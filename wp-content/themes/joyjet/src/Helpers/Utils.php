<?php
/**
 * Utils class
 *
 * @package joyjet
 */

namespace Theme\Helpers;

/**
 * Utils class
 */
class Utils {

	/**
	 * Return url encoded svg for image placeholder
	 *
	 * @param integer $width - Svg width.
	 * @param integer $height - Svg height.
	 * @param string  $fill - Fill color, in case of hex format, the '#' must be omitted.
	 * @return string
	 */
	public static function image_placeholder( $width = 0, $height = 0, $fill = 'FFF' ): string {
		return "data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='{$width}' height='{$height}'%3e%3crect width='100%25' height='100%25' fill='%23{$fill}'/%3e%3c/svg%3e";
	}

	/**
	 * Get asset image full url
	 *
	 * @param string $filename
	 * @return string
	 */
	public static function get_asset_image_src( string $filename ): string {
		return get_stylesheet_directory_uri() . '/assets/images/' . $filename;
	}

	/**
	 * Get CSS markup for background image
	 *
	 * @param string $url
	 * @return string
	 */
	public static function background_image( string $url ): string {
		if ( $url ) {
			$bg = "style=\"
				background-image: url('$url');
				background-position: center;
				background-size: cover;
				\"";
		}

		return $bg ?? '';
	}

	/**
	 * Sanitize post data
	 *
	 * @param string $data
	 * @return string
	 */
	public static function sanitize_post( string $data ): string {
		return wp_strip_all_tags( stripslashes( sanitize_text_field( filter_input( INPUT_POST, $data ) ) ) );
	}

	/**
	 * Filter request data
	 *
	 * @param string $data
	 *
	 * @return string sanitized data
	 */
	public static function filter_request_data( $data ) {
		return ! empty( $data ) ? sanitize_text_field( wp_unslash( $data ) ) : '';
	}

	/**
	 * Hides partially the displayed email
	 *
	 * @param string $email
	 *
	 * @return string obfuscated email address
	 */
	public static function obfuscate_email( $email ) {
		$em   = explode( '@', $email );
		$name = implode( '@', array_slice( $em, 0, count( $em ) - 1 ) );
		$len  = floor( strlen( $name ) / 2 );

		return substr( $name, 0, $len ) . str_repeat( '*', $len ) . '@' . end( $em );
	}

	/**
	 * Mask string
	 *
	 * @param string $val
	 * @param string $mask
	 *
	 * @return string masked string
	 */
	public static function mask( string $val, string $mask ) : string {
		$masked      = '';
		$k           = 0;
		$mask_length = strlen( $mask ) - 1;

		for ( $i = 0; $i <= $mask_length; $i++ ) {
			if ( '#' === $mask[ $i ] ) {
				if ( isset( $val[ $k ] ) ) {
					$masked .= $val[ $k++ ];
                }
			} else {
				if ( isset( $mask[ $i ] ) ) {
					$masked .= $mask[ $i ];
                }
			}
		}
		return $masked;
	}

	/**
	 * Sends AJAX response message
	 *
	 * @param mixed $message
	 *
	 * @return void
	 */
	public static function send_json_message( $message ) : void {
		wp_send_json(
			array(
				'error'   => false,
				'message' => $message,
			)
		);
	}

	/**
	 * Convert timestamp for display.
	 *
	 * @param int    $timestamp Event timestamp.
	 * @param string $time_prefix Event timestamp.
	 * @param string $date_prefix Event timestamp.
	 *
	 * @return string Human readable date.
	 */
	public static function get_elapsed_time( $timestamp, $time_prefix = '', $date_prefix = '' ) : string {
		if ( empty( $timestamp ) ) {
			return '';
		}

		$def_time_prefix = __( 'há', 'joyjet' );
		$def_date_prefix = __( 'em', 'joyjet' );
		$time_prefix     = $time_prefix ? $time_prefix : $def_time_prefix;
		$date_prefix     = $date_prefix ? $date_prefix : $def_date_prefix;

		$time_diff = time() - $timestamp;

		if ( $time_diff >= 0 && $time_diff < DAY_IN_SECONDS ) {
			/* translators: %s: Human-readable time difference. */
			return sprintf( __( '%1$s %2$s atrás', 'joyjet' ), $time_prefix, human_time_diff( $timestamp ) );
		}

		return sprintf( '%s %s', $date_prefix, date_i18n( get_option( 'date_format' ), $timestamp ) );
	}

	/**
     * Do the redirect action
     *
     * @param string $url
     *
     * @return void
     */
    public static function redirect( $url ) : void {
		global $wp;

		$current_url = trailingslashit( home_url( $wp->request ) );

		if ( $url !== $current_url ) {
			exit( wp_safe_redirect( $url ) );
		}
    }

	/**
	 * Convert array to inline styles
	 *
	 * @param array $props
	 *
	 * @return string
	 */
	public static function inline_styles( array $props ) : string {
		$inline = '';

		foreach ( $props as $prop => $value ) {
			$inline .= "$prop: $value; ";
		}

		return rtrim( $inline, ' ' );
	}

	/**
	 * Format value to query serialized array item on database
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function array_query( string $value ) : string {
		return sprintf( ':"%s";', $value );
	}

	/**
	 * Meta query helper
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return array
	 */
	public static function query_meta_by( $key = '', $value = '' ) : array {
		return array(
			'key'     => $key ? $key : '',
			'compare' => '=',
			'value'   => $value ? $value : '',
		);
	}

	/**
	 * Display HTML attribute if the value exists
	 *
	 * @param string $attr
	 * @param string $value
	 *
	 * @return string
	 */
	public static function get_html_attribute( $attr = '', $value = '' ) : string {
		return ! empty( $attr ) && ! empty( $value ) ? "$attr=\"$value\"" : '';
	}

	/**
	 * Echo get_html_attribute
	 *
	 * @param string $attr
	 * @param string $value
	 *
	 * @return void
	 */
	public static function html_attribute( string $attr = '', $value = '' ) : void {
		echo self::get_html_attribute( $attr, $value );
	}

	/**
	 * Check for empty array or empty array items
	 *
	 * @param mixed $input
	 *
	 * @return boolean
	 */
	public static function is_array_empty( $input ) : bool {
		$result = true;

		if ( is_array( $input ) && count( $input ) > 0 ) {
			foreach ( $input as $value ) {
				$result = $result && self::is_array_empty( $value );
			}
		} else {
			$result = empty( $input );
		}

		return $result;
	}

	/**
	 * Insert in array
	 *
	 * @param array      $array
	 * @param int|string $position
	 * @param mixed      $insert
	 */
	public static function array_insert( &$array, $position, $insert ) {
		if ( is_int( $position ) ) {
			array_splice( $array, $position, 0, $insert );
		} else {
			$pos   = array_search( $position, array_keys( $array ), true );
			$array = array_merge(
                array_slice( $array, 0, $pos ),
                $insert,
                array_slice( $array, $pos )
			);
		}
	}
}