<?php
/**
 * Profile page template file
 *
 * @see Theme\Pages\Profile
 *
 * @package joyjet
 */

use Theme\Components;

?>

<main id="profile-page" class="main-content page-bg-pattern-2">
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

                <div class="col-md-8">
                    <div class="row align-items-center align-items-lg-stretch">
                        <div class="col-md-8">
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
                        </div>
                        <div class="col-md-4">
                            <?php echo new Components\ProfileAvatar(); ?>
                        </div>
                    </div>
                    <?php echo $profile_form; ?>
                </div>
            </div>
        </div>
        <?php

		echo new Components\Modal(
            array(
				'id'      => 'profile-updated',
				'align'   => 'left',
				'content' => new Components\ModalContent\Notification(
					array(
						'title'   => __( 'Atualização de Cadastro Realizada!', 'joyjet' ),
						'content' => __( 'Parabéns, agora seu perfil está atualizado!', 'joyjet' ),
					)
				),
            )
		);
		?>
    </div>
</main>