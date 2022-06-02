<?php
/**
 * Petitions Shortcode
 *
 * @package jusdocs
 */

namespace Theme\Hooks\Shortcodes;

use SolidPress\Core\Hook;

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
     * Helper function to get the petition stars
     *
     * @param int $rating
     *
     * @return string
     */
	public static function get_stars( $rating ) {
		ob_start();

		echo <<<HTML
            <div class="stars d-flex">
        HTML;

		for ( $i = 0; $i < 5; $i++ ) :
			$icon = $i < $rating ? 'solid' : 'regular';

			echo <<<HTML
                <span class="star"><i class="icon icon-star-$icon"></i></span>
            HTML;
			endfor;

		echo <<<HTML
            </div>
        HTML;

		$stars = ob_get_clean();

		return $stars;
	}

	/**
	 * The content of the shortcode
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
	public function shortcode_content( $attrs ) {
        $api_endpoint = 'https://api.jusdocs.com/api/v2/petitions';
        $request_url  = add_query_arg(
            [
				'limit'   => 3,
				'page'    => 1,
				'orderBy' => 'updated_at',
			],
            $api_endpoint
        );

		$request = wp_remote_get( $request_url );

		if ( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );

		if ( empty( $data ) ) {
			return;
		}

		ob_start();

		echo <<<HTML
            <div class="petitions-container">
        HTML;

		foreach ( $data->items as $item ) :
			$dt = new \DateTime( $item->updated_at, wp_timezone() );

			$author       = $item->author ?? [];
			$author_name  = $author->name ?? '';
			$name_arr     = explode( ' ', $author_name );
            $stars_number = ! empty( $item->avaliations->stars ) ? (int) $item->avaliations->stars : 0;

			$f_name   = substr( $name_arr[0] ?? '', 0, 1 );
			$s_name   = substr( $name_arr[1] ?? '', 0, 1 );
			$initials = $f_name . $s_name;
			$stars    = self::get_stars( $stars_number );
			$date     = $dt ? $dt->format( 'd/m/Y' ) : '';

			echo <<<HTML
                <div class="card">
                    <div class="header">
                        <div class="avatar">$initials</div>
                        <div>
                            <p>$author_name</p>
                            <span>Advogado(a)</span>
                        </div>
                    </div>
                    <div class="meta">
                        <div class="stars-container">$stars</div>
                        <div class="last-updated"><i>Atualizada em: $date</i></div>
                    </div>
                    <div class="title">
                        <h4><a href="https://jusdocs.com/peticoes/{$item->slug}"
                                target="_blank">{$item->title}</a></h4>
                    </div>
                    <div class="info">
                        <div>
                            <span>Área do direito:</span>
                            <p>{$item->area}</p>
                        </div>
                        <div>
                            <span>Tipo de petição:</span>
                            <p>{$item->type->name}</p>
                        </div>
                    </div>
                </div>
            HTML;
		endforeach;

		echo <<<HTML
            </div>
        HTML;

        $petitions = ob_get_clean();

        return $petitions;
	}
}