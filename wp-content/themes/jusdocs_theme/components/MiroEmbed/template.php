<?php
/**
 * MiroEmbed template file
 *
 * @see Theme\Components\MiroEmbed
 *
 * @package jusdocs
 */

use Theme\Helpers\Utils;
?>

<div class="_miro-embed <?php echo $class; ?>">
    <div class="miro-iframe-container">
        <iframe class="main-frame" width="100%" height="500" src="<?php echo $src; ?>" frameBorder="0"
            scrolling="no"
            allowFullScreen>Miro Embed</iframe>
    </div>

</div>