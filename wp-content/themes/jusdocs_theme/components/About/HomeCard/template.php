<?php
/**
 * About HomeCard template file
 *
 * @see Theme\Components\About\HomeCard
 *
 * @package jusdocs
 */

use Theme\Components;
?>

<div class="_about-home-card">
    <div class="row">
        <div class="col-md-4 about-image mb-5 mb-lg-0">
            <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" />
        </div>
        <div class="col-md-8 about-text">
            <h2><?php echo $title; ?></h2>
            <div><?php echo $text; ?></div>
        </div>
    </div>
</div>