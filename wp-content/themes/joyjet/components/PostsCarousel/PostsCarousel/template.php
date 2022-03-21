<?php
/**
 * PostsCarousel template file
 *
 * @see Theme\Components\PostsCarousel\PostsCarousel
 *
 * @package joyjet
 */

use Theme\Components;

?>

<div class="_posts-carousel">
    <div class="container">
        <div class="swiper-container">
            <!-- Swiper -->
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $posts as $post_item ) : ?>
                    <div class="swiper-slide">
                        <?php echo new Components\PostEntry\PostEntry( [ 'post' => $post_item ] ); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="swiper-nav swiper-button-next"><i class="icon icon-chevron-right"></i></div>
            <div class="swiper-nav swiper-button-prev"><i class="icon icon-chevron-left"></i></div>
        </div>
    </div>
</div>