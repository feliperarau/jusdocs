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

<div class="_hero" style="background-image: url('<?php echo $background; ?>'); ">
    <div class="hero-inner d-flex flex-column">
        <div class="container flex-grow-1 d-flex align-items-center">
            <div class="col-md-6 py-4">
                <h1 class="display-4 fw-normal text-white"><?php echo $headline; ?><span class="dot text-primary"><i
                            class="icon icon-circle-solid"></i></span>
                </h1>
                <p class="hero-text text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit eos
                    iusto
                    accusantium est veritatis corporis architecto labore atque. Iste temporibus ullam sunt eos,
                    architecto delectus similique et soluta labore quibusdam.</p>

                <?php
                echo new Components\Buttons\Solid(
                    [
						'label' => __( 'More about us', 'joyjet' ),
					]
                );
				?>
            </div>
        </div>

        <?php
            echo new Components\Hero\Trending();
		?>
    </div>
</div>