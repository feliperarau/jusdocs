<?php
/**
 * Image template file
 *
 * @see Theme\Components\Image
 *
 * @package jusdocs
 */

?>

<img
    <?php echo ( $class ? "class=\"$class\"" : '' ); ?>
    <?php echo ( $width ? "width=\"$width\"" : '' ); ?>
    <?php echo ( $height ? "height=\"$height\"" : '' ); ?>
    <?php echo ( $alt ? "alt=\"$alt\"" : '' ); ?>

    src="<?php echo $src; ?>" />