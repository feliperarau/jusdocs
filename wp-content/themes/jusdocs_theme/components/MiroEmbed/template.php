<?php
/**
 * MiroEmbed template file
 *
 * @see Theme\Components\MiroEmbed
 *
 * @package jusdocs
 */

?>

<div class="_miro-embed <?php echo $class; ?>">
    <div class="miro-iframe-container">
        <iframe class="main-frame" width="100%" height="500" src="<?php echo $src; ?>" frameBorder="0"
            scrolling="no"
            allowFullScreen>Miro Embed</iframe>
    </div>

    <!-- Button trigger modal -->
    <div class="toggle-wrapper">
        <button type="button" class="btn btn-primary toggle-modal">
            <?php echo $button_label; ?>
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon icon-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <iframe class="modal-frame" width="100%" height="100%" src="<?php echo $src; ?>" frameBorder="0"
                        scrolling="no"
                        allowFullScreen>Miro Embed</iframe>
                </div>
            </div>
        </div>
    </div>
</div>