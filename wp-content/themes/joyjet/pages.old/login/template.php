<?php
/**
 * Login page template file
 *
 * @see Theme\Pages\Login
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Helpers\Utils;

$login = sanitize_text_field( isset( $_GET['login'] ) ? wp_unslash( $_GET['login'] ) : '' );
get_header();

?>

<main id="login-page" class="auth-pages page-bg-pattern-1">
    <div class="bg-shapes d-flex flex-column">
        <div class="bg-bar d-flex">
            <div class="container d-flex flex-column flex-1">
                <div class="row flex-1 align-items-center">
                    <div class="form-action col-md-6 col-lg-4 bg-color-calma offset-lg-1">

                        <div class="form-inner">
                            <div class="d-flex justify-content-center mb-4">
                                <?php
								echo new Components\LazyImage(
                                    array(
										'width'            => 'auto',
										'height'           => 140,
										'src'              => Utils::get_asset_image_src( 'logo/logo-43.svg' ),
										'alt'              => __( 'Calmamente', 'joyjet' ),
										'placeholder_fill' => 'FFF',
                                    )
								);
								?>
                            </div>

                            <div class="info-login">
                                <h1 class="text-32 text-color-primary mb-2"><?php esc_html_e( 'Login', 'joyjet' ); ?></h1>
                                <p class="text-color-calma-2">
                                    <?php echo $subtitle; ?>
                                </p>
                            </div>

                            <?php echo new Components\Login\Form(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>