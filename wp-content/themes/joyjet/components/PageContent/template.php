<?php
/**
 * Page content template file
 *
 * @see Theme\Components\PageContent
 *
 * @package joyjet
 */

use Theme\Components;

?>

<div class="_page-content <?php echo $class; ?>">

    <?php if ( $title ) : ?>
    <h1 class="text-color-primary text-28"><?php echo $title; ?></h1>
    <?php endif; ?>

    <?php if ( $content ) : ?>
    <div class="main-text-description"><?php echo $content; ?></div>
    <?php endif; ?>

    <?php
	if ( $image ) :
		echo new Components\LazyImage(
            array(
				'class' => 'pl-2 img-fluid',
				'image' => $image,
            )
		);
	endif;
	?>
</div>