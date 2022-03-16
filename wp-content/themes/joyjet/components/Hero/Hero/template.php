<?php
/**
 * Hero Main template file
 *
 * @see Theme\Components\Hero\Main
 *
 * @package joyjet
 */

use Theme\Components;

?>

<section class="_hero" style="background-image: url('<?php echo $background; ?>'); ">
    <div class="hero-inner text-white">
        <div class="container">
            <div class="col-md-6 py-4">
                <h1 class="display-4 fw-normal">
                    <?php echo $headline; ?>
                    <span class="dot text-primary">.</span>
                </h1>
                <p class="hero-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit eos iusto
                    accusantium est veritatis corporis architecto labore atque. Iste temporibus ullam sunt eos,
                    architecto delectus similique et soluta labore quibusdam.</p>
            </div>
        </div>
    </div>
</section>