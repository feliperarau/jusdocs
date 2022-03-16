<?php
/**
 * Footer template
 *
 * @see Theme\Components\Footer
 *
 * @package joyjet
 */

use Theme\Components;
?>

<footer class="_footer text-color-footer-text-color container <?php echo $class; ?>">
    <div class="footer-wrapper">
        <div class="footer-inner">
            <div
                class="content-footer d-md-flex text-center text-md-left justify-content-md-between align-items-center bg-color-light-5 py-4 py-md-0 flex-1">
                <p class="pl-4 copy">
                    <?php echo $copyright; ?>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php get_footer(); ?>