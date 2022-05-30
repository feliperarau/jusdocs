<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Amp_Alert extends Widget_Base {
	
	public function get_name() {
		return 'alert';
	}

	public function get_title() {
		return __( 'Amp Alert', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}
	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		
		$settings['background'] = (!empty($settings['background']) ? $settings['background']:'');
		$settings['border_color'] = (!empty($settings['border_color']) ? $settings['border_color']:'');
		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'');
		$settings['description_color'] = (!empty($settings['description_color']) ? $settings['description_color']:'');
		
		$background_color_css = '';
		if(!empty($settings['background'])){
			$background_color_css = '.elementor-element-'.$this->get_id().' .elementor-alert{
				background:'.$settings['background'].';
				border-color: '.$settings['border_color'].';
			}';
		}else{
			$default_css = '.elementor-element-'.$this->get_id().' .elementor-alert{
				border-color: '.$settings['border_color'].';
			}

			';
		}
		$border_width_css = '';
		if(!empty($settings['border_left-width']['size']) || isset($settings['border_left-width']['size'])){
			$border_width_css = '.elementor-element-'.$this->get_id().' .elementor-alert{
				border-left-width:'.$settings['border_left-width']['size'].''.$settings['border_left-width']['unit'].';
			}';
		}else{
			$border_width_css = '.elementor-element-'.$this->get_id().' .elementor-alert{
				border-left-width:5px;
			}';
		}
		$inline_styles = '
			.elementor-element-'.$this->get_id().' .elementor-alert-description{
				color:'.$settings['description_color'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-alert .elementor-alert-title{
				font-size:16px;
				font-weight: 600;
				color:'.$settings['title_color'].';
			}
			
		'.$border_width_css.''.$default_css.''.$background_color_css;
        //echo $inline_styles;
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-alert'][$this->get_id()] = $inline_styles;
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings['alert_type'] = (!empty($settings['alert_type']) ? $settings['alert_type']:'info');
		$settings['show_dismiss'] = (!empty($settings['show_dismiss']) ? $settings['show_dismiss']:'show');
		$this->amp_elementor_widget_styles();
		if ( empty( $settings['alert_title'] ) ) {
			return;
		}

		if ( ! empty( $settings['alert_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-alert elementor-alert-' . $settings['alert_type'] );
		}

		$this->add_render_attribute( 'wrapper', 'role', 'alert' );

		$this->add_render_attribute( 'alert_title', 'class', 'elementor-alert-title' );

		$this->add_inline_editing_attributes( 'alert_title', 'none' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >
			<span <?php echo $this->get_render_attribute_string( 'alert_title' ); ?>><?php echo $settings['alert_title']; ?></span>
			<?php
			if ( ! empty( $settings['alert_description'] ) ) :
				$this->add_render_attribute( 'alert_description', 'class', 'elementor-alert-description' );

				$this->add_inline_editing_attributes( 'alert_description' );
				?>
				<span <?php echo $this->get_render_attribute_string( 'alert_description' ); ?>><?php echo $settings['alert_description']; ?></span>
			<?php endif; ?>
			<?php if ( 'show' === $settings['show_dismiss'] ) : ?>
				<button type="button" class="elementor-alert-dismiss">
					<span aria-hidden="true">&times;</span>
					<span class="elementor-screen-only"><?php echo __( 'Dismiss alert', 'elementor' ); ?></span>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<# if ( settings.alert_title ) {
			view.addRenderAttribute( {
				alert_title: { class: 'elementor-alert-title' },
				alert_description: { class: 'elementor-alert-description' }
			} );

			view.addInlineEditingAttributes( 'alert_title', 'none' );
			view.addInlineEditingAttributes( 'alert_description' );
			#>
			<div class="elementor-alert elementor-alert-{{ settings.alert_type }}" role="alert">
				<span {{{ view.getRenderAttributeString( 'alert_title' ) }}}>{{{ settings.alert_title }}}</span>
				<span {{{ view.getRenderAttributeString( 'alert_description' ) }}}>{{{ settings.alert_description }}}</span>
				<# if ( 'show' === settings.show_dismiss ) { #>
					<button type="button" class="elementor-alert-dismiss">
						<span aria-hidden="true">&times;</span>
						<span class="elementor-screen-only"><?php echo __( 'Dismiss alert', 'elementor' ); ?></span>
					</button>
				<# } #>
			</div>
		<# } #>
		<?php
	}
}
