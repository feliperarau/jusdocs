<?php
/**
 * Authenticated page template file
 *
 * @see Theme\Pages\AuthenticatedPage
 *
 * @package joyjet
 */

use Theme\Components;
?>
<?php
	get_header();

if ( ! $hide_header ) {
	if ( $slim_header ) {
		echo new Components\HeaderSlim( array( 'class' => $header_class ) );
	} else {
		echo new Components\Header( array( 'class' => $header_class ) );
	}
}
?>

<?php echo $page; ?>

<?php
if ( ! $hide_footer ) {
	echo new Components\Footer( array( 'class' => $footer_class ) );
}

	get_footer();