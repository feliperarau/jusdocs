<?php
/**
 * Recover password form template file
 *
 * @see Theme\Components\Login\ForgotPasswordForm
 *
 * @package jusdocs
 */

use Theme\ComponentPreset\FormControls\InputCpf;
use Theme\Components;
use Theme\Endpoints\Component;

?>

<form action="" class="forgot-password-form <?php echo $class; ?>">
    <?php wp_nonce_field( 'forgot_password', 'forgot_password_nonce' ); ?>

    <h2 class="text-color-primary"><?php esc_html_e( 'Recuperação de senha', 'jusdocs' ); ?></h2>
    <p>
        <?php esc_html_e( 'Preencha os campos abaixo e aguarde, logo você receberá um e-mail para recuperar sua senha.', 'jusdocs' ); ?>
    </p>
    <div class="text-left">
        <?php
        echo new InputCpf(
            array(
				'name'  => 'forgot_user_login',
				'label' => __( 'Digite o seu CPF', 'jusdocs' ),
            )
        );

        echo new Components\FormControls\Alert();

        echo new Components\FormControls\Submit(
            array(
				'label' => __( 'Obter nova senha', 'jusdocs' ),
			)
        );
        ?>
    </div>
</form>