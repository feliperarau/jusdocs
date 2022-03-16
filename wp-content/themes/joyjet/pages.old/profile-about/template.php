<?php
/**
 * Profile about page template file
 *
 * @see Theme\Pages\ProfileAbout
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Endpoints\Component;
use Theme\Helpers\Utils;

?>

<main id="profile-about" class="main-content page-bg-pattern-2">
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
                    <div class="col-md-10 p-0">

                        <div class="about-header mb-6">
                            <h1 class="text-primary text-28"><?php esc_html_e( 'sobre o:', 'joyjet' ); ?></h1>
                            <?php
							echo new Components\Image(
                                array(
									'class' => 'ml-md-8 pl-md-6 mt-n4',
									'src'   => Utils::get_asset_image_src( 'logo/logo-about.svg' ),
                                )
							);

							?>
                        </div>
                        <?php
						echo new Components\PageContent(
                            array(
								'class'   => 'mb-8',
								'content' => get_the_content(),
                            )
						);
						?>

                        <div class="actions text-center">
                            <div class="grid">
                                <a href="<?php echo $faq_url; ?>"
                                    class="btn btn-primary"><?php esc_html_e( 'Perguntas Frequentes', 'joyjet' ); ?></a>
                                <a href="<?php echo $contact_url; ?>"
                                    class="btn btn-primary"><?php esc_html_e( 'Fale Conosco', 'joyjet' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>