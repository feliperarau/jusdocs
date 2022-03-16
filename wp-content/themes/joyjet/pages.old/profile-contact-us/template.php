<?php
/**
 * Profile contact us template file
 *
 * @see Theme\Pages\Profile
 *
 * @package joyjet
 */

use Theme\Components;

?>

<main id="profile-contact-us" class="main-content page-bg-pattern-2">
    <div class="page-wrapper d-flex bg-logo-baloon">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4 col-xl-3 mt-md-8 pt-md-1">
                    <?php
					echo new Components\Profile\Sidebar(
                        array(
							'class' => 'base-shadow',
                        )
					);
					?>
                </div>

                <div class="col-12 col-md-8">
                    <?php echo new Components\Breadcrumbs(); ?>
                    <?php
					echo new Components\PageContent(
                        array(
							'class'   => 'mb-4',
							'title'   => get_the_title(),
							'content' => get_the_content(),
                        ),
					);
					?>

                    <?php
					echo new Components\Forms\Contact(
                        array(
							'class' => 'col-md-8 px-0',
                        )
					);
					?>
                </div>
            </div>
        </div>
    </div>
</main>