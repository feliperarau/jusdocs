<?php
/**
 * MainSidebar CheckinRanking template file
 *
 * @see Theme\Components\MainSidebar\Modules\CheckinRanking
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Helpers\Utils;

?>

<div class="_checkin-ranking bg-color-calma align-self-stretch rounded-20 <?php echo $class; ?>">
    <h2 class="text-center text-color-calma-2 text-32"><?php echo $title; ?></h2>
    <p class="text-color-dark-tertiary mb-0"><?php echo $description; ?></p>

    <div class="podium-container d-flex justify-content-center mt-6">
        <div class="podium d-flex">
            <div class="second podium-col">
                <?php if ( $reactions->second->id ) : ?>
                <img class="media type-<?php echo $reactions->second->media->type; ?>"
                    src="<?php echo $reactions->second->media->src; ?>" />
                <?php endif; ?>

                <div class="stand">
                    <span class="number">2</span>
                </div>
            </div>
            <div class="first podium-col ">

                <?php if ( $reactions->first->id ) : ?>
                <img class="media type-<?php echo $reactions->first->media->type; ?>"
                    src="<?php echo $reactions->first->media->src; ?>" />
                <?php endif; ?>

                <div class="stand">
                    <span class="number">1</span>
                    <span class="count"><?php printf( '%sx', $reactions->first->count ); ?></span>
                </div>
            </div>
            <div class="third podium-col">
                <?php if ( $reactions->third->id ) : ?>
                <img class="media type-<?php echo $reactions->third->media->type; ?>"
                    src="<?php echo $reactions->third->media->src; ?>" />
                <?php endif; ?>
                <div class="stand">
                    <span class="number">3</span>
                </div>
            </div>
        </div>
    </div>
</div>