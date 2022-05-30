<?php
/**
 * NavMenu template file
 *
 * @see Theme\Components\NavMenu
 *
 * @package jusdocs
 */

?>
<div
    class="_nav-menu collapse navbar-collapse d-lg-flex justify-content-end"
    id="navbarNav">

    <button
        class="_menu-toggler navbar-toggler collapsed"
        type="button"
        data-bs-toggle="collapse"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="open-icon ei ei-icon_menu"></span>
        <span class="closed-icon ei ei-icon_close"></span>
    </button>

    <?php
    if ( has_nav_menu( 'main-menu' ) ) :
		wp_nav_menu(
			[
				'theme_location'  => 'main-menu',
				'menu_class'      => 'navbar-nav',
				'container_class' => 'menu-container',
                'container'       => 'nav',
				'items_wrap'      => '<ul id="primary-menu-list" class="%2$s">%3$s</ul>',
				'fallback_cb'     => false,
            ]
        );
    endif;
	?>
</div>