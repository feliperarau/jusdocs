<?php
/**
 * Archive template file
 *
 * @see Theme\Pages\Archive
 *
 * @package jusdocs
 */

use Theme\Components;
?>

<main id="archive" class="main-content">

  <div class="container">

    <div class="row">
      <div class="col-md-3">
        <?php echo the_content(); ?>
      </div>
      <div class="col-md-9">

        <div class="content">
          <?php echo the_content(); ?>
        </div>
      </div>
    </div>
  </div>
</main>