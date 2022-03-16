<?php
/**
 * Profile privacy policy page template file
 *
 * @see Theme\Pages\ProfilePrivacyPolicy
 *
 * @package joyjet
 */

use Theme\Components;

?>

<main id="profile-privacy-policy" class="main-content page-bg-pattern-2">
    <div class="page-wrapper d-flex">
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
							'class'   => 'mb-8',
							'title'   => get_the_title(),
							'content' => get_the_content(),
                        ),
					);
					?>
                    <h2 class="text-uppercase text-14 text-color-primary alt-font-family">
                        <?php esc_html_e( 'Políticas de Privacidade', 'joyjet' ); ?>
                    </h2>

                    <?php
					echo new Components\PageContentPolicy(
                        array(
							'content' => $privacy_policy,
							'class'   => 'text-color-calma-2 alt-font-family',
                        ),
					);
					?>
                </div>
            </div>
        </div>
    </div>
</main>