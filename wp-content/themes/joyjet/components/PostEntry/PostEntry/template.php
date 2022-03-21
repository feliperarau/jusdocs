<?php
/**
 * PostEntry template file
 *
 * @see Theme\Components\PostEntry\PostEntry
 *
 * @package joyjet
 */

use Theme\Components;
?>

<div class="_post-entry">
    <div class="card-thumbnail">
        <a href="<?php echo $link; ?>">
            <img src="<?php echo $thumbnail; ?>" class="card-img-top" alt="<?php echo $title; ?>" />
        </a>
    </div>
    <div class="card-body">
        <h5 class="card-title">
            <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
        </h5>
        <p class="card-text"><?php echo $content; ?></p>
    </div>
</div>