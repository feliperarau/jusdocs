<?php
/**
 * Subscribe Form main template
 */

$submit_button_text = $this->get_settings( 'submit_button_text' );
$submit_placeholder = $this->get_settings( 'submit_placeholder' );
$layout             = $this->get_settings( 'layout' );
$use_icon           = $this->get_settings( 'add_button_icon' );

$this->add_render_attribute( 'main-container', 'class', array(
	'jet-subscribe-form',
	'jet-subscribe-form--' . $layout . '-layout',
) );

$this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );

$instance_data = apply_filters( 'jet-elements/subscribe-form/input-instance-data', array(), $this );

$instance_data = json_encode( $instance_data );

$this->add_render_attribute( 'form-input',
	array(
		'class'       => array(
			'jet-subscribe-form__input jet-subscribe-form__mail-field',
		),
		'type'               => 'email',
		'name'               => 'email',
		'placeholder'        => $submit_placeholder,
		'data-instance-data' => htmlspecialchars( $instance_data ),
	)
);

$icon_html = '';

if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
	$icon_html = $this->_get_icon( 'button_icon', '<span class="jet-subscribe-form__submit-icon jet-elements-icon">%s</span>' );
}
$submit_url =  admin_url('admin-ajax.php?action=jet_elements_subscribe_optin_form_submission');
$actionXhrUrl = preg_replace('#^https?:#', '', $submit_url);
?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<form method="POST" action-xhr="<?php echo $actionXhrUrl;?>" class="jet-subscribe-form__form">
		<div class="jet-subscribe-form__input-group">
			<div class="jet-subscribe-form__fields">
				<input <?php echo $this->get_render_attribute_string( 'form-input' ); ?>><?php
					$this->generate_additional_fields();
				?></div>
			<?php echo sprintf( '<span class="jet-subscribe-form__submit elementor-button elementor-size-md">%s<input class="form-submit jet-elements-s" type="submit" name="submit" value="%s"></span>', $icon_html, $submit_button_text ); ?>
		</div>
		   <div submit-success>
                       <template type="amp-mustache">
                       {{successmsg}}
                       </template>
           </div>
           <div submit-error>
                       <template type="amp-mustache">
                          {{response}}
                       </template>
            </div>
	</form>
</div>
