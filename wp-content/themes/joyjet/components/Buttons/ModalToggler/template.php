<?php
/**
 * ModalToggler template file
 *
 * @see Theme\Components\Buttons\ModalToggler
 *
 * @package joyjet
 */

$modal_toggle_attrs = 'data-toggle="modal" data-target="#' . $modal_id . '"';
?>

<div class="_modal-toggler <?php echo $class; ?> align-<?php echo $align; ?>">
    <?php if ( 'button' === $element ) : ?>
    <button type="button" class="btn <?php echo $button_class; ?>" <?php echo $modal_toggle_attrs; ?>>
        <?php echo $label; ?>
    </button>

    <?php elseif ( 'anchor' === $element ) : ?>
    <a class="btn <?php echo $button_class; ?>" href="#" <?php echo $modal_toggle_attrs; ?>><?php echo $label; ?></a>
    <?php endif; ?>
</div>