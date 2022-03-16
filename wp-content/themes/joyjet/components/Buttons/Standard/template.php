<?php
/**
 * Default Button template file
 *
 * @see Theme\Components\Buttons\Standard
 *
 * @package joyjet
 */

?>

<<?php echo $tag; ?>
    <?php echo ( $type ? "type=\"$type\"" : '' ); ?>
    <?php echo ( $link ? "href=\"$link\"" : '' ); ?>
    <?php echo ( $target ? "target=\"$target\"" : '' ); ?>
    <?php echo ( $state ? "data-state=\"$state\"" : '' ); ?>
    <?php echo ( $custom_data_tpl ? $custom_data_tpl : '' ); ?>
    class="<?php echo $class; ?>">
    <span class="btn-text">
        <?php echo $text; ?>
    </span>
    <?php if ( $icon ) : ?>
    <i class="icon <?php echo "icon-$icon"; ?>"></i>
    <?php endif; ?>
    <div class="form-spinner spinner-border spinner-border-sm ml-2 d-none" role="status"></div>
</<?php echo $tag; ?>>