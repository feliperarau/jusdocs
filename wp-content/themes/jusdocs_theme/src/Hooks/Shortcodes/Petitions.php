<?php
/**
 * Petitions Shortcode
 *
 * @package jusdocs
 */

namespace Theme\Hooks\Shortcodes;

use SolidPress\Core\Hook;
use Theme\Components;

/**
 * Petitions shortcode class
 */
class Petitions extends Hook {

	/**
	 * Adds actions
	 */
	public function __construct() {
		add_shortcode( 'petitions', [ $this, 'shortcode_content' ] );
	}

	/**
	 * The content of the shortcode
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
	public function shortcode_content( $attrs ) {
		$params = shortcode_atts(
            array(
				'number' => 3,
            ),
            $attrs
        );

        $api_endpoint = 'https://api.jusdocs.com/api/v2/petitions';
        $request_url  = add_query_arg(
            [
				'limit'   => 3,
				'page'    => 1,
				'orderBy' => 'updated_at',
			],
            $api_endpoint
        );

        $petitions = get_transient( 'jusdocs_latest_petitions' );

        if ( false === $petitions ) {
            $response = wp_remote_get( $request_url );

            if ( is_wp_error( $response ) ) {
                return false; // Bail early
            }

            $body      = wp_remote_retrieve_body( $response );
            $petitions = json_decode( $body );

            set_transient( 'jusdocs_latest_petitions', $petitions, 1 * HOUR_IN_SECONDS );
        }

		if ( empty( $petitions ) ) {
            return;
        }

        $petitions = new Components\PetitionsGrid(
            [
				'petitions' => $petitions->items ?? [],
				'number'    => $params['number'],
			]
        );

        return $petitions;
	}
}