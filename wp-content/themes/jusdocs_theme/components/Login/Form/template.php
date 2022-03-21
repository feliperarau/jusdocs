<?php
/**
 * Login form template file
 *
 * @see Theme\Components\Login\Form
 *
 * @package jusdocs
 */

use Theme\Components;
use Theme\ComponentPreset;
use Theme\Helpers\Dev;

?>

<form action="" class="_login-form <?php echo $class; ?>">
    <?php wp_nonce_field( 'user_login', 'user_login_nonce' ); ?>

    <div class="form-top mb-3 pb-7">

        <?php
		echo new Components\FormControls\InputMasked(
            array(
				'name'        => 'user_login',
				'mask'        => '000.000.000-00',
				'mask_args'   => array( 'reverse' => true ),
				'required'    => true,
				'label_class' => 'text-12',
				'placeholder' => __( 'CPF', 'jusdocs' ),
				'label'       => __( 'CPF', 'jusdocs' ),
            )
		);
		echo new Components\FormControls\Input(
            array(
				'name'        => 'user_password',
				'type'        => 'password',
				'required'    => true,
				'label_class' => 'text-12',
				'placeholder' => __( 'Senha', 'jusdocs' ),
				'label'       => __( 'Senha', 'jusdocs' ),
            )
		);
		?>

        <div class="
			alert
			<?php echo ! $user_message['message'] ? 'd-none' : ''; ?>
			<?php echo $user_message['error'] ? 'alert-danger' : 'alert-primary'; ?>
			form-alert" role="alert">
            <?php echo $user_message['message']; ?>
        </div>

        <div class="actions d-flex align-items-center justify-content-between">
            <div class="forgot-password">
                <?php
				echo new Components\Buttons\ModalToggler(
                    array(
						'modal_id'     => 'forgot-password',
						'label'        => __( 'Esqueceu a senha?', 'jusdocs' ),
						'button_class' => 'btn-link pl-0 no-lower',
                    )
				);
				?>
            </div>
            <button class="btn btn-primary">
                <?php esc_html_e( 'Entrar', 'jusdocs' ); ?>
                <div class="form-spinner spinner-border spinner-border-sm ml-2 d-none" role="status"></div>
            </button>
        </div>
    </div>
    <div class="form-bottom">
        <div class="mb-6 d-flex align-items-center justify-content-between">
            <a
                href="<?php echo $sign_up_url; ?>"
                class="text-color-calma-2"><?php esc_html_e( 'ainda não tem cadastro?', 'jusdocs' ); ?></a>
            <a href="<?php echo $sign_up_url; ?>" class="btn btn-primary"><?php esc_html_e( 'Cadastrar', 'jusdocs' ); ?></a>
        </div>

        <?php
		echo new Components\Buttons\ModalToggler(
            array(
				'modal_id'     => 'policy',
				'label'        => __( 'Politicas de Privacidade e Termos de Uso', 'jusdocs' ),
				'button_class' => 'btn-link p-0 text-no-transform text-left',
			)
        );
		?>
    </div>

    <?php echo new ComponentPreset\Modal\Compliance(); ?>
</form>

<?php
echo new Components\Modal(
    array(
		'id'      => 'forgot-password',
		'content' => new Components\Login\ForgotPasswordForm(),
	)
);