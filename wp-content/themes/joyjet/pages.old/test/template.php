<?php
/**
 * Index page template
 *
 * @see Theme\Page\Index
 *
 * @package joyjet
 */

use Theme\Components\Emails;
use Theme\ComponentPreset\Emails as PresetEmails;

?>

<div id="test-page" class="page">
    <?php

        echo new PresetEmails\UserApproved(
            array(
                'password' => 'HAHAHAHA',
            )
        );
		?>
</div>