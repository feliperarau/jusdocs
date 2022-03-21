<?php
/**
 * Index page template
 *
 * @see Theme\Page\Index
 *
 * @package joyjet
 */

use Theme\Components\Emails;

?>

<div id="test-page" class="page">
    <?php

        echo new Emails\TransactionalNotification(
            array(
                'body_text_1' => 'Olá Mundo',
            )
        );
		?>
</div>