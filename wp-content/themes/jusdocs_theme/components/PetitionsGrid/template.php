<?php
/**
 * PetitionsGrid template file
 *
 * @see Theme\Components\PetitionsGrid
 *
 * @package jusdocs
 */

use Theme\Components;
?>

<div class="_petitions-grid <?php echo $class; ?>">
    <?php
	foreach ( $petitions as $petition ) :
		echo new Components\PetitionCard( [ 'petition' => $petition ] );
    endforeach;
	?>
</div>