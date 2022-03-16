<?php
/**
 * MainSidebar template file
 *
 * @see Theme\Components\MainSidebar\Component
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Helpers\Utils;
?>
<div
    class="_main-sidebar col d-flex flex-column <?php echo $class; ?> footer-<?php echo $footer_style; ?>">
    <div class="inner bg-color-primary text-white flex-1">
        <div class="sidebar-components d-flex flex-column align-items-center">
            <?php
			foreach ( $components as $component ) :
                echo ( $component ?? '' );
            endforeach;
			?>
        </div>
    </div>
</div>