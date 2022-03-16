<?php
/**
 * FrontPage template file
 *
 * @see Theme\Pages\FrontPage
 *
 * @package joyjet
 */

use Theme\Components;
?>

<main id="front-page" class="main-content">
    <?php
	echo new Components\Hero\Hero(
        [
			'headline' => __( 'SPACE', 'joyjet' ),
		]
    );
	?>

</main>