<?php
/**
 * Header template file
 *
 * @see Theme\Components\Header\Header
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Helpers\Utils;

get_header();
?>
<header class="_header">
    <nav class="navbar navbar-dark navbar-expand-lg">
        <div class="container align-items-between">
            <a href="<?php echo $home_permalink; ?>" aria-label="<?php echo $site_name; ?>" class="d-flex">
                <?php
                echo new Components\Image(
                    [
                        'src'   => $logo_image,
                        'alt'   => __( 'Logo', 'joyjet' ),
                        'width' => 55,
                    ]
                );
                ?>
            </a>

            <?php echo new Components\Header\NavMenu(); ?>

        </div>
    </nav>
</header>