<?php
/**
 * Reset password page template file
 *
 * @see Theme\Pages\ResetPassword
 *
 * @package joyjet
 */

use Theme\Components;
?>

<main id="reset-password-page" class="page-bg-pattern-1 auth-pages">
    <div class="bg-vertical-bar page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-7 order-2 order-md-1">
                    <?php
					echo new Components\PageContent(
                        array(
							'class'   => 'mb-4',
							'title'   => get_the_title(),
							'content' => get_the_content(),
                        ),
					);

					if ( ! $error ) :
						?>
                    <p class="text-color-calma-2">
                        <?php
						// translators: user email
						printf( __( 'Alteração de senha para o e-mail: <strong class="ml-3">%s</strong>', 'joyjet' ), $user_email );
						?>
                    </p>

						<?php
						echo new Components\Profile\ChangePasswordForm(
                            array(
								'key'        => $key,
								'login'      => $login,
								'user_email' => $user_email,
								'action'     => 'recover',
								'class'      => 'mb-4',
                            )
						);
                else :
                    ?>
                    <p><?php echo $error->getMessage(); ?></p>
                    <a class="btn btn-primary inline-button"
                        href="<?php echo $error_page_redirect; ?>"><?php esc_html_e( 'Voltar', 'joyjet' ); ?></a>
                    <?php
                endif;
                ?>
                </div>
                <div class="mt-n8 mt-md-0 col-md-5 order-1 order-md-2 mb-8">
                    <?php
					echo new Components\Image(
                        array(
                            'class' => 'd-block mx-auto',
							'width' => 420,
							'src'   => $logo,
                        )
					);
					?>
                </div>
            </div>
        </div>
    </div>
</main>