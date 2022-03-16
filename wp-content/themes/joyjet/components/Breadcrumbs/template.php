<?php
/**
 * Breadcrumbs template file
 *
 * @see Theme\Components\Breadcrumbs
 *
 * @package joyjet
 */

?>

<div class="_breadcrumbs font-weight-600 pb-6 text-sm <?php echo $class; ?>">
    <?php
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p class="m-0 p-0"><i class="icon icon-arrow-breadcrumbs"></i>', '</p>' );
	}
	?>
</div>