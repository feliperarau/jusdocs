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
    <section class="home-splash">
        <?php
		echo new Components\Hero\Hero(
            [
				'headline' => __( 'SPACE', 'joyjet' ),
            ]
		);
		?>
    </section>

    <section class="home-posts">
        <?php
		echo new Components\PostsCarousel\PostsCarousel(
            []
		);
		?>
    </section>

    <section class="home-about container">
        <?php
		echo new Components\About\HomeCard(
            [
				'title' => $about_title,
				'text'  => $about_text,
				'image' => $about_image,
            ]
		);
		?>
    </section>
</main>