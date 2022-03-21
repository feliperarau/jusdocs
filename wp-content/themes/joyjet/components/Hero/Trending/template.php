<?php
/**
 * Hero Main template file
 *
 * @see Theme\Components\Hero\Trending
 *
 * @package joyjet
 */

use Theme\Components;

?>

<div class="_hero-trending text-white">
    <div class="container py-4">
        <div class="row">
            <div class="trending-item col-md-3">
                <p>
                    <?php printf( __( 'Trending<br><span class="text-primary hightlight">Today</span>', 'joyjet' ) ); ?>
                </p>
            </div>
            <?php foreach ( $trending_posts as $trending ) : ?>
            <div class="trending-item col-md-3">
                <a href="<?php echo $trending->link; ?>"><?php echo $trending->title; ?></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>