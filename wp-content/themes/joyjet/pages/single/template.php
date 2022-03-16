<?php
/**
 * Single template file
 *
 * @see Theme\Pages\Single
 *
 * @package joyjet
 */

use Theme\Components;
?>

<main id="single" class="main-content">

    <?php
	echo new Components\Hero\Hero(
        [
			'headline' => __( 'SPACE', 'joyjet' ),
		]
    );
	?>
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                Sidebar
            </div>
            <div class="col-md-9">
                <h1><?php echo $title; ?></h1>
                <div class="content"><?php echo $content; ?></div>
            </div>
        </div>
    </div>
</main>