<?php
/**
 * Page template
 *
 * @see Theme\Pages\Page
 *
 * @package Joyjet
 */

use Theme\Components;
?>

<div id="page" class="page">

    <div class="container mt-7">
        <?php if ( get_the_title() ) : ?>
        <div class="title font-size-lg">
            <h1 class="h4 text-dark"><?php the_title(); ?></h1>
        </div>
        <?php endif; ?>
        <?php the_content(); ?>
    </div>
</div>