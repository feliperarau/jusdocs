<?php
/**
 * UserHello Info template file
 *
 * @see Theme\Components\MainSidebar\Modules\UserHello
 *
 * @package jusdocs
 */

use Theme\Components;
?>

<div class="_user-hello <?php echo $class; ?>">
    <div class="user-headline d-inline-flex flex-column">
        <span class="title text-18 font-weight-light"><?php echo $hello; ?></span>
        <h3 class="name text-28 font-weight-bold mb-0"><?php echo $name; ?></h3>

        <div class="text-right">
            <a href="<?php echo $edit_profile_url; ?>"
                class="edit text-16 d-inline-flex btn btn-link p-0 m-0 text-white"><?php esc_html_e( 'editar conta', 'jusdocs' ); ?></a>
        </div>
    </div>
</div>