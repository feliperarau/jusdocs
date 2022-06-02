<?php
/**
 * MainSidebar Menu template file
 *
 * @see Theme\Components\MainSidebar\Menu
 *
 * @package jusdocs
 */

use Theme\Components;
?>

<div class="_user-sidebar-menu <?php echo $class; ?>">
    <?php
    echo new Components\Profile\Menu(
        array(
			'menu_name' => $menu_name,
			'menu'      => $menu,
        )
    );
    ?>
</div>