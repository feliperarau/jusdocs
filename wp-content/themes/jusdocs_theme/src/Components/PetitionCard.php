<?php
/**
 * PetitionCard component
 *
 * @package jusdocs
 */

namespace Theme\Components;

use SolidPress\Core\Component;
use Theme\Helpers\Utils;

/**
 * PetitionCard
 */
class PetitionCard extends Component {
	/**
	 * Component template path relative to theme root
	 *
	 * @var string
	 */
	public $template = 'components/PetitionCard/template';

	/**
     * Component default props
     *
     * @var array
     */
	public $props = [
		'class'    => '',
		'petition' => [],
	];

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
	 * Values returned by get_props will be avaliable at the template as variables
	 *
	 * @return array
	 */
	public function get_props(): array {
        $petition = (object) $this->props['petition'];

		$dt = new \DateTime( $petition->updated_at, wp_timezone() );

		$author       = $petition->author ?? [];
		$author_name  = $author->name ?? '';
		$name_arr     = explode( ' ', $author_name );
		$stars_number = ! empty( $petition->avaliations->stars ) ? (int) $petition->avaliations->stars : 0;

		$f_name   = substr( $name_arr[0] ?? '', 0, 1 );
		$s_name   = substr( $name_arr[1] ?? '', 0, 1 );
		$initials = $f_name . $s_name;
		$stars    = self::get_stars( $stars_number );
		$date     = $dt ? $dt->format( 'd/m/Y' ) : '';

		return [
			'initials'      => $initials,
			'author_name'   => $author_name,
			'stars'         => $stars,
			'date'          => $date,
			'slug'          => $petition->slug ?? '',
			'title'         => $petition->title ?? '',
			'area'          => $petition->area ?? '',
			'petition_type' => $petition->type->name ?? '',
		];
	}
}